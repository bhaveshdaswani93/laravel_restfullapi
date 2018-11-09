<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategoryBuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category)
    {
        $buyers = $category->products()->has('transactions')
                ->with('transactions.buyer')->get()
                ->pluck('transactions')
                ->collapse()
                ->pluck('buyer')
                ->unique()
                ->values()
                ;
        return $this->showAll($buyers);
    }
}
