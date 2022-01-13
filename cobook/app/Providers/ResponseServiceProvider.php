<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('apiJson', function($data, $errorCode = 200, $errorMsg = null, $errorDebug = null){
            $debug = null;
    
            if (strtolower(config('app.env')) === 'local') {
                $debug = $errorDebug;
            }

            $formattedData = [
                'data' => $data,
                'serverSettings' => [
                    'servername' => config('app.servername'),
                    'environment' => config('app.env')
                ],
                'errors' => [
                    'code' => $errorCode,
                    'message' => $errorMsg,
                    'debug' => $debug
                ]
            ];

            return response()->json(
                $formattedData,
                $errorCode
            );
        });
    }
}
