<?php

//require_once 'Eidicomtypedocument.php';
require_once 'Company.php';

class LoginController extends Zend_Controller_Action {

    public function init() {
        try {
            $this->view->parameters = $this->_request->getParams();
            $this->view->messages = $this->_helper->flashMessenger->getMessages();
            $this->view->setting = $this->_helper->Setting->getSetting();
            if ($this->getRequest()->isXmlHttpRequest()) :
                $this->_helper->getHelper('layout')->disableLayout();
                $this->view->parameters["popup"] = true;
            elseif ($this->view->parameters["popup"] == true):
                Zend_Layout::startMvc(array('layout' => 'iframe', 'layoutPath' => '../application/modules/' . $this->view->parameters['module'] . '/layouts/scripts/'));
            else:
                Zend_Layout::startMvc(array('layoutPath' => '../application/modules/' . $this->view->parameters['module'] . '/layouts/scripts/'));
            endif;
            $this->model = new Model_DBTable_Company();
        } catch (Exception $exc) {
            echo '<pre>';
            print_r($exc->getMessage());
            echo '</pre>';
            exit();
        }
    }

    public function indexAction() {
        try {
            if ($this->getRequest()->isPost()) {
                if (empty($_POST["user"]))
                    throw new Zend_Controller_Action_Exception('Ingrese su Usuario.');
                if (empty($_POST["password"]))
                    throw new Zend_Controller_Action_Exception('Ingrese su Clave.');
                if($data=$this->model->logIn($_POST)):
                    $this->_helper->Login->loginInPublic($data);
                    $return = array('response' => true, 'message' => "Redireccionando...", 'goback' => $goback);   
                else:
                    throw new Zend_Controller_Action_Exception("Usuario o clave incorrectos.");
                endif;
                echo json_encode($return);
                exit();
            }
            $this->view->sellers= $this->model->listAll();
            $this->view->response = $response;
            $this->view->token = $this->_helper->Form->setToken();
        } catch (Zend_Exception $exc) {
            echo json_encode(array('response' => false, 'message' => $exc->getMessage(), 'goback' => null));
            exit();
        }
    }

    public function forgotpasswordAction() {
        try {
            $this->eidicom_user = new Model_DBTable_Eidicomusers();
            if ($this->getRequest()->isPost()) {
                if (empty($_POST["email"]))
                    throw new Zend_Controller_Action_Exception('Ingrese su E-Mail.');
                if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
                    throw new Zend_Controller_Action_Exception('Su E-Mail no es válido.');
                if (!$userData = $this->eidicom_user->showAll("email='" . $_POST["email"] . "'"))
                    throw new Zend_Controller_Action_Exception('Su E-Mail no pertenece a ninguna cuenta de EIDICOM.');

                $newpassword = substr(uniqid(md5(time())), 0, 6);
                $password = md5(MD5_PREFIX . $newpassword);
                $this->eidicom_user->edit(array('password' => $password), $userData[0]["id"]);
                /*
                 * EMAIL
                 */
                $this->_mail = $this->_helper->Mail;
                $body = '<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                            <td height="47" colspan="2" valign="bottom"><div align="center"><span style="font-family: Verdana, Geneva, sans-serif; font-size: 19px; color: #000; text-transform:uppercase"><strong>Hola ' . $userData[0]["name"] . ' ' . $userData[0]["last"] . '</strong></span></div></td>
                        </tr>
                        <tr>
                            <td height="37" colspan="2"><div align="center"><span style="font-family: Verdana, Geneva, sans-serif; font-size: 16px; color: #000;"> A continuación te mostramos los nuevos datos de acceso en Eidico Market.</span></div></td>
                        </tr>
                        <tr>
                            <td height="42" colspan="2" style="font-family: Verdana, Geneva, sans-serif; font-size: 14px;">
                             <p>&nbsp;</p>
                                            <p><b>Tu nueva contraseña:</b> ' . $newpassword . '</p>
                                            <p>Usala para ingresar en EIDICOM y recuerda cambiarla a una que puedas recordar.</p>
                                            <p>&nbsp;</p>
                                            <p>¡Muchas Gracias!</p>
                                </td>
                        </tr>
                    </table>';
                $template_newsletter = file_get_contents(PUBLIC_PATH . DS . "html" . DS . "template.html");
                $html = str_replace("##urlEmail##", HOST . $this->view->baseUrl() . "/", $template_newsletter);
                $html = str_replace("##titleEmail##", $this->view->setting["se_title"], $html);
                $html = str_replace("##subtitleEmail##", "", $html);
                $html = str_replace("##contentEmail##", $body, $html);
                $this->_mail->sendEmail($html, $this->view->setting["se_title"] . " - Recuperación de clave", $_POST["email"], $userData[0]["name"] . ' ' . $userData[0]["last"], "contacto@eidicomarket.com");
                /*
                 * END EMAIL
                 */
                $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => $userData[0]["name"] . ", te enviamos tu nueva contraseña a la dirección de E-Mail '" . $_POST["email"] . "'."));
                $return = array('response' => true, 'message' => "Redireccionando...", 'goback' => $goback);
                echo json_encode($return);
                exit();
            }
            $this->view->response = $response;
            $this->view->token = $this->_helper->Form->setToken();
        } catch (Zend_Exception $exc) {
            echo json_encode(array('response' => false, 'message' => $exc->getMessage(), 'goback' => null));
            exit();
        }
    }

    public function accessAction() {
        try {
            $this->session_webid = new Zend_Session_Namespace('webid');
            if ($this->getRequest()->isPost()) {
                if (empty($_POST["code"]))
                    throw new Zend_Controller_Action_Exception('Ingrese el código de Acceso.');
                if ($_POST["code"] != $this->view->setting["se_code_block"])
                    throw new Zend_Controller_Action_Exception('El código de Acceso es incorrecto.');
                $this->session_webid->data["WEBID_CODE_AUTH"] = $_POST["code"];
                $goback = $this->view->url(array('controller' => 'nh', 'action' => 'reserve', 'id' => $this->view->parameters["lo_id"]), null, true);
                $return = array('response' => true, 'message' => "Redireccionando...", 'goback' => $goback);
                echo json_encode($return);
                exit();
            }
            $this->view->response = $response;
            $this->view->token = $this->_helper->Form->setToken();
        } catch (Zend_Exception $exc) {
            echo json_encode(array('response' => false, 'message' => $exc->getMessage(), 'goback' => null));
            exit();
        }
    }

    public function logoutAction() {
        try {
            $this->session_webid = new Zend_Session_Namespace('webid');
            $this->session_webid->data = null;
            session_destroy();
            $this->_helper->Login->logOutPublic();
            $this->_redirect('/index');
        } catch (Zend_Exception $exc) {
            
        }
    }

}

?>
