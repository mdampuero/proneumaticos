<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */
require_once 'Company.php';
require_once 'Branch.php';
require_once 'Tbranch.php';
require_once 'Cbranch.php';
require_once 'Bbranch.php';
require_once 'Model.php';
require_once 'Sinister.php';
require_once 'Sinisteraccesory.php';
require_once 'Sinisteractivity.php';
require_once 'Accesory.php';
require_once 'Common.php';
require_once 'Gallery.php';
require_once 'News.php';

class SinisterController extends Zend_Controller_Action {

    public function init() {

        $this->view->setting = $this->_helper->Setting->getSetting();
        $this->view->parameters = $this->_request->getParams();
        $this->branch = new Model_DBTable_Branch();
        //SEND DATE VIEW
        $this->fields = array(
            array('field' => 'si_id', 'label' => 'ID', 'list' => true, 'class' => 'id', 'order' => true),
            array('field' => 'si_number', 'label' => 'Nº de Siniestro', 'list' => true, 'search' => true, 'order' => true),
            array('field' => 'br_name', 'label' => 'Marca', 'search' => true, 'order' => true, 'list' => true),
            array('field' => 'mo_name', 'label' => 'Modelo', 'search' => true, 'order' => true, 'list' => true),
            array('field' => 'si_domain', 'label' => 'Dominio', 'search' => true, 'order' => true, 'list' => true),
            array('field' => 'si_fullname', 'label' => 'Nombre Asegurado', 'search' => true, 'order' => true, 'list' => true, 'class' => 'hidden-xs'),
//            array('field' => 'si_email', 'label' => 'E-Mail', 'search' => true, 'order' => true, 'list' => true, 'class' => 'hidden-xs'),
//            array('field' => 'si_phone', 'label' => 'Teléfono', 'search' => true, 'order' => true, 'list' => true, 'class' => 'hidden-xs'),
            
            array('field' => 'status_label', 'label' => 'Estado del siniestro', 'notdisplay' => true, 'notsave' => true, 'list' => true, 'order' => true),
            array('field' => 'si_data_complete', 'label' => 'Datos completos', 'notdisplay' => true, 'notsave' => true, 'list' => true, 'order' => true),
        );
        $this->view->fields = $this->fields;
        $this->actions = array(
            array('type' => 'link', 'label' => 'Agregar ' . $this->singular, 'icon' => 'plus', 'controller' => $this->view->parameters["controller"], 'action' => 'add'),
            array('type' => 'collapse', 'label' => 'Filtrar', 'icon' => 'filter', 'function' => 'openFilter()'),
            array('type' => 'link', 'label' => 'Listar ' . $this->plural, 'icon' => 'list', 'controller' => $this->view->parameters["controller"], 'action' => 'index'),
            array('type' => 'link', 'label' => 'Exportar a Excel ' . $this->plural, 'icon' => 'floppy-saved', 'controller' => $this->view->parameters["controller"], 'action' => 'excel'),
        );
        $this->view->actions = $this->actions;
        $this->view->options = $this->options;
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
        $this->company = new Model_DBTable_Company();
        $this->branch = new Model_DBTable_Branch();
        $this->tbranch = new Model_DBTable_Tbranch();
        $this->cbranch = new Model_DBTable_Cbranch();
        $this->model = new Model_DBTable_Model();
        $this->sinister = new Model_DBTable_Sinister();
        $this->sinister_accesory = new Model_DBTable_Sinisteraccesory();
        $this->accesory = new Model_DBTable_Accesory();
        $this->bbranch = new Model_DBTable_Bbranch();
        $this->sinister_activity = new Model_DBTable_Sinisteractivity();
        $this->gallery = new Model_DBTable_Gallery();
        $this->news = new Model_DBTable_News();
        $this->session_sinister = new Zend_Session_Namespace('session_sinister');
    }

