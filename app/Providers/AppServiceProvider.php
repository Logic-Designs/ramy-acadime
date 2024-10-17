<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Response::macro('success', function ($message, $data = [], $status = 200, $pagination = []) {
            $response = [
                'status' => true,
                'message' => __($message),
                'data' => $data,
            ];

            if (!empty($pagination)) {
                $response['pagination'] = $pagination;
            }

            return response()->json($response, $status);
        });

        Response::macro('error', function($message = '', $errors = '', $status = 400) {
            return response()->json([
                'status' => false,
                'message' => __($message),
                'errors' => $errors,
            ], $status);
        });
    }
}
