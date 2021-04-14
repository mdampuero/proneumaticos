<?php

require_once 'Setting.php';

class Zend_Controller_Action_Helper_Setting extends Zend_Controller_Action_Helper_Abstract {

    private $config;

    public function __construct() {
        $this->pluginLoader = new Zend_Loader_PluginLoader();
        $this->setting = new Model_DBTable_Setting();
    }

    public function getSetting() {

        return $this->setting->get(1);
    }

    public function checkBaseUrl() {
        $this->baseUrlNew = HOST . $this->getActionController()->view->baseUrl();
        $this->baseUrlOld = $this->getSetting();

        if ($this->baseUrlNew != $this->baseUrlOld["SBaseUrl"]) {
            $this->setting->edit(array('SBaseUrl' => $this->baseUrlNew));
            //News
            $this->news = new Model_DBTable_Newsletternews();
            $this->news->updateUrl($this->baseUrlOld["SBaseUrl"], $this->baseUrlNew);
            //Newsletter
            $this->newsletter = new Model_DBTable_Newsletters();
            $this->newsletter->updateUrl($this->baseUrlOld["SBaseUrl"], $this->baseUrlNew);
        }
    }

}

?>
