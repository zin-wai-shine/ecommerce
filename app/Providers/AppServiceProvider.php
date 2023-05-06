<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('valid_sort_column', function($attribute, $value, $parameters, $validator) {
            $tableName = $parameters[0];
            return Schema::hasColumn($tableName, $value);
        });

        Validator::extend('valid_order_column', function($attribute, $value, $parameters, $validator) {
            $accept = ['asc','desc'];
            return in_array($value,$accept);
        });
    }
}
