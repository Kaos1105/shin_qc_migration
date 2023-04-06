<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


/**
 * @mixin IdeHelperQaAnswer
 */
class QaAnswer extends Model
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
        'alignment',
        'height',
        'length',
        'qa_linked',
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
