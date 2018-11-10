<?php

namespace App;

use App\Seller;
use App\Category;
use App\Transaction;
use App\Events\ProductUpdateEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    
    const AVAILABLE_PRODUCT = 'available';
    const UNAVAILABLE_PRODUCT = 'unavailable';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    protected $fillable = [
    	'name',
    	'description',
    	'quantity',
    	'status',
    	'image',
    	'seller_id'
    ];

    protected $hidden = [
        'pivot'
    ];

    protected $dispatchesEvents = [
        'updated' => ProductUpdateEvent::class,
        // 'saved' => ProductUpdateEvent::class,
    ];

    public function isAvailable()
    {
    	return $this->status === Product::AVAILABLE_PRODUCT;
    }

    public function seller()
    {
    	return $this->belongsTo(Seller::class);
    }

    public function transactions()
    {
    	return $this->hasMany(Transaction::class);
    }

    public function categories()
    {
    	return $this->belongsToMany(Category::class);
    }


}
