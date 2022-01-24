<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

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


    //create a workshop
    public function create(Request $request)
    {
        $location = '';
        $workshop = '';

        try {
            $this->validate($request, [
                'locationName' => 'required|string',
                'address' => 'required|string',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'workshopName' => 'required|string',
                'startDate' => 'required|date',
                'endDate' => 'required|date',
                'description' => 'required'
            ]);
        } catch(ValidationException $ex) {
            // print_r($ex->errors());
            // exit;

            return response()->apiJson([], 401, 'Bad Validation', json_encode($ex->errors()));
        }

        $startDate = date("Y-m-d", strtotime($request->startDate));
        $endDate = date("Y-m-d", strtotime($request->endDate));


        try {
            $location = DB::table('locations')->insertGetId([
                'name' => $request->locationName,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude
            ]);
        } catch(QueryException $ex) {
            return response()->apiJson([], 401, 'Bad Create', $ex->getMessage());
        }

        try {
            $workshop = DB::table('workshops')->insertGetId([
                'location_id' => $location,
                'name' => $request->workshopName,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'description' => $request->description
            ]);
        } catch(QueryException $ex) {
            return response()->apiJson([], 401, 'Bad Create', $ex->getMessage());
        }

        return response()->apiJson([
            'result' => 'success',
            'workshop_id' => $workshop 
        ]);
    }

    
}
