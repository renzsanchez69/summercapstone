<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UtilitiesController extends Controller
{
    public static function monetize($addComma, $number)
    {
        //        $number = bcdiv($number, 1, 2);
        return $addComma ? number_format($number, strlen(substr(strrchr($number, "."), 1))) : $number;
    }
}
