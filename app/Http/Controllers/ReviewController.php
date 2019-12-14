<?php

namespace App\Http\Controllers;

use App\Model\Review;
use App\Model\Product;
use App\Http\Resources\ReviewResource;
use Illuminate\Http\Request;

class ReviewController extends Controller
{    
    public function index(Product $product)
    {   
        return ReviewResource::collection($product->reviews);
    }
    
    public function create()
    {
        //
    }
    
    public function store(Request $request, Product $product)
    {
        $this->validate($request, [
            'customer'  => 'required',
            'star'  => 'required|integer|between:0,5',
            'review'  => 'required'
        ]);
        $review = new Review($request->all());
        $product->reviews()->save($review);
        return response([
            'data'  => new ReviewResource($review)
        ], 201);
    }
    
    public function show(Review $review)
    {
        //
    }
    
    public function edit(Review $review)
    {
        //
    }
    
    public function update(Request $request, Product $product, Review $review)
    {
        $review->update($request->all());
        return response([
            'data'  => new ReviewResource($review)
        ], 201);
    }
    
    public function destroy(Product $product, Review $review)
    {
        $review->delete();
        return response(null, 204);
    }
}
