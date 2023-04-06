<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


/**
 * @mixin IdeHelperActivity
 */
class Activity extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'circle_id',
        'activity_category',
        'activity_title',
        'date_intended',
        'time_intended',
        'date_execution',
        'time_start',
        'time_finish',
        'time_span',
        'participant_number',
        'location',
        'content1',
        'content2',
        'content3',
        'content4',
        'content5',
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
