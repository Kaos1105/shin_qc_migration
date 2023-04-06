<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class PromotionCircle extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'circle_id',
        'year',
        'motto_of_the_workplace',
        'motto_of_circle',
        'axis_x',
        'axis_y',
        'target_number_of_meeting',
        'target_hour_of_meeting',
        'target_case_complete',
        'improved_cases',
        'objectives_of_organizing_classe',
        'review_this_year',
        'comment_promoter',
        'display_order',
        'statistic_classification',
        'use_classification',
        'note',
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
