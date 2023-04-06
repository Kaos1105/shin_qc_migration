<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class NaviDetails extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'history_id',
        'qa_id',
        'date_answer',
        'answer_id',

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
