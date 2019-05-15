<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategoryBuyerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category)
    {
        $this->allowedAdminAction();
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
