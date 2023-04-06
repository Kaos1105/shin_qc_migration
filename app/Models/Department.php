<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class Department extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'department_name',
        'bs_id',
        'sw_id',
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
