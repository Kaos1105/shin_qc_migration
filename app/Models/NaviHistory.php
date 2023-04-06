<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class NaviHistory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date_start',
        'user_id',
        'story_id',
        'done_status',
        'thread_id',

        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
