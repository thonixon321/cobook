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
        $hostName = $request->query('hostName');

        try {
            $workshops = DB::table('workshops')
                            ->select('workshop_id', 'location_id', 'workshops.name as workshopName','startDate', 'endDate', 'description', 'users.name', 'users.email')
                            ->leftJoin('users', 'users.id', '=', 'workshops.created_by')
                            ->when($hostName, function($query) use ($hostName) {
                                //when host name is provided, search for workshops with that host
                                $query->where('users.name', 'LIKE', '%'.$hostName.'%');
                            })
                            ->where('workshopEn', 1)
                            ->get();
        } catch(QueryException $ex) {
            return response()->apiJson([], 401, 'Bad Select', $ex->getMessage());
        }

        foreach($workshops as $workshop) {
            try {
                $location =  DB::table('locations')
                                ->select('location_id', 'name', 'address', 'latitude', 'longitude')
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
        //validate request
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
            return response()->apiJson([], 401, 'Bad Validation', json_encode($ex->errors()));
        }
        //reformat date
        $startDate = date("Y-m-d", strtotime($request->startDate));
        $endDate = date("Y-m-d", strtotime($request->endDate));
        //add location
        try {
            $location = DB::table('locations')->insertGetId([
                'name' => $request->locationName,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'created_by' => $request->user()->id
            ]);
        } catch(QueryException $ex) {
            return response()->apiJson([], 401, 'Bad Create', $ex->getMessage());
        }
        //add workshop
        try {
            $workshop = DB::table('workshops')->insertGetId([
                'location_id' => $location,
                'name' => $request->workshopName,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'description' => $request->description,
                'created_by' => $request->user()->id
            ]);
        } catch(QueryException $ex) {
            return response()->apiJson([], 401, 'Bad Create', $ex->getMessage());
        }

        return response()->apiJson((object)[
            'result' => 'success',
            'workshop_id' => $workshop 
        ]);
    }



    //update a workshop
    public function update(Request $request)
    {
        $locationId = (int) $request->locationId;
        $workshopId = (int) $request->workshopId;
        $location = '';
        $workshop = '';
    
        //validate request
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
            return response()->apiJson([], 401, 'Bad Validation', json_encode($ex->errors()));
        }

        $userWorkshop = $this->checkAuth();

        if ($userWorkshop->created_by === $request->user()->id) {

            //reformat date
            $startDate = date("Y-m-d", strtotime($request->startDate));
            $endDate = date("Y-m-d", strtotime($request->endDate));
            //update location
            try {
                DB::table('locations')
                ->where('location_id', $locationId)
                ->where('locationEn', 1)
                ->update([
                    'name' => $request->locationName,
                    'address' => $request->address,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude
                ]);
            } catch(QueryException $ex) {
                return response()->apiJson([], 401, 'Bad Update', $ex->getMessage());
            }
            //update workshop
            try {
                DB::table('workshops')
                ->where('workshop_id', $workshopId)
                ->where('workshopEn', 1)
                ->update([
                    'location_id' => $locationId,
                    'name' => $request->workshopName,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'description' => $request->description
                ]);
            } catch(QueryException $ex) {
                return response()->apiJson([], 401, 'Bad Update', $ex->getMessage());
            }
            return response()->apiJson((object)[
                'result' => 'success'
            ]);
        } else {
            return response()->apiJson([], 401, 'Bad User', $ex->getMessage());
        }


    }



    //add attendee for workshop
    public function addAttendee(Request $request)
    {
        //validate request
        try {
            $this->validate($request, [
                'workshopId' => 'required|integer'
            ]);
        } catch(ValidationException $ex) {
            return response()->apiJson([], 401, 'Bad Validation', json_encode($ex->errors()));
        }

        //add attendee to workshop
        try {
            DB::table('user_workshop')
            ->insert([
                'user_id' => $request->user()->id,
                'workshop_id' => $request->workshopId,
                'role' => 'attendee'
            ]);
        } catch(QueryException $ex) {
            return response()->apiJson([], 401, 'Bad Insert', $ex->getMessage());
        }

        return response()->apiJson((object)[
            'result' => 'success'
        ]);
    }



    //get attendees for workshop
    public function getAttendees(Request $request)
    {
        $attendees = '';
        
        //validate request
        try {
            $this->validate($request, [
                'workshopId' => 'required|integer'
            ]);
        } catch(ValidationException $ex) {
            return response()->apiJson([], 401, 'Bad Validation', json_encode($ex->errors()));
        }

        $userWorkshop = $this->checkAuth();

        //make sure user is the creator of requested workshop
        if ($userWorkshop->created_by === $request->user()->id) {
            //get attendees
            try {
              $attendees = DB::table('user_workshop')
                ->select('users.name', 'users.email', 'role')
                ->leftJoin('users', 'users.id', '=', 'user_workshop.user_id')
                ->where('user_workshop.workshop_id', $request->workshop_id)
                ->where('users.userEn', 1)
                ->get();
            } catch(QueryException $ex) {
                return response()->apiJson([], 401, 'Bad Select', $ex->getMessage());
            }
        } else {
            return response()->apiJson([], 401, 'Bad User', $ex->getMessage());
        }

        return response()->apiJson($attendees);
    }



    public function removeAttendee(Request $request)
    {
        //remove attendee from workshop
        try {
            DB::table('user_workshop')
            ->where('workshop_id', (int) $request->workshopId)
            ->where('user_id', $request->user()->id)
            ->delete();
        } catch(QueryException $ex) {
            return response()->apiJson([], 401, 'Bad Delete', $ex->getMessage());
        }

        return response()->apiJson((object)[
            'result' => 'success'
        ]);
    }




    private function checkAuth()
    {
        try {
            $userWorkshop = DB::table('workshops')
                             ->select('created_by')
                             ->where('workshopEn', 1)
                             ->first();
         } catch (QueryException $ex) {
             return response()->apiJson([], 401, 'Bad Select', $ex->getMessage());
         }

         return $userWorkshop;
    }

    
}
