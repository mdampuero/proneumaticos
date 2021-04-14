<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Customer.php';
require_once 'Sinister.php';
require_once 'Gallery.php';
require_once 'Common.php';

class Admin_MaintenanceController extends Zend_Controller_Action {

    protected $IDRecurso;
    var $fields = array(
        array('field' => 'borrar_registros_customer', 'label' => 'Borrar todos los registros de Clientes'),
        array('field' => 'borrar_registros_sinisterr', 'label' => 'Borrar todos los registros de Siniestros'),
    );

    public function init() {
        try {
            //SEND DATE VIEW
            $this->view->fields = $this->fields;
            $this->view->actions = $this->actions;
            $this->view->options = $this->options;
            $this->view->loginInfo = $this->_helper->Login->isLogin();
            $this->view->setting = $this->_helper->Setting->getSetting();
            $this->view->parameters = $this->_request->getParams();
            $this->view->messages = $this->_helper->flashMessenger->getMessages();

            //LAYOUT
            $this->response = $this->getResponse();
            if ($this->getRequest()->isXmlHttpRequest()) {
                $this->_helper->getHelper('layout')->disableLayout();
            } elseif ($this->view->parameters["popup"] == true) {
                Zend_Layout::startMvc(array('layout' => 'iframe', 'layoutPath' => '../application/modules/' . $this->view->parameters['module'] . '/layouts/scripts/'));
            } else {
                Zend_Layout::startMvc(array('layoutPath' => '../application/modules/' . $this->view->parameters['module'] . '/layouts/scripts/'));
            }

            //CONTROLLER CONFIG
            $this->view->currentTitle = 'Configuración';

            //MODELS      
            $this->customer = new Model_DBTable_Customer();
            $this->sinister = new Model_DBTable_Sinister();
        } catch (Zend_Exception $exc) {
            throw new Exception($exc->getMessage());
        }
    }

    public function indexAction() {
        try {
            $this->view->icon = 'flash';
            $this->view->title = 'Mantenimiento';
            if ($this->getRequest()->isPost()):
                $this->data = $this->_helper->Form->isValid();
                $message = "";
                if ($_POST["borrar_registros_customer"] == true):
                    $message.="<p>Se vació la tabla '" . $this->customer->clearData() . "'</p>";
                endif;
                if ($_POST["borrar_registros_sinisterr"] == true):
                    $message.="<p>Se vació la tabla '" . $this->sinister->clearData() . "'</p>";
                endif;
                $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => $message));
                $this->_helper->Redirector->gotoSimple('index', 'maintenance', 'admin');
            endif;
            $this->view->token = $this->_helper->Form->setToken();
        } catch (Zend_Exception $exc) {
            $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage()));
            $this->_helper->Redirector->gotoSimple('index', 'maintenance', 'admin');
        }
    }

}
