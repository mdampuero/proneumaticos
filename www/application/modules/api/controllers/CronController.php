<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Sinister.php';

class Api_CronController extends Zend_Rest_Controller {

    public $response;

    public function init() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $this->_helper->AjaxContext()->initContext('json');
        $this->response = new StdClass();
    }

    public function countdaysAction() {
        $modelSinister = new Model_DBTable_Sinister();
        $modelSinister->updateDays();
        echo 'OK';
    }

    public function indexAction() {
        
    }

    public function getAction() {
        
    }

    public function postAction() {
        
    }

    public function putAction() {
        
    }

    public function deleteAction() {
        
    }

}
