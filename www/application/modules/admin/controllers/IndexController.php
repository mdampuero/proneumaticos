<?php

require_once 'Sinister.php';
require_once 'Company.php';
require_once 'State.php';
require_once 'Branch.php';
class Admin_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->loginInfo = $this->_helper->Login->isLogin();
        $this->view->parameters = $this->_request->getParams();
        $this->view->setting = $this->_helper->Setting->getSetting();
        $this->view->messages = $this->_helper->flashMessenger->getMessages();
        Zend_Layout::startMvc(array('layoutPath' => '../application/modules/' . $this->view->parameters['module'] . '/layouts/scripts/'));
    }

    public function indexAction()
    {
        try {
            $this->sinister = new Model_DBTable_Sinister();
            $this->company = new Model_DBTable_Company();
            $this->state = new Model_DBTable_State();
            $this->branch = new Model_DBTable_Branch();
            $this->view->companies = $this->company->listAll();
            $this->view->brands = $this->branch->listAll();
            $this->view->status = $this->sinister->getStatus();
            $this->view->states = $this->state->listAll();
            $this->view->icon = 'dashboard';
            $this->view->title = 'Dashboard';
        } catch (Zend_Exception $e) {

        }
    }

}