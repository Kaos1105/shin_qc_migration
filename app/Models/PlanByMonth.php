<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


/**
 * @mixin IdeHelperPlanByMonth
 */
class PlanByMonth extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plan_by_year_id',
        'execution_order_no',
        'contents',
        'month_start',
        'month_end',
        'content_jan',
        'content_feb',
        'content_mar',
        'content_apr',
        'content_may',
        'content_jun',
        'content_jul',
        'content_aug',
        'content_sep',
        'content_oct',
        'content_nov',
        'content_dec',
        'in_charge',
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
