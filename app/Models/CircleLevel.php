<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class CircleLevel extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'promotion_circle_id',
        'member_id',
        'axis_x_i',
        'axis_x_ro',
        'axis_x_ha',
        'axis_x_ni',
        'axis_x_ho',
        'axis_y_i',
        'axis_y_ro',
        'axis_y_ha',
        'axis_y_ni',
        'axis_y_ho',
        'display_order',
        'statistic_classification',
        'use_classification',
        'note', 'created_by',
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
