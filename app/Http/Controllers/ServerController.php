<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function getStatus()
    {
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            return \response()->json("Could not connect to the database.",200);
        }
        return \response()->json("All services running.",200);
    }
}
