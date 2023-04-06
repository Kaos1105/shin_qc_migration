<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


/**
 * @mixin IdeHelperCategory
 */
class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_name',
        'is_display',

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
