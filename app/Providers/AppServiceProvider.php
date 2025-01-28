<?php

namespace App\Providers;

use App\Rules\FileAttachment;
use App\Services\ToastService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('toast', function () {
            return new ToastService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            return $user->super_admin;
        });

        Validator::extend('file_attachment', function ($attribute, $value, $parameters, $validator) {
            $rule = new FileAttachment();

            $fail = function ($message) use ($validator, $attribute) {
                $validator->errors()->add($attribute, $message);
            };

            $rule->validate($attribute, $value, $fail);

            return !$validator->errors()->has($attribute);
        });
    }
}
