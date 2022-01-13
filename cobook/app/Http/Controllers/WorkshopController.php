<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class WorkshopController extends Controller
{
    //retrieve all the workshops
    public function index(Request $request)
    {

        try {
            $workshops = DB::table('workshops')
                            ->select('workshop_id', 'location_id', 'name', 'startDate', 'endDate', 'description')
                            ->where('workshopEn', 1)
                            ->get();
        } catch(QueryException $ex) {
            return response()->apiJson([], 401, 'Bad Select', $ex->getMessage());
        }

        foreach($workshops as $workshop) {
            try {
                $location =  DB::table('locations')
                                ->select('name', 'address', 'latitude', 'longitude')
                                ->where('location_id', $workshop->location_id)
                                ->where('locationEn', 1)
                                ->first();
            } catch(QueryException $ex) {
                return response()->apiJson([], 401, 'Bad Select', $ex->getMessage());
            }

            $workshop->location = $location;
            unset($workshop->location_id);
            
        }

        return response()->apiJson($workshops);
    }



    
}
