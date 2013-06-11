<?php
/**
 * Utility debug tools
 * @author eka808
**/
class PurpleDebug
{
    /**
     * Formatted print_r
     * @param data : data we want to show
    **/
    static function print_r($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}
?>