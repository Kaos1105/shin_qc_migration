<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


/**
 * @mixin IdeHelperNotification
 */
class Notification extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'notification_classify',
        'message',
        'date_start',
        'date_end',

        'display_order',
//        'statistic_classification',
        'use_classification',

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
