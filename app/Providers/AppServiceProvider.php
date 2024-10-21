<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;
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

        Schema::defaultStringLength(191);


        Response::macro('success', function ($message, $data = [], $status = 200, $pagination_data = null) {
            $response = [
                'status' => true,
                'message' => __($message),
                'data' => $data,
            ];

            if ($pagination_data) {
                $response['pagination'] = [
                    'total' => $pagination_data->total(),
                    'current_page' => $pagination_data->currentPage(),
                    'last_page' => $pagination_data->lastPage(),
                    'per_page' => $pagination_data->perPage(),
                    'from' => $pagination_data->firstItem(),
                    'to' => $pagination_data->lastItem(),
                    'item_count' => $pagination_data->count()
                ];
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