    public function indexAction() {
        try {
            $status = array(
                array('id' => 1, 'name' => 'Faltan Definir Repuestos'),
                array('id' => 2, 'name' => 'En espera de repuestos'),
                array('id' => 3, 'name' => 'Ingresado sin entregar'),
                array('id' => 4, 'name' => 'Entregado'),
                array('id' => 5, 'name' => 'Facturado'),
            );
            $this->view->filter = array(
                'si_status' => array('label' => 'Estado', 'data' => $status, 'id' => 'id', 'value' => 'name')
            );
            //SAVE MASIVE
            if ($this->getRequest()->isPost()):
                if ($_POST["save"] == 1):
                    if (count($this->fields)):
                        foreach ($this->fields as $field):
                            if ($field['list'] == true && $field['list-edit'] == true) :
                                if (count($_POST[$field["field"]])):
                                    foreach ($_POST[$field["field"]] as $ID => $VALUE):
                                        $_POST[$field["field"]] = $VALUE;
                                        $this->_helper->Form->isValid(array(1 => $field));
                                        $this->data[$ID][$field["field"]] = $VALUE;
                                    endforeach;
                                endif;
                            endif;
                        endforeach;
                    endif;
                    if (count($this->data)):
                        foreach ($this->data as $ID => $data):
                            $this->model->edit($data, $ID);
                        endforeach;
                    endif;
                    $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => MESSAGE_EDI));
                    $this->_helper->Redirector->gotoSimple('index', null, null);
                endif;
            endif;
            $this->view->token = $this->_helper->Form->setToken();
            //END SAVE MASIVE
            if ($this->view->parameters["filter"] == 1)
                $where.=" AND ";
            if ($this->view->parameters["filter"] == 1):
                foreach ($this->view->filter as $key => $f):
                    if ($this->view->parameters[$key] != ""):
                        $where.=" " . $key . "='" . $this->view->parameters[$key] . "' AND";
                    endif;
                endforeach;
                $where = substr($where, 0, -3);
            endif;
            $this->view->token = $this->_helper->Form->setToken();
            //END SAVE MASIVE
            if (!empty($this->view->parameters['search'])) {
                $where.= " AND ";
                if ($this->view->parameters["filter"] == 1)
                    $where.=" AND ";
                $where.= create_where($this->view->parameters['search'], $this->fields);
            }

            if (strlen($where) < 3):
                $where = null;
            endif;
            $results_all = $this->sinister->showAll("si_co_id=" . $this->view->login["co_id"] . $where, $this->view->parameters['sort'], $this->view->parameters['order']);
            $this->session_excel->results_all = $results_all;
            $paginator = Zend_Paginator::factory($results_all);
            $paginator->setItemCountPerPage($this->view->setting["se_count_page"])
                    ->setCurrentPageNumber($this->_getParam('page', 1))
                    ->setPageRange($this->view->setting["se_page_range"]);
            $this->view->news = $this->news->showAll();
            $this->view->results = $paginator;
            $this->view->enableSearch = true;
            $this->renderScript('_list.phtml');
        } catch (Zend_Exception $exc) {
            $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage(), 'data' => $this->getRequest()->getPost()));
            $this->_helper->Redirector->gotoSimple('index');
        }
    }

    public function viewAction() {
        try {
            if ($this->view->parameters["id"]):
                $sinister = $this->sinister->get($this->view->parameters["id"]);
                $sinister["si_date"] = $this->_helper->Date->getDateFormatted($sinister["si_date"]);
                $sinister["si_pictures"] = $this->gallery->showAll("ga_si_id=" . $this->view->parameters["id"]);
                $this->view->sinister = $sinister;
                $this->view->sinister_accesory = $this->sinister_accesory->getBySinister($this->view->parameters["id"]);
                $this->view->sinister_activities = $this->sinister_activity->getBySinister($this->view->parameters["id"]);
            endif;
        } catch (Exception $exc) {
            exit($exc->getMessage());
            $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage(), 'data' => $this->getRequest()->getPost()));
            $this->_helper->Redirector->gotoSimple('index');
        }
    }

    public function accountAction() {
        try {
            if ($this->getRequest()->isPost()):
                if (empty($_POST["co_user"]))
                    throw new Zend_Controller_Action_Exception('Ingrese un usuario válido.');
                if ($this->company->isExist($_POST["co_user"], $this->view->login["co_id"]))
                    throw new Zend_Controller_Action_Exception('El usuario ' . $_POST["co_user"] . ' ya existe para otra compañia, por favor elija otro.');
                if (empty($_POST["co_pass1"]) && empty($_POST["co_pass2"])):
                    $this->data = array(
                        'co_phone' => $_POST["co_phone"],
                        'co_email' => $_POST["co_email"],
                        'co_user' => $_POST["co_user"],
                    );
                elseif ($_POST["co_pass1"] != $_POST["co_pass2"]):
                    throw new Zend_Controller_Action_Exception('Las contraseñas no coinciden.');
                else:
                    $this->data = array(
                        'co_phone' => $_POST["co_phone"],
                        'co_email' => $_POST["co_email"],
                        'co_user' => $_POST["co_user"],
                        'co_password' => $_POST["co_pass1"],
                    );
                endif;
                $this->company->edit($this->data, $this->view->login["co_id"]);
                $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => 'Sus datos fueron cambiados correctamente', 'data' => $this->getRequest()->getPost()));
                $this->_helper->Redirector->gotoSimple('account');
            endif;
        } catch (Exception $exc) {
            $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage(), 'data' => $this->getRequest()->getPost()));
            $this->_helper->Redirector->gotoSimple('account');
        }
    }
}