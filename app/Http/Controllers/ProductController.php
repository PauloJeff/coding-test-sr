<?php

namespace App\Http\Controllers;

use App\Product;
use App\Stock;
use Exception;
use Illuminate\Http\Request;

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
                'code_product' => 'required|string',
                'description' => 'required|string',
                'attributes' => 'required|string',
                'price' => 'required|numeric',
                'stock' => 'required|array',
                'stock.store' => 'required|exists:store,id',
                'stock.qtd_stock' => 'required|integer'
            ]);

            $product = new Product([
                'name' => $request->name,
                'code_product' => $request->code_product,
                'description' => $request->description,
                'attributes' => $request->attributes,
                'price' => $request->price
            ]);

            $product->save();

            foreach($request->stock as $index => $value)
            {
                $stock = new Stock([
                    'store' => $value->store,
                    'qtd_stock' => $value->qtd_stock
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

    public function updateProduct(Request $request, $id)
    {
        try{
            $request->validate([
                'name' => 'nullable',
                'code_product' => 'nullable',
                'description' => 'nullable',
                'attributes' => 'nullable',
                'price' => 'nullable',
            ]);

            $product = Product::find($id);
            var_dump($product->name);
            die;
        } catch(Exception $e)
        {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
