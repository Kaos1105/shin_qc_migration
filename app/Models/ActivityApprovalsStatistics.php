<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperActivityApprovalsStatistics
 */
class ActivityApprovalsStatistics extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'circle_id',
        'year',
        'kaizen_month_1',
        'kaizen_month_2',
        'kaizen_month_3',
        'kaizen_month_4',
        'kaizen_month_5',
        'kaizen_month_6',
        'kaizen_month_7',
        'kaizen_month_8',
        'kaizen_month_9',
        'kaizen_month_10',
        'kaizen_month_11',
        'kaizen_month_12',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
