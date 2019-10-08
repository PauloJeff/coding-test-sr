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
        return $products;
    }

    /**
     * Deletar produtos
     */
    public function delete($id)
    {
        $product = Product::find($id);
        $product->stock()->delete();
        $product->delete();

        return response()->json([
            'message' => 'Produto excluído'
        ], 201);
    }

    public function subStock($store_id, $product_id, $qtd)
    {
        try{
            $stock = Stock::where('product_id', $product_id)
                ->where('store_id', $store_id);
            $stock->qtd_prod = $stock->qtd_prod - $qtd;
            $stock->update();
        } catch(Exception $e)
        {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
