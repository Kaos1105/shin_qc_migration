<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


/**
 * @mixin IdeHelperActivityApproval
 */
class ActivityApproval extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'promotion_circle_id',
        'approver_classification',
        'user_approved',
        'date_approved',
        'user_jan',
        'date_jan',
        'user_feb',
        'date_feb',
        'user_mar',
        'date_mar',
        'user_apr',
        'date_apr',
        'user_may',
        'date_may',
        'user_jun',
        'date_jun',
        'user_jul',
        'date_jul',
        'user_aug',
        'date_aug',
        'user_sep',
        'date_sep',
        'user_oct',
        'date_oct',
        'user_nov',
        'date_nov',
        'user_dec',
        'date_dec',
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
