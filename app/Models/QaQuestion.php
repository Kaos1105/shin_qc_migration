<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


/**
 * @mixin IdeHelperQaQuestion
 */
class QaQuestion extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'qa_id',
        'screen_classification',
        'content',
        'file_name',
        'file_size',
        'alignment',
        'height',
        'length',
        'delta',
        'comment',

        'display_order',
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
