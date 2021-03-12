<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);  // https://laravel-news.com/laravel-5-4-key-too-long-error

        /**
         * Validate ExistsInDatabase or 0/null
         * From: https://laracasts.com/discuss/channels/laravel/validator-ignoring-field-if-value-is-0-for-exists-rule
         *
         * Can be used like
         *
         *    'parent_id' => 'sometimes|exists_or_null:company_categories,id'
         */
        Validator::extend(
            'exists_or_null',
            function ($attribute, $value, $parameters) {
                if (intval($value) == 0 || is_null($value)) {
                    return true;
                } else {
                    $validator = Validator::make([$attribute => intval($value)], [
                        $attribute => 'exists:'.implode(',', $parameters),
                    ]);

                    return ! $validator->fails();
                }
            }
        );
    }
}
