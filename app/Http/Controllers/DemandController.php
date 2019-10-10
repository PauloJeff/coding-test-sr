<?php

namespace App\Http\Controllers;

use App\DemandItem;
use Illuminate\Http\Request;
use App\Http\Controllers\ProductController;
use App\Demand;
use App\Stock;
use Exception;

class DemandController extends Controller
{
    /**
     * Criação de pedidos
     * 
     * @param [integer] client_id
     * @param [string] code_request
     * @param [integer] status
     * @param [double] freight_price
     * @param [array] order_itens
     */
    public function addDemand(Request $request)
    {
        try {
            $request->validate([
                'client_id' => 'required|exists:client,id',
                'code_demand' => 'required|string|unique:demand',
                'status' => 'required|exists:status_demand,id',
                'freight_price' => 'required|numeric',
                'demand_itens' => 'required|array|min:1',
                'demand_itens.*.product_id' => 'required|exists:product,id',
                'demand_itens.*.qtd_item' => 'required|integer|min:1'
            ]);

            $user = $request->user();
            $store = $user->store;
            $this->validStock($request->demand_itens, $store->id);

            $demand = new Demand([
                'client_id' => $request->client_id,
                'code_demand' => $request->code_demand,
                'status' => $request->status,
                'freight_price' => $request->freight_price,
                'store_id' => $store->id
            ]);

            $demand->save();

            foreach ($request->demand_itens as $i => $value) {
                $demand_item = new DemandItem([
                    'demand_id' => $demand->id,
                    'product_id' => $value['product_id'],
                    'qtd_item' => $value['qtd_item']
                ]);

                $demand_item->save();
                $stock = Stock::where('product_id', $value['product_id'])
                    ->where('store_id', $store->id)
                    ->first();
                $stock->qtd_prod = $stock->qtd_prod - $value['qtd_item'];
                $stock->update();
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Pedido criado com sucesso!'
        ], 201);
    }

    /**
     * Listagem de pedidos
     */
    public function demand()
    {
        $demands = Demand::all();
        
        $data = [];
        foreach($demands as $i => $demand)
        {
            $data[] = [
                'client' => $demand->client->name . ' ' . $demand->client->lastname,
                'code_demand' => $demand->code_demand,
                'freight_price' => "R$" . number_format($demand->freight_price, 2, ',', '.'),
                'date_request' => $demand->date_request,
                'store' => $demand->store->name,
                'status' => $demand->statusDemand->name
            ];

            foreach($demand->demandItem as $index => $demandItem)
            {
                $data[$i]['demand_itens'][] = [
                    'product' => $demandItem->product->name,
                    'qtd_item' => $demandItem->qtd_item
                ];
            }
        }

        return $data;
    }

    /**
     * Cancelamento de pedido
     * 
     * @param [integer] id
     */
    public function cancelDemand($id)
    {
        try{
            $demand = Demand::find($id);
            $demand->status = 4;

            $itens = $demand->demandItem();
            foreach($itens as $i => $item)
            {
                $stock = Stock::where('product_id', $item->product_id)
                        ->where('store_id', $demand->store_id)
                        ->first();
                
                $stock->qtd_prod = $stock->qtd_prod + $item->qtd_item;
                $stock->update();
            }
            $demand->update();
        } catch(Exception $e)
        {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Pedido cancelado!'
        ], 201);
    }

    /**
     * Atualiza o status do pedido
     * 
     * @param [integer] status
     */
    public function updateDemand(Request $request, Demand $demand)
    {
        try{
            $request->validate([
                'status' => 'required|exists:status_demand,id'
            ]);
            $demand->update($request->only(['status']));
        } catch(Exception $e)
        {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Pedido atualizado!'
        ], 201);
    }

    public function validStock($demand_itens, $store_id)
    {
        foreach ($demand_itens as $i => $value) {
            $stock = Stock::where('product_id', $value['product_id'])
                ->where('store_id', $store_id)
                ->first();

            if ($value['qtd_item'] > $stock->qtd_prod) {
                throw new Exception('A quantidade de produtos do pedidos é maior que o estoque!');
            }
        }

        return true;
    }
}
