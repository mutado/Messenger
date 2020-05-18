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
        ::where('userSender', $user['id'])
        ->paginate(20);
    }

    public function getById($id)
    {
        $user = Auth::user();
        echo $user['id'];
        return Message::find($id)->where('userSender', $user['id'])->get();
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
            'channelId' => 'required|integer|exists:App\Channel,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400);
        }
        $input = $request->all();

        if (!isset($input['type'])||empty($input['type'])) {
            $input['type'] = "message";
        }
        $msg = self::CreateMessage($input['content'], Auth::user()['id'], $input['channelId']);

        // Call new message event

        return response()->json(['success'=>$msg], $this->createdStatus);
    }

    /**
     * @var $content
     */
    public static function CreateMessage($content, $userId, $channelId, $type = "message")
    {
        // echo \json_encode($msg);
        // $msg->create();
        $msg = Message::create([
            'content'=>$content,
            'userSender'=>$userId,
            'channelId'=>$channelId,
            'seen'=>false,
            'type'=>$type
            ]);
        \broadcast(new \App\Events\MessageSent($msg))->toOthers();
        
        return $msg;
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
