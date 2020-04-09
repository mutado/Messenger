<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserChannels extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'userId', 'channelId'
    ];
}
