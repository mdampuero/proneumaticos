<?php

require_once 'Sinister.php';

class Admin_IndexController extends Zend_Controller_Action {

    public function init() {
        $this->view->loginInfo = $this->_helper->Login->isLogin();
        $this->view->parameters = $this->_request->getParams();
        $this->view->setting = $this->_helper->Setting->getSetting();
        $this->view->messages = $this->_helper->flashMessenger->getMessages();
        $this->sinister=new Model_DBTable_Sinister();
        Zend_Layout::startMvc(array('layoutPath' => '../application/modules/' . $this->view->parameters['module'] . '/layouts/scripts/'));
    }

    public function indexAction() {
        try {
            $this->view->icon = 'dashboard';
            $this->view->title = 'Siniestros';
            $this->view->description = 'Siniestros recientes';
            $this->view->result=$this->sinister->showAll(NULL,NULL,"DESC",20);
        } catch (Zend_Exception $e) {
            
        }
    }

}
