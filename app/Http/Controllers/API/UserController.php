<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api');
    }
    public $successStatus = 200;

    public function getProfile(Request $request)
    {
        $data = $request->user();
        return response()->json($data, 200);
    }

    public function getAll()
    {
        $data = User::all();
        return response()->json($data, 200);
    }

    /* login api function
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $success = $user;
            $success['token'] =  $user->createToken('Messenger')-> accessToken;
            return response()->json(['success' => $success], $this-> successStatus);
        } elseif (Auth::attempt(['username' => request('username'), 'password' => request('password')])) {
            $user = Auth::user();
            $success = $user;
            $success['token'] =  $user->createToken('Messenger')-> accessToken;
            return response()->json(['success' => $success], $this-> successStatus);
        } else {
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|alpha_dash|unique:App\User',
            'email' => 'required|email|unique:App\User',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        echo $input['username'];

        if (!isset($input['name'])) {
            $input['name'] = $input['username'];
        }

        $user = User::create($input);
        $success = $user;
        $success['token'] =  $user->createToken('MyApp')-> accessToken;
        return response()->json(['success'=>$success], $this-> successStatus);
    }
    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function details()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this-> successStatus);
    }

    public function update(Request $request)
    {
        $input = $request->all();
        $user = User::find(Auth::user()->id);
        if ($input['name'] != $user->name) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 400);
            }
            $user->name = $input['name'];
        }
        if ($input['email']!=$user->email) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:App\User',
            ]);
            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 400);
            }
            $user->email = $input['email'];
        }
        $user->save();
        return response()->json(['success'=>$user], 200);
    }
}
