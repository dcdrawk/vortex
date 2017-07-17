<?php
namespace App\Library;
use Carbon\Carbon;

/**
 * Class UploadStoringPath
 *
 * builds an object with paths to the latest upload directory
 * 1. this->store_path for programmatic purpose ( saving file to server )
 * 2. this->url for front end purpose , storing a path in the DB [ from root of the website ]
 *
 * @package App\StoringPaths
 *
 */
class UploadStoringPath
{

    public $url;
    public $store_path;

    function __construct() {

        $public_path = public_path();
        $store_directory = 'uploads';

        $store_path = "$public_path/$store_directory";

        $date = Carbon::now();

        if (!file_exists("$store_path/$date->year")) {
            mkdir("$store_path/$date->year", 0777, true);
        }
        if (!file_exists("$store_path/$date->year/$date->month/")) {
            mkdir("$store_path/$date->year/$date->month", 0777, true);
        }

        $this->store_path = "$public_path/$store_directory/$date->year/$date->month/";
        $this->url = "/$store_directory/$date->year/$date->month/";

    }

}

