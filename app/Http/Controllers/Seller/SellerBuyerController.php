<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SellerBuyerController extends ApiController
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
    public function index(Seller $seller)
    {
        $buyers = $seller->products()->with('transactions.buyer')->get()
        ->pluck('transactions')
        ->collapse()
        ->pluck('buyer')
        ->unique()
        ->values()
        ;
        return $this->showAll($buyers);
    }
}
