<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class Homepage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'classification',
        'url',
        'date_start',
        'date_end',

        'display_order',
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
