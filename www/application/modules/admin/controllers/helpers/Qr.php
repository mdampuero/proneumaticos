<?php

require_once 'phpqrcode/qrlib.php';

class Zend_Controller_Action_Helper_Qr extends Zend_Controller_Action_Helper_Abstract {

    /**
     * @var Zend_Loader_PluginLoader
     */
    public $pluginLoader;
    public $level = "L";
    public $matrixPointSize = 10;

    /**
     * Constructor: initialize plugin loader * 
     * @return void
     */
    public function __construct() {
        $this->pluginLoader = new Zend_Loader_PluginLoader();
    }

    public function generate($text) {
        try {
            $filename = md5($text . '|' . $this->level . '|' . $this->matrixPointSize) . '.png';
            QRcode::png($text, PATH_IMG . $filename, $this->level, $this->matrixPointSize, 2);
            return $filename;
        } catch (Zend_Exception $exc) {
           throw new Zend_Controller_Action_Exception($exc->getMessage(), 599); 
        }
    }

}

?>