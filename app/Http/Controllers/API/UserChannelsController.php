<?php

namespace App\Http\Controllers\API;

// use App\Http\Controller\API\MessageController;
use App\Http\Controllers\Controller;
use App\UserChannels;
use App\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserChannelsController extends Controller
{
    /**
     * Display a listing of the channels for user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        return UserChannels::where('userId', $user['id']);
    }

    /**
     * Join channel
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function join($id)
    {
        $user = Auth::user();
        //check if user already joined
        if (
            UserChannels
            ::where('userId', $user['id'])
            ->where('channelId', $id)
            ->doesntExist()
            ) {
            $input['userId'] = $user['id'];
            $input['channelId'] =  (integer)$id;
            $validator = Validator::make($input, [
                'userId' => 'required|integer|exists:App\User,id',
                'channelId' => 'required|integer|exists:App\Channel,id',
            ]);
            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 400);
            }

            $channel = Channel::find($id);
            $channel['members'] =$channel['members']+1;
            $channel->save();

            $userChannels = UserChannels::create($input);
            self::sendMessage("{$user->name} joined this channel", $user->id, $id);
            \broadcast(new \App\Events\MemberJoined($user,$id))->toOthers();
            return \response()->json(['success'=>$userChannels], 201);
        } else {
            return response()->json(['error'=>'User already member of this channel'], 400);
        }
    }

    public function getMembers($id)
    {
        $user = Auth::user();
        if (UserChannels
        ::where('userId', $user['id'])
        ->where('channelId', $id)
        ->doesntExist()) {
            return response()->json(['error'=>'user not a member of this channel'], 400);
        } else {
            $uschs = UserChannels::where('channelId', $id)
            ->get()
            ->map(
                function ($item, $key) {
                    return $item->user;
                }
            );
            return \response()->json(['success'=>$uschs], 200);
        }
    }

    private function sendMessage($content, $userid, $channelId)
    {
        app('App\Http\Controllers\API\MessageController')->CreateMessage($content, $userid, $channelId, "service");
    }

    public function leave($id)
    {
        $user = Auth::user();
        //check if user already joined
        $usch = UserChannels
        ::where('userId', $user['id'])
        ->where('channelId', $id);
        if ($usch) {
            self::sendMessage("{$user->name} left this channel", $user->id, $id);
            $usch->delete();
            \broadcast(new \App\Events\MemberLeft($user,$id))->toOthers();

            return \response()->json(['success'=>"leaved channel"], 201);
        } else {
            return response()->json(['error'=>'User not a member of this channel'], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UserChannels  $userChannels
     * @return \Illuminate\Http\Response
     */
    public function show(UserChannels $userChannels)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserChannels  $userChannels
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserChannels $userChannels)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserChannels  $userChannels
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserChannels $userChannels)
    {
        //
    }
}
