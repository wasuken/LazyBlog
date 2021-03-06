<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

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
        Validator::extend('not_url', function ($attribute, $value, $parameters, $validator) {
            $result = preg_match('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', $value);
            return $result <= 0;
        });
        Validator::extend('is_author', function ($attribute, $value, $parameters, $validator) {
            $user = Auth::user();
            $page = \App\Page::find($value);
            return intval($user->id) === intval($page->user_id);
        });
    }
}
