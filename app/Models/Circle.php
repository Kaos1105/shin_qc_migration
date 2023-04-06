<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


/**
 * @mixin IdeHelperCircle
 */
class Circle extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'circle_name', 'circle_code', 'date_register', 'place_id', 'display_order', 'statistic_classification', 'use_classification', 'note', 'created_by', 'updated_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
