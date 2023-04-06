<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class PromotionTheme extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'theme_id',
        'progression_category',
        'date_expected_start',
        'date_expected_completion',
        'date_actual_start',
        'date_actual_completion',
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
