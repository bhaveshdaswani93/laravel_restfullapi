<?php

namespace App\Providers;

use App\User;
use App\Buyer;
use App\Product;
use App\Transaction;
use App\Policies\UserPolicy;
use App\Policies\BuyerPolicy;
use Laravel\Passport\Passport;
use App\Policies\ProductPolicy;
use App\Policies\TransactionPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        Buyer::class => BuyerPolicy::class,
        Seller::class => SellerPolicy::class,
        User::class => UserPolicy::class,
        Transaction::class => TransactionPolicy::class,
        Product::class => ProductPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();
        Passport::tokensExpireIn(now()->addMinutes(2));

        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::enableImplicitGrant();
        Passport::tokensCan([
            'purchase-product' => 'Create a new transaction for a specific product',
            'manage-products' =>  'Create, reade, update, and delete products (CRUD)',
            'manage-account' => 'Read your account data, id, name, email, if verified, and if admin (cannot read password). Modify your account data (email, and password). Cannot delete your account',
            'read-general' => 'Read general information like purchasing categories, purchased products, selling products, selling categories, your transactions (purchases and sales)',
        ]);

        Gate::define('admin-access',function($user){
            return $user->isAdmin();
        });
        //
    }
}
