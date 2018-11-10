<?php

namespace App\Http\Controllers\Seller;

use App\User;
use App\Seller;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $products = $seller->products;
        return $this->showAll($products);
    }

   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,User $seller)
    {
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'quantity' => 'required|integer|min:1',
            'image' => 'required|image'
        ];
        $this->validate($request,$rules);
        $data =  $request->all();
        $data['status'] = Product::UNAVAILABLE_PRODUCT;
        $data['seller_id'] = $seller->id;
        $data['image'] = $request->image->store('');
        $product = Product::create($data);
        return $this->showOne($product);
    }
   
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller,Product $product)
    {
        $rules = [
            'quantity' => 'integer|min:1',
            'status' => 'in:'.Product::AVAILABLE_PRODUCT.','.Product::UNAVAILABLE_PRODUCT,
            'image' => 'image'
        ];
        $this->validate($request,$rules);
        $this->isProductOwner($seller,$product);
        $product->fill($request->only([
            'quantity',
            'name',
            'description'
        ]));
        if($request->has('status'))
        {
            $product->status = $request->status;
            if($product->isAvailable() && $product->categories()->count() == 0 )
            {
                return $this->errorResponse("For product to be available status it must have atleast one category",409);
            }
        }
        if($request->hasFile('image'))
        {
            Storage::delete($product->image);
            $product->image = $request->image->store('');
        }
        if($product->isClean())
        {
            return $this->errorResponse("Nothing to update.",422);
        }
        $product->save();
        return $this->showOne($product);
    }

    protected function isProductOwner(Seller $seller,Product $product)
    {
        if($seller->id != $product->seller_id)
        {
            throw new HttpException(422,"The product information can only be modified by the owner of the product");
            
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller,Product $product)
    {
        $this->isProductOwner($seller,$product);
        $product->delete();
        Storage::delete($product->image);
        return $this->showOne($product);
    }
}
