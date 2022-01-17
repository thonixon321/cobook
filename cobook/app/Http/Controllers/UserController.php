<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
}
