<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content','userSender', 'channelId', 'seen','type'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'send_at' => 'datetime',
        'recieved' => 'datetime',
    ];

    public function user(){
        return $this->belongsTo('App\User','userSender');
    }
}
