<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Validator;
use Carbon\Carbon;

class MessageController extends Controller
{
    public $successStatus = 200;
    public $createdStatus = 201;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        return Message
        ::where('userSender',$user['id'])
        ->paginate(20);
    }

    public function getById($id)
    {
        $user = Auth::user();
        echo $user['id'];
        return Message::find($id)->where('userSender',$user['id'])->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'content' => 'required',
            'channel' => 'required|integer|exists:App\Channel,id', 
            'send_at' => 'required|date', 
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        $input = $request->all();
        $input['userSender'] = Auth::user()['id'];
        $input['recieved']= Carbon::now();
        $input['seen'] = false;
        $msg = Message::create($input);

        return response()->json(['success'=>$msg], $this->createdStatus); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        //
    }
}
