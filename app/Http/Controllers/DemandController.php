<?php

namespace App\Http\Controllers;

use App\DemandItem;
use Illuminate\Http\Request;
use App\Http\Controllers\ProductController;
use App\Demand;
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
        try{
            $product = new ProductController();
            $request->validate([
                'client_id' => 'required|exists:client,id',
                'code_demand'=> 'required|string',
                'status'=> 'required|exists:status_demand,id',
                'freight_price'=> 'required|numeric',
                'demand_itens' => 'required|array|min:1',
                'demand_itens.*.product_id' => 'required|exists:product,id',
                'demand_itens.*.qtd_item' => 'required|integer'
            ]);

            $store = $request->user()->store();
            var_dump($store);
            die;
            /*$demand = new Demand([
                'client_id' => $request->client_id,
                'code_request' => $request->code_request,
                'status' => $request->status,
                'freight_price' => $request->freight_price,
                'store_id' => $store->id
            ]);

            $demand->save();*/

            foreach($request->demand_itens as $i => $value)
            {
                /*$demand_item = new DemandItem([
                    'request_id' => $demand->id,
                    'product_id' => $value['product'],
                    'qtd_item' => $value['qtd_item']
                ]);

                $demand_item->save();*/
                $product->subStock($store->id, $value['product'], $value['qtd_item']);
            }
        } catch(Exception $e)
        {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Pedido criado com sucesso!'
        ], 201);
    }
}
