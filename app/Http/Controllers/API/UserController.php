<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api');
    }

    public function getProfile(Request $request){
        $data = $request->user();
        return response()->json($data,200);
    }

    public function getUserList(){
        $data = App\User::all();
        return response()->json($data,200);
    }
}
