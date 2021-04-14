<?php

class Zend_Controller_Action_Helper_Date extends Zend_Controller_Action_Helper_Abstract {

    /**
     * @var Zend_Loader_PluginLoader
     */
    public $pluginLoader;

    /**
     * Constructor: initialize plugin loader * 
     * @return void
     */
    public function __construct() {
        $this->pluginLoader = new Zend_Loader_PluginLoader();
    }

    public function getDateFormatted($date) {

        $dateExploded = explode('/', $date);
        if (count($dateExploded) == 1) {
            return $date;
        }
        return ($dateExploded[2] . '-' . $dateExploded[1] . '-' . $dateExploded[0]);
    }

    public function nameMonth($mes) {
        if ($mes == "01") {
            $return= "Enero";
        } else if ($mes == "02") {
            $return= "Febrero";
        } else if ($mes == "03") {
            $return= "Marzo";
        } else if ($mes == "04") {
            $return= "Abril";
        } else if ($mes == "05") {
            $return= "Mayo";
        } else if ($mes == "06") {
            $return= "Junio";
        } else if ($mes == "07") {
            $return= "Julio";
        } else if ($mes == "08") {
            $return= "Agosto";
        } else if ($mes == "09") {
            $return= "Septiembre";
        } else if ($mes == "10") {
            $return= "Octubre";
        } else if ($mes == "11") {
            $return= "Noviembre";
        } else if ($mes == "12") {
            $return= "Diciembre";
        }
        return $return;
    }

    public function getAge($fecha) {
        $fecha = $this->getDateFormatted($fecha);
        $dias = explode("-", $fecha, 3);
        $dias = mktime(0, 0, 0, $dias[1], $dias[0], $dias[2]);
        $edad = (int) ((time() - $dias) / 31556926 );
        return $edad;
    }

}

?>