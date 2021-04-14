<?php

class Zend_Controller_Action_Helper_Login extends Zend_Controller_Action_Helper_Abstract {

    public $session;
    private $accessRestring;
    private $params;

    public function __construct() {
        $this->session = new Zend_Session_Namespace('login');
        $this->session_url = new Zend_Session_Namespace('url');
        $this->session_public = new Zend_Session_Namespace('login_public');
        $this->redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');
        $this->flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
        $this->request = Zend_Controller_Front::getInstance()->getRequest();
    }

    public function loginIn($user = null) {
        if ($user) {
            $this->session->user = $user;
            return true;
        } else {
            return false;
        }
    }

    public function getLogin() {

        return $this->session->user;
    }

    public function isLogin() {
        $this->session_url->params = $this->request->getParams();
        if (!$this->session->user) {
            $this->redirector->gotoSimpleAndExit('index', 'login', 'admin');
        }
        //ADMIN 
        $this->accessRestring[2] = array(
            'controllers' => array(
                'maintenance',
                'roles',
            ),
        );
        //OPERADORES
        $this->accessRestring[3] = array(
            'controllers' => array(
                'customer',
                'email',
                'maintenance',
                'page',
                'paymethod',
                'order',
                'roles',
                'setting',
                'slider',
                'user',
                'statusorder',
            ),
            'actions' => array(
                'sinister/delete',
            )
        );
        //SUPER USUARIO
        $this->accessRestring[1] = array(
            'elements' => array(
                'user/delete/1',
                'roles/delete/1',
                'roles/delete/2',
                'user/delete/' . $this->session->user["IDUser"],
            )
        );
        //ROL NO DEFINIDO ROL MAYOR A 2
        if ($this->session->user["ad_ar_id"] > 3):
            $this->accessRestring[$this->session->user["ad_ar_id"]] = $this->accessRestring[3];
        endif;
        $this->params = $this->request->getParams();
        if (@in_array($this->params["controller"], $this->accessRestring[$this->session->user["ad_ar_id"]]["controllers"])):
            /*
             * NO DASHBOARD
             */
//            if ($this->session->user["ad_ar_id"] == 3):
//                $this->redirector->gotoSimple('index', 'index', 'admin', array('status' => 'danger'));
//            else:
                $this->flashMessenger->addMessage(array('type' => 'danger', 'message' => "Ud. no tiene  permisos para acceder a este controlador."));
                $this->redirector->gotoSimple('index', 'index', 'admin', array('status' => 'danger'));
//            endif;
        elseif (@in_array($this->params["controller"] . "/" . $this->params["action"], $this->accessRestring[$this->session->user["ad_ar_id"]]["actions"])):
            $this->flashMessenger->addMessage(array('type' => 'danger', 'message' => "Ud. no tiene  permisos para realizar esta acción."));
            $this->redirector->gotoSimple('index', $this->params["controller"], 'admin', array('status' => 'danger'));
        elseif (@in_array($this->params["controller"] . "/" . $this->params["action"] . "/" . $this->params["id"], $this->accessRestring[$this->session->user["ad_ar_id"]]["elements"])):
            $this->flashMessenger->addMessage(array('type' => 'danger', 'message' => "Ud. no tiene  permisos para realizar esta acción."));
            $this->redirector->gotoSimple('index', $this->params["controller"], 'admin', array('status' => 'danger'));
        endif;
        $this->session->user["accessRestring"] = $this->accessRestring[$this->session->user["ad_ar_id"]];
        return $this->session->user;
    }

    public function logOut($user = null) {
        $this->session->user = null;
    }

    //PUBLIC
    public function loginInPublic($user = null) {
        if ($user) {
            $this->session_public->user = $user;
            return true;
        } else {
            return false;
        }
    }

    public function getLoginPublic() {

        return $this->session_public->user;
    }

    public function isLoginPublic($required = false) {
        if (!$this->session_public->user) {
            if ($required):
                $this->redirector->gotoSimpleAndExit('index', 'login');
            endif;
            return null;
        }
        return $this->session_public->user;
    }

    public function logOutPublic($user = null) {
        $this->session_public->user = null;
    }

}

?>