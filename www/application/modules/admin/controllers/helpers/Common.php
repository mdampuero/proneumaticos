<?php

class Zend_Controller_Action_Helper_Common extends Zend_Controller_Action_Helper_Abstract {

    public $status;
    public $flag;

    public function __construct() {

        $this->status = array(
            '1' => 'Ingresada',
            '10' => 'Finalizada'
        );
        $this->step = array(
            '1' => 'Paso 1 completo',
            '2' => 'Paso 2 compelto',
            '3' => 'Paso 3 completo',
            '4' => 'Paso 4 completo',
        );
        $this->types = array(
            'au' => 'LLANTA AUXILIZAR',
            'po' => 'LLANTA DE POSICIÓN',
            'co' => 'NEUMÁTICO',
            'ba' => 'BATERÍA',
        );
    }

}

?>
