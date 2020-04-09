<?php

namespace App\Http\Controllers\API;

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
        return UserChannels::where('userId',$user['id']);
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
            ::where('userId',$user['id'])
            ->where('channelId',$id)
            ->doesntExist()
            )
        {
            $input['userId'] = $user['id'];
            $input['channelId'] =  (integer)$id;
            $validator = Validator::make($input, [ 
                'userId' => 'required|integer|exists:App\User,id',
                'channelId' => 'required|integer|exists:App\User,id', 
            ]);
            if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], 400);            
            }

            $channel = Channel::find($id);
            $channel['members'] =$channel['members']+1;
            $channel->save();

            $userChannels = UserChannels::create($input);
            
            return \response()->json(['success'=>$userChannels], 201);
        }
        else{
            return response()->json(['error'=>'User already member of this channel'], 400);
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
