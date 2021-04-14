<?php

class Zend_Controller_Action_Helper_Browser extends Zend_Controller_Action_Helper_Abstract {

    public $browserOld;

    public function get() {
        $browser = $_SERVER['HTTP_USER_AGENT'];

        $browser_array = explode(";", $browser);

        if ($browser_array[1]) {
            $pos = strpos($browser_array[1], "MSIE");
            if (!$pos === false) {
                $string_ie = explode(" ", $browser_array[1]);
                $version = end($string_ie);
                if ($version > 9) {
                    // return "_ie";
                    return null;
                } else {
//                     return null;
                    return "_ie";
                }
            } else {
                $lastValue = end($browser_array);
                $array_space = explode(" ", $lastValue);
                $nameBrowser = end($array_space);
                $array_bar = explode("/", $nameBrowser);
                if ($array_bar[0] == "Firefox" && $array_bar[1] < 20) {
                    return "_ie";
                }
                return null;
            }
        } else {
            return null;
        }
    }

}

?>
