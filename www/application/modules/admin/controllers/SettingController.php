<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Setting.php';
require_once 'Common.php';

class Admin_SettingController extends Zend_Controller_Action {

    var $fields = array();
    var $actions = array();
    var $options = array();
    var $singular = "Configuración General";
    var $plural = "todos los parámetros de configuración";
    var $title = "Configuración / General";

    public function init() {
        try {
            //SEND DATE VIEW

            $this->view->loginInfo = $this->_helper->Login->isLogin();
            $this->view->setting = $this->_helper->Setting->getSetting();
            $this->view->parameters = $this->_request->getParams();
            $this->fields = array(
                array('field' => 'se_id', 'label' => 'ID', 'list' => true, 'class' => 'id', 'order' => true, 'search' => true),
                array('field' => 'se_title', 'label' => 'Nombre del Sitio', 'required' => 'required', 'list' => true, 'search' => true, 'order' => true),
                array('field' => 'se_footer', 'label' => 'Pie de Página', 'required' => 'required', 'list' => true, 'search' => true, 'order' => true),
//                array('field' => 'se_system_email', 'label' => 'E-Mail de sistema', 'required' => 'required'),
                array('field' => 'se_count_page', 'label' => 'Máximo registro por página', 'required' => 'required'),
                array('field' => 'se_page_range', 'label' => 'Máximo rango por página', 'required' => 'required'),
//                array('field' => 'se_url_sps', 'label' => 'URL SPS', 'required' => 'required'),
//                array('field' => 'se_url_sps_response', 'label' => 'URL SPS Respuesta', 'required' => 'required'),
//                array('field' => 'se_email_host', 'label' => 'SMTP Servidor', 'required' => 'required'),
//                array('field' => 'se_email_port', 'label' => 'SMTP Puerto', 'required' => 'required'),
//                array('field' => 'se_email_user', 'label' => 'SMTP Usuario', 'required' => 'required'),
//                array('field' => 'se_email_password', 'label' => 'SMTP Clave', 'required' => 'required'),
//                array('field' => 'se_email_email', 'label' => 'SMTP E-Mail', 'required' => 'required'),
//                array('field' => 'se_email_name', 'label' => 'SMTP Nombre', 'required' => 'required'),
//                array('field' => 'se_email_secure', 'label' => 'SMTP Seguridad'),
//                array('field' => 'sendTest', 'label' => 'Enviar E-Mail de prueba', 'notsave' => true, 'type' => 'checkbox'),
//                array('field' => 'se_img_logo_header', 'label' => 'Imagen logo Encabezado', 'type' => 'image', 'x' => 200, 'y' => 50),
//                array('field' => 'se_img_bg', 'label' => 'Imagen de Fondo', 'type' => 'image', 'x' => 1920, 'y' => 1200),
            );
            $this->view->fields = $this->fields;
            $this->actions = array(
                array('type' => 'link', 'label' => 'Listar ' . $this->plural, 'icon' => 'list', 'controller' => $this->view->parameters["controller"], 'action' => 'index'),
            );
            $this->view->actions = $this->actions;
            $this->options = array(
                array('type' => 'link', 'title' => 'Detalle', 'icon' => 'glyphicon glyphicon-eye-open text-primary', 'controller' => $this->view->parameters["controller"], 'action' => 'detail'),
                array('type' => 'link', 'title' => 'Editar', 'icon' => 'glyphicon glyphicon-edit text-primary', 'controller' => $this->view->parameters["controller"], 'action' => 'edit'),
            );
            $this->view->options = $this->options;
            $this->view->messages = $this->_helper->flashMessenger->getMessages();
            $this->session_selected = new Zend_Session_Namespace('selected');
            $this->IDRecurso = 4;

            //LAYOUT
            $this->response = $this->getResponse();
            if ($this->getRequest()->isXmlHttpRequest()) {
                $this->_helper->getHelper('layout')->disableLayout();
            } elseif ($this->view->parameters["popup"] == true) {
                Zend_Layout::startMvc(array('layout' => 'iframe', 'layoutPath' => '../application/modules/' . $this->view->parameters['module'] . '/layouts/scripts/'));
            } else {
                Zend_Layout::startMvc(array('layoutPath' => '../application/modules/' . $this->view->parameters['module'] . '/layouts/scripts/'));
            }

            //MODELS      
            $this->setting = new Model_DBTable_Setting();
        } catch (Zend_Exception $exc) {
            throw new Exception($exc->getMessage());
        }
    }

