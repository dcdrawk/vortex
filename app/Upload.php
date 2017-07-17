<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    //


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
