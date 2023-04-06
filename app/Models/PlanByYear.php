<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


/**
 * @mixin IdeHelperPlanByYear
 */
class PlanByYear extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'year',
        'vision',
        'target',
        'motto',
        'prioritize_1',
        'prioritize_2',
        'prioritize_3',
        'prioritize_4',
        'prioritize_5',
        'prioritize_6',
        'prioritize_7',
        'prioritize_8',
        'prioritize_9',
        'prioritize_10',
        'meeting_times',
        'meeting_hour',
        'case_number_complete',
        'case_number_improve',
        'classes_organizing_objective',
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
