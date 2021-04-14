<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Tmodel.php';
require_once 'Common.php';

class Admin_TmodelController extends Zend_Controller_Action {

    var $fields = array();
    var $actions = array();
    var $options = array();
    var $singular = "Modelo";
    var $plural = "todos los Modelos";
    var $messageDelete = "¿Esta seguro que desea eliminar esta Modelo?";
    var $title = "Repuestos / LLantas / Modelos";

    public function init() {
        try {

            $this->view->loginInfo = $this->_helper->Login->isLogin();
            $this->view->setting = $this->_helper->Setting->getSetting();
            $this->view->parameters = $this->_request->getParams();
            //SEND DATE VIEW
            $this->fields = array(
                array('field' => 'tm_id', 'label' => 'ID', 'list' => true, 'class' => 'id', 'order' => true),
                array('field' => 'tm_name', 'label' => 'Nombre', 'required' => 'required', 'search' => true, 'order' => true, 'list' => true),
            );
            $this->view->fields = $this->fields;
            $this->actions = array(
                array('type' => 'link', 'label' => 'Agregar ' . $this->singular, 'icon' => 'plus', 'controller' => $this->view->parameters["controller"], 'action' => 'add'),
                array('type' => 'link', 'label' => 'Listar ' . $this->plural, 'icon' => 'list', 'controller' => $this->view->parameters["controller"], 'action' => 'index'),
                array('type' => 'link', 'label' => 'Exportar a Excel ' . $this->plural, 'icon' => 'floppy-saved', 'controller' => $this->view->parameters["controller"], 'action' => 'excel'),
            );
            $this->view->actions = $this->actions;
            $this->options = array(
                array('type' => 'link', 'title' => 'Detalle', 'icon' => 'glyphicon glyphicon-eye-open text-primary', 'controller' => $this->view->parameters["controller"], 'action' => 'detail', 'modal' => true),
                array('type' => 'link', 'title' => 'Editar', 'icon' => 'glyphicon glyphicon-edit text-primary', 'controller' => $this->view->parameters["controller"], 'action' => 'edit'),
                array('type' => 'link', 'title' => 'Eliminar', 'icon' => 'glyphicon glyphicon-ban-circle text-danger', 'controller' => $this->view->parameters["controller"], 'action' => 'delete', 'dialog' => true,
                    'dialog_message' => $this->messageDelete)
            );
            $this->view->options = $this->options;
            $this->view->messages = $this->_helper->flashMessenger->getMessages();
            if ($this->view->messages[0]["data"])
                $this->view->result = $this->view->messages[0]["data"];
            $this->session_excel = new Zend_Session_Namespace('excel');
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
            $this->view->title = $this->title;
            $this->model = new Model_DBTable_Tmodel();
        } catch (Zend_Exception $exc) {
            throw new Exception($exc->getMessage());
        }
    }

    public function indexAction() {
        try {
            //SAVE MASIVE
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
            $where = "";
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
                if ($this->view->parameters["filter"] == 1)
                    $where.=" AND ";
                $where.= create_where($this->view->parameters['search'], $this->fields);
            }
            $results_all = $this->model->showAll($where, $this->view->parameters['sort'], $this->view->parameters['order']);
            $this->session_excel->results_all = $results_all;
            $paginator = Zend_Paginator::factory($results_all);
            $paginator->setItemCountPerPage($this->view->setting["se_count_page"])
                    ->setCurrentPageNumber($this->_getParam('page', 1))
                    ->setPageRange($this->view->setting["se_page_range"]);

            $this->view->results = $paginator;
            $this->view->enableSearch = true;
            $this->renderScript('_list.phtml');
        } catch (Zend_Exception $exc) {
            $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage(), 'data' => $this->getRequest()->getPost()));
            $this->_helper->Redirector->gotoSimple('index');
        }
    }

    public function addAction() {
        try {
            if ($this->getRequest()->isPost()) {
                $this->data = $this->_helper->Form->isValid($this->fields);
                $id = $this->model->add($this->data);
                $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => MESSAGE_NEW));
                $this->_helper->Redirector->gotoSimple('add', null, null);
            }
            $this->view->title = $this->title . ' / Nuevo';
            $this->view->token = $this->_helper->Form->setToken();
            $this->renderScript('_form.phtml');
        } catch (Zend_Exception $exc) {
            $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage(), 'data' => $this->getRequest()->getPost()));
            $this->_helper->Redirector->gotoSimple('add');
        }
    }

    public function editAction() {
        try {
            if ($this->getRequest()->isPost()) {
                $this->data = $this->_helper->Form->isValid($this->fields);
                $this->model->edit($this->data, $this->view->parameters["id"]);
                $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => MESSAGE_EDI));
                $this->_helper->Redirector->gotoSimple('index', null, null);
            }
            $this->view->title = $this->title . ' / Editar';
            $this->view->token = $this->_helper->Form->setToken();
            $this->view->result = $this->model->get($this->view->parameters["id"]);
            $this->renderScript('_form.phtml');
        } catch (Zend_Exception $exc) {
            $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage()));
            $this->_helper->Redirector->gotoSimple('edit', null, null, array('id' => $this->view->parameters["id"]));
        }
    }

    public function deleteAction() {
        try {
            $this->_helper->viewRenderer->setNoRender(TRUE);
            if ($this->getRequest()->isPost()) {
                $this->model->delete_slow($this->getRequest()->getPost('id'));
            }
        } catch (Zend_Exception $exc) {
            exit($exc->getMessage());
        }
    }

    public function detailAction() {
        try {
            $this->view->result = $this->model->get($this->view->parameters["id"]);
            $this->view->formDisabled = true;
            $this->view->title = $this->title . ' / Detalle';
            $this->view->description = 'Detalle de ' . $this->title . ' "' . $this->view->result[$this->view->fields[1]["field"]] . '"';
            if ($this->getRequest()->isXmlHttpRequest()):
                $this->renderScript('_detail.phtml');
            else:
                $this->renderScript('_form.phtml');
            endif;
        } catch (Zend_Exception $exc) {
            $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage()));
            $this->_helper->Redirector->gotoSimple('index');
        }
    }

    public function excelAction() {
        try {
            set_time_limit(0);
            $this->_helper->getHelper('layout')->disableLayout();
            $this->view->results = $this->session_excel->results_all;
            $this->getResponse()->setRawHeader("Content-Type: application/vnd.ms-excel; charset=UTF-8")
                    ->setRawHeader("Content-Disposition: attachment; filename=" . strtofilename($this->title) . ".xls")
                    ->setRawHeader("Content-Transfer-Encoding: binary")
                    ->setRawHeader("Expires: 0")
                    ->setRawHeader("Cache-Control: must-revalidate, post-check=0, pre-check=0")
                    ->setRawHeader("Pragma: public")
                    ->sendResponse();
            $this->renderScript('_listExcel.phtml');
        } catch (Zend_Exception $exc) {
            throw new Exception($exc->getMessage());
        }
    }

}
