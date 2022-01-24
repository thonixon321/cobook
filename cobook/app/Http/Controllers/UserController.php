<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\QueryException;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = DB::table('users')->select('*')->get();
        } catch(QueryException $ex) {
            return response()->apiJson([], 401, 'Bad Select', $ex->getMessage());
        }

        return response()->apiJson($users);
    }


    //get lat and lng of an address a user gives
    public function getLatLng($address)
    {
        $response = '';

        try {
            $response = Http::get('https://atlas.microsoft.com/search/address/json?&subscription-key='. config('app.azure_sub_key') .'&api-version=1.0&language=en-US&query='.$address);

            $response = $response->json();

            if (!$response['results']) {
                throw new Exception($response, 401);
            }
        } catch(Exception $ex) {
            return response()->apiJson([], 401, 'Bad Http', $ex->getMessage());
        }

        $lat = $response['results'][0]['position']['lat'];
        $lng = $response['results'][0]['position']['lon'];

        return response()->apiJson(['lat' => $lat, 'lng' => $lng]);

    }
}
