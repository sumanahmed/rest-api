<?php

namespace App\Http\Controllers;

use App\Model\Product;
use Illuminate\Http\Request;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Product\ProductResource;
use App\Exceptions\ProductNotBelongsToUser;
use Auth;

class ProductController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api')->except('index','show');
    }

    public function index()
    {
        return ProductCollection::collection(Product::paginate(5));
    }
   
    public function create()
    {
        //
    }
   
    public function store(Request $request)
    { 
        $this->validate($request,[
            'name'  => 'required|max:255|unique:products',
            'description'  => 'required',
            'price'  => 'required|max:10',
            'stock'  => 'required|max:6',
            'discount'  => 'required|max:2',
        ]);

        $product = new Product;
        $product->name = $request->name;
        $product->detail = $request->description;
        $product->stock = $request->stock;
        $product->price = $request->price;
        $product->discount = $request->discount;
        $product->save();
        return response([
            'data'  => new ProductResource($product)
        ], 201);
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    public function edit(Product $product)
    {
        //
    }

    public function update(Request $request, Product $product)
    {
        $this->productUserCheck($product);
        $request['detail'] = $request->description;
        unset($request['description']);
        $product->update($request->all());
        return response([
            'data'  => new ProductResource($product)
        ], 200);
    }
    
    public function destroy(Product $product)
    {
        $this->productUserCheck($product);
        $product->delete();
        return response(null, 204);
    }

    public function productUserCheck($product){
        if(Auth::id() !== $product->user_id){
            throw new ProductNotBelongsToUser;
        }
    }
}
