<?php

use App\Models\Progress;

class Helper
{
    public static function progress()
    {
        $progress = Progress::all();
        //$progress = 1;

        return $progress;
    }
}