    public function indexAction() {
        try {
            if (!empty($this->view->parameters['search'])) {
                $where = create_where($this->view->parameters['search'], $this->fields);
            }
            $results_all = $this->setting->showAll($where, $this->view->parameters['sort'], $this->view->parameters['order']);
            $paginator = Zend_Paginator::factory($results_all);
            $paginator->setItemCountPerPage(COUNTPERPAGE)
                    ->setCurrentPageNumber($this->_getParam('page', 1))
                    ->setPageRange(PAGERANGE);

            $this->view->results = $paginator;
            $this->view->enableSearch = true;
            $this->renderScript('_list.phtml');
        } catch (Zend_Exception $exc) {
            throw new Exception($exc->getMessage());
        }
    }

    public function editAction() {
        try {
            if ($this->getRequest()->isPost()) {
                $this->data = $this->_helper->Form->isValid($this->fields);
                $this->setting->edit($this->data, $this->view->parameters["id"]);
                if ($_POST["sendTest"] == 1):
                    $this->_mail = $this->_helper->Mail;
                    $body = '<table cellspacing="0" border="0" height="auto" cellpadding="0" width="600">
                                    <tr>
                                        <td class="article-title" height="45" valign="top" style="padding: 0 20px; font-family: Georgia; font-size: 20px; font-weight: bold;" width="600" colspan="2">
                                           Felicitaciones! Configuración SMTP correcta
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content-copy" valign="top" style="padding: 0 20px; color: #000; font-size: 14px; font-family: Georgia; line-height: 20px;" colspan="2">
                                            <p>Si Ud. logra visualizar este E-Mail es porque ha configurado correctamente todos los parámetros de envío SMTP.</p>
                                        </td>
                                    </tr>
                                </table>';
                    $template_newsletter = file_get_contents(PUBLIC_PATH . DS . "html" . DS . "template.html");
                    $html = str_replace("##urlEmail##", HOST . $this->view->baseUrl() . "/", $template_newsletter);
                    $html = str_replace("##titleEmail##", $this->view->setting["se_title"], $html);
                    $html = str_replace("##subtitleEmail##", "Prueba de Configuración de Correo", $html);
                    $html = str_replace("##footerEmail##", $this->view->setting["se_footer"], $html);
                    $html = str_replace("##contentEmail##", $body, $html);
                    $this->_mail->sendEmail($html, "Prueba de Configuración de Correo", $this->view->setting["se_system_email"], $this->view->setting["se_system_email"]);
                    $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => "Los cambios se guardaron correctamente.<br/>Se envió un correo de prueba a '" . $this->view->setting["se_system_email"] . "'"));
                else:
                    $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => "Los cambios se guardaron correctamente"));
                endif;
                $this->_helper->Redirector->gotoSimple('edit', null, null, array('status' => 'success', 'id' => 1));
            }
            $this->view->title = $this->title . ' / Editar';
            $this->view->token = $this->_helper->Form->setToken();
            $this->view->result = $this->setting->get($this->view->parameters["id"]);
            $this->renderScript('_form.phtml');
        } catch (Zend_Exception $exc) {
            $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage()));
            $this->_helper->Redirector->gotoSimple('edit', 'setting', 'admin', array('id' => 1));
        }
    }

    public function detailAction() {
        try {
            $this->view->result = $this->setting->get($this->view->parameters["id"]);
            $this->view->formDisabled = true;
            $this->view->title = $this->title . ' / Detalle';
            $this->renderScript('_form.phtml');
        } catch (Zend_Exception $exc) {
            throw new Exception($exc->getMessage());
        }
    }

}
