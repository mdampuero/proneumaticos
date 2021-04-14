<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */
require_once 'News.php';

class NewsController extends Zend_Controller_Action {

    public function init() {

        $this->view->setting = $this->_helper->Setting->getSetting();
        $this->view->parameters = $this->_request->getParams();
        $this->view->login = $this->_helper->Login->isLoginPublic(true);
        $this->view->messages = $this->_helper->flashMessenger->getMessages();
        if ($this->view->messages[0]["data"])
            $this->view->result = $this->view->messages[0]["data"];
        $this->session_excel = new Zend_Session_Namespace('excel');
        if ($this->getRequest()->isXmlHttpRequest()) {
            $this->_helper->getHelper('layout')->disableLayout();
        } elseif ($this->view->parameters["popup"] == true) {
            Zend_Layout::startMvc(array('layout' => 'iframe', 'layoutPath' => '../application/modules/' . $this->view->parameters['module'] . '/layouts/scripts'));
        } else {
            Zend_Layout::startMvc(array('layout' => 'default', 'layoutPath' => '../application/modules/' . $this->view->parameters['module'] . '/layouts/scripts/'));
        }
        $this->news = new Model_DBTable_News();
    }

    public function indexAction() {
        try {
            
        } catch (Zend_Exception $exc) {
            $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage(), 'data' => $this->getRequest()->getPost()));
            $this->_helper->Redirector->gotoSimple('index');
        }
    }

    public function viewAction() {
        try {
            if ($this->view->parameters["id"]):
                $this->view->new = $this->news->get($this->view->parameters["id"]);
            endif;
        } catch (Exception $exc) {
            exit($exc->getMessage());
            $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage(), 'data' => $this->getRequest()->getPost()));
            $this->_helper->Redirector->gotoSimple('index');
        }
    }
}
