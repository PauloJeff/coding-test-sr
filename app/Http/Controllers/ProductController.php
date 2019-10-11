<?php

namespace App\Http\Controllers;

use App\DemandItem;
use App\Product;
use App\Stock;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Criação de produtos
     * 
     * @param [string] name
     * @param [string] code_product
     * @param [string] description
     * @param [string] attributes
     * @param [double] price
     * @param [array] stock
     */
    public function addProduct(Request $request)
    {
        try{
            $request->validate([
                'name' => 'required|string',
                'code_product' => 'required|string|unique:product',
                'description' => 'required|string',
                'attribute' => 'required|string',
                'price' => 'required|numeric',
                'stock' => 'required|array|min:1',
                'stock.*.store' => 'required|exists:store,id',
                'stock.*.qtd_stock' => 'required|integer'
            ]);

            $product = new Product([
                'name' => $request->name,
                'code_product' => $request->code_product,
                'description' => $request->description,
                'attributes' => $request->attribute,
                'price' => $request->price
            ]);

            $product->save();

            foreach($request->stock as $i => $value)
            {
                $stock = new Stock([
                    'product_id' => $product->id,
                    'store_id' => $value['store'],
                    'qtd_prod' => $value['qtd_stock']
                ]);

                $stock->save();
            }
        } catch(Exception $e)
        {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Produto criado com sucesso!'
        ], 201);
    }

    /**
     * Edição de produtos
     * 
     * @param [string] name
     * @param [string] code_product
     * @param [string] description
     * @param [string] attributes
     * @param [double] price
     * @param [array] stock
     */
    public function updateProduct(Request $request, Product $product)
    {
        try{
            $request->validate([
                'name' => 'nullable',
                'code_product' => 'nullable|unique:product',
                'description' => 'nullable',
                'attributes' => 'nullable',
                'price' => 'nullable',
            ]);

            $product->update($request->only(['name', 'code_product', 'description', 'attibutes', 'price']));
        } catch(Exception $e)
        {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Produto editado com sucesso!'
        ], 201);
    }

    /**
     * Exibição de produtos
     * 
     * @return [json] products
     */
    public function getProducts()
    {
        $products = Product::all();
        $data = [];
        foreach($products as $i => $value)
        {
            $data[] = [
                'name' => $value->name,
                'code_product' => $value->code_product,
                'description' => $value->description,
                'attributes' => $value->attributes,
                'price' => 'R$' . number_format($value->price, 2, ',', '.'),
            ];

            foreach($value->stock as $index => $stock)
            {
                $data[$i]['stock'][] = [
                    'store' => $stock->store->name,
                    'qtd_prod' => $stock->qtd_prod
                ];
            }
        }

        return $data;
    }

    /**
     * Deletar produtos
     */
    public function delete(Product $product)
    {
        $product->stock()->delete();
        $product->delete();

        return response()->json([
            'message' => 'Produto excluído'
        ], 201);
    }

    /**
     * Produtos com baixo estoque
     * 
     * @return [json] $products
     */
    public function getProductsLowStock()
    {
        $products = Product::join('stock', 'product_id', '=', 'product.id')
            ->where('stock.qtd_prod', '<', 3)
            ->get();

        $data = [];
        foreach($products as $i => $value)
        {
            $data[] = [
                'name' => $value->name,
                'code_product' => $value->code_product,
                'description' => $value->description,
                'attributes' => $value->attributes,
                'price' => 'R$' . number_format($value->price, 2, ',', '.'),
            ];

            foreach($value->stock as $index => $stock)
            {
                $data[$i]['stock'][] = [
                    'store' => $stock->store->name,
                    'qtd_prod' => $stock->qtd_prod
                ];
            }
        }

        return $data;
    }

    /**
     * Produtos mais vendidos
     * 
     * @return [json] $products
     */
    public function bestSeller()
    {
        $itens = DemandItem::all();
        
        $countItem = [];
        foreach($itens as $i => $item)
        {
            if(!isset($countItem[$item->id]))
            {
                $countItem[$item->id] = 0;
            }
            $countItem[$item->id] = [
                'name' => $item->product->name,
                'qtd_sale' => $countItem[$item->id] + $item->qtd_item
            ];
        }

        $countItem[3] = [
            'name' => 'Bagaceira',
            'qtd_sale' => 10
        ];

        $countItem[4] = [
            'name' => 'Bagaceira',
            'qtd_sale' => 20
        ];

        usort($countItem, function($a, $b) {
            return $b['qtd_sale'] <=> $a['qtd_sale'];
        });
        $bestSellers = array_slice($countItem, 0, 3);

        return $bestSellers;
    }
}
