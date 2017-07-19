<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Conner\Tagging\Taggable;

class Upload extends Model
{
    //
    use Taggable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'uploads';

    /**
     * return the recent images
     *
     * @param $query
     * @return mixed
     *
     */
    static function  scopeRecent($query)
    {
        return $query->orderby('created_at','DESC');
    }
}
