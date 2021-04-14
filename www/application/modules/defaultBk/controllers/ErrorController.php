<?php

class ErrorController extends Zend_Controller_Action {

    public function init() {
        Zend_Layout::startMvc(array('layout' => 'error', 'layoutPath' => '../application/modules/default/layouts/scripts/'));
        $this->_redirector = $this->_helper->getHelper('Redirector');
    }

    public function errorAction() {
        $errors = $this->_getParam('error_handler');

        if (!$errors || !$errors instanceof ArrayObject) {
            $this->view->message = 'You have reached the error page';
            return;
        }

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $priority = Zend_Log::NOTICE;
//                $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => 'No se encontro el controlador (404)'));
                //   $this->_redirector->gotoSimple('index', 'index', 'admin', array('status' => 'danger'));
                break;
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $priority = Zend_Log::NOTICE;
//                $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => 'No se encontro la accion (404)'));
                // $this->_redirector->gotoSimple('index', 'index', 'admin', array('status' => 'danger'));
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $priority = Zend_Log::CRIT;
//                $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => 'Ocurrio un error en la aplicacion (500)'));
                // $this->_redirector->gotoSimple('index', 'index', 'admin', array('status' => 'danger'));
                break;
        }

        // Log exception, if logger available
        if ($log = $this->getLog()) {
            $log->log($this->view->message, $priority, $errors->exception);
            $log->log('Request Parameters', $priority, $errors->request->getParams());
        }

        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception;
        }

        $this->view->request = $errors->request;
//        EXIT(APPLICATION_PATH.DS."modules".DS.$this->view->parameters["module"].DS."views".DS."scripts".DS."error/error");
        $this->renderScript('error/error.phtml');
    }

    public function getLog() {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }

}

?>
