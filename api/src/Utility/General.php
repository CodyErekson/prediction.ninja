<?php
//general functions and such
//call a function like so:  StringUtil::limitString($string, 300);
//to do so within the class:  self::limitString($string, 300);

namespace RoboPaul\Utility;

class General {

    public static function cls() {
        //clear the cli screen
        array_map(create_function('$a', 'print chr($a);'), array(27, 91, 72, 27, 91, 50, 74));
    }

    //determine what suffix to append to the end of a number for display purposes
    public static function ordinal_suffix($number){
        $ones = $number % 10;
        $tens = (int)floor( $number / 10 ) % 10;
        if ( $tens == 1 ) {
            $suff = "th";
        } else {
            switch ($ones){
                case 1:
                    $suff = "st";
                    break;
                case 2:
                    $suff = "nd";
                    break;
                case 3:
                    $suff = "rd";
                    break;
                default:
                    $suff = "th";
            }
        }
        return $number . $suff;
    }

    public static function rand_string( $length ) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $size = strlen( $chars );
        $str = "";
        for( $i = 0; $i < $length; $i++ ) {
            $str .= $chars[ rand( 0, $size - 1 ) ];
        }
        return $str;
    }

}
