<?php

namespace App\Http\Controllers\API;

use App\Exceptions\ProductException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    /**
     * List available Product items.
     * @response array{
     *  data: ProductResource[], 
     *  links: array{first: string, last: string, prev: string, next: string}, 
     *  meta: array{current_page: integer, from: integer, last_page: integer, links: array{}} 
     * }
     * @status 200
     */

    public function index()
    {
        // get the produt items 10 by page
        $products = Product::paginate(10);

        // return the ProductCollection
        return ProductResource::collection($products);
    }

    /**
     * Create a new product
     * @param ProductRequest $request
     * @return ProductResource
     * @response {data: ProductResource}
     * @status 201
     * 
     */
    public function store(ProductRequest $request): ProductResource
    {
        try {

            // Validate the request data
            $validatedData = $request->validated();
            
            // create the product
            $product = Product::create($validatedData);

            // return the last resource created
            return new ProductResource($product);

        } catch (ValidationException $e) {

            return response()->json([
                "message" => "Create error",
                'errors' => $e->errors()
            ], 404);

        }

    }

    /**
     * Find a product by id
     * @param integer $id
     * @return ProductResource
     * @status 200
     * @response {data: ProductResouser }
     */
    public function show($id): ProductResource
    {
        try {

            // find and return the resource
            return new ProductResource(Product::findOrFail($id));
            
        }catch(ModelNotFoundException $e){

            throw ProductException::notExist();

        }
    }

    /**
     * Update product from db
     * @param ProductRequest $request
     * @param integer $id
     * @status 204
     */
    public function update(ProductRequest $request, $id)
    {
        try {
            
            // Check if the id exists 
            $product = Product::findOrFail($id);
            
            // Validate the request data
            $validatedData = $request->validated();

            // Update the product attributes
            $product->fill($validatedData);
            
            // Save the updated product
            $product->save();

            return response()->noContent();

        }catch(ModelNotFoundException $e){

            throw ProductException::notExist();

        } catch (ValidationException $e) {

            return response()->json([
                "message" => "Error try to update the product",
                'errors' => $e->errors()
            ], 404);

        }

    }

    /**
     * Delete product from db
     * @param integer $id
     * @status 204
     */
    public function destroy($id)
    {
        
        try {
            // Check if the id exists 
            $product = Product::findOrFail($id);

            // confirm destroy product
            $product->delete();

            return response()->noContent();

        }catch(ModelNotFoundException $e){

            throw ProductException::notExist();
        
        }
    }
}
