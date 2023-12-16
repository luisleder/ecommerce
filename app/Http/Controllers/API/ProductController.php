<?php

namespace App\Http\Controllers\API;

use App\Exceptions\ProductException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Rules\ImageURL;

class ProductController extends Controller
{

    /**
     * Get all products by pagination
     * @return Response Paginate of product
     */
    public function index()
    {
        $products = Product::simplePaginate(20);
        return response()->json($products, 200);

    }

    /**
     * Create a new product
     * @param Request
     * @return Response 
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => ['required'],
            'description' => ['required'],
            'image' => ['required','url', new ImageURL],
            'brand' => ['required'],
            'price' => ['required','numeric','regex:/^\d+(\.\d{1,2})?$/'],
            'price_sale' => ['required','numeric','regex:/^\d+(\.\d{1,2})?$/'],
            'category' => ['required'],
            'stock' => ['required','integer']
        ];
        
        try {

            $request->validate($rules);
            $product = Product::create($request->only(array_keys($rules)));
            return response()->json($product, 201);

        } catch (ValidationException $e) {

            return response()->json([
                "message" => "Create error",
                'errors' => $e->errors()
            ], 404);

        }

    }

    /**
     * Find a product by id
     * @param $id
     * @return Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        
        if ($product) {

            return response()->json($product, 200);

        } else {
            
            throw ProductException::notExist();
            
        }
    }

    /**
     * Update product from db
     * @param Request $request
     * @param Int $id
     * @return Resonpe JSON
     */
    public function update(Request $request, $id)
    {
        // Defined rules to validate
        $rules = [
            'name' => ['required'],
            'description' => ['required'],
            'image' => ['required','url', new ImageURL],
            'brand' => ['required'],
            'price' => ['required','numeric','regex:/^\d+(\.\d{1,2})?$/'],
            'price_sale' => ['required','numeric','regex:/^\d+(\.\d{1,2})?$/'],
            'category' => ['required'],
            'stock' => ['required','integer']
        ];

        try {
            
            // Check if the id exists 
            if (Product::where('id', $id)->exists()) {
                
                // Validate the request data
                $validatedData = $request->validate($rules);

                // Find the product by ID
                $product = Product::find($id);

                // Update the product attributes
                $product->fill($validatedData);
                
                // Save the updated product
                $product->save();

                return response()->json([
                    'message' => 'Product updated successfully',
                    'product' => $product
                ]);

            } else {
    
                throw ProductException::notExist();
    
            }
        
        } catch (ValidationException $e) {

            return response()->json([
                "message" => "Error try to update the product",
                'errors' => $e->errors()
            ], 404);

        }

    }

    /**
     * Delete product from db
     * @param $id
     * @return Response Json
     */
    public function destroy($id)
    {
        if(Product::where('id', $id)->exists()) {

            $product = Product::find($id);
            $product->delete();

            return response()->json([
              "message" => "product record deleted"
            ], 202);

        } else {

            throw ProductException::notExist();
        
        }
    }
}
