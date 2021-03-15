<?php
//functions used to generate page display elementd
//call a function like so:  StringUtil::limitString($string, 300);
//to do so within the class:  self::limitString($string, 300);

namespace RoboPaul\Utility;

class Display {

    public static function displayThumbnail($picture, $caption=false){
        //create a thumbnail display
        $main = Initialize::obtain();
        $DB = DB::pass();
        $ppath = $main->config->get('directories', 'pictures');

        $ret = "<div class=\"col-lg-3 col-md-6 hero-feature\">
                <div class=\"thumbnail\">
                    <img class=\"frnt-thmb\" src=\"" . $ppath . "/" . $picture['generated'] . "\" title=\"" . $picture['filename'] . "\" data-toggle=\"lightbox\" data-remote=\"" . $ppath . "/" . $picture['generated'] . "\" />";
        if ( $caption ){
            $ret .= "   <div class=\"caption\">
                        <h3>" . $caption . "</h3>";
        }
        $ret .= "   </div>
                </div>
            </div>";
        return $ret;
    }

}
