<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */
require_once 'Company.php';
require_once 'Provider.php';
require_once 'Gallery.php';
require_once 'Sinister.php';
require_once 'Sinisteraccesory.php';
require_once 'Sinisteractivity.php';
require_once 'Branch.php';
require_once 'Model.php';
require_once 'Common.php';

class Admin_SinisterController extends Zend_Controller_Action {

    var $fields = array();
    var $actions = array();
    var $options = array();
    var $singular = "Siniestro";
    var $plural = "todos los Siniestros";
    var $messageDelete = "¿Esta seguro que desea eliminar este Siniestro?";
    var $title = "Siniestros";

    public function init() {
        try {

            $this->view->loginInfo = $this->_helper->Login->isLogin();
            $this->view->setting = $this->_helper->Setting->getSetting();
            $this->view->parameters = $this->_request->getParams();
            $this->company = new Model_DBTable_Company();
            $this->branch = new Model_DBTable_Branch();
            $this->gallery = new Model_DBTable_Gallery();
            $this->provider = new Model_DBTable_Provider();
            //SEND DATE VIEW
            $this->fields = array(
                array('field' => 'si_id', 'label' => 'ID', 'list' => true, 'class' => 'id', 'order' => true),
                array('field' => 'si_number', 'label' => 'Nº Siniestro', 'required' => 'required', 'search' => true, 'order' => true, 'list' => true,'attr'=>'maxlength="50"'),
                array('field' => 'br_name', 'label' => 'Marca', 'notdisplay' => true, 'notsave' => true, 'list' => true, 'search' => true, 'order' => true),
                array('field' => 'mo_name', 'label' => 'Modelo', 'notdisplay' => true, 'notsave' => true, 'list' => true, 'search' => true, 'order' => true),
                array('field' => 'si_date', 'label' => 'Fecha de Ingreso', 'required' => 'required', 'search' => true, 'order' => true, 'list' => true, 'type' => 'date', 'calendar' => true),
                array('field' => 'days', 'label' => 'Días', 'notdisplay' => true, 'notsave' => true, 'list' => true, 'search' => false, 'order' => true),
                array('field' => 'si_co_id', 'label' => 'Compañia', 'required' => 'required', 'type' => 'combo', 'data' => $this->company->listAll(), 'option-empy' => 'Seleccione una Compañia'),
                array('field' => 'co_name', 'label' => 'Compañia', 'notdisplay' => true, 'notsave' => true, 'list' => true, 'search' => true, 'order' => true),
                array('field' => 'si_fullname', 'label' => 'Asegurado', 'required' => '', 'search' => true, 'order' => true, 'list' => true),
                array('field' => 'si_email', 'label' => 'E-Mail', 'required' => '', 'search' => true, 'order' => true, 'list' => false),
                array('field' => 'si_phone', 'label' => 'Teléfono', 'required' => '', 'search' => true, 'order' => true, 'list' => false),
                array('field' => 'si_domain', 'label' => 'Dominio', 'required' => 'required', 'search' => true, 'order' => true, 'list' => true,'attr'=>'maxlength="8"'),
                array('field' => 'status_label', 'label' => 'Estado', 'notdisplay' => true, 'notsave' => true, 'list' => true,  'order' => true),
                array('field' => 'si_data_complete', 'label' => 'Datos completos','class' => 'text-center', 'notdisplay' => true, 'notsave' => true, 'list' => true, 'search' => true, 'order' => true),
                array('field' => 'divider', 'type' => 'divider', 'notsave' => true),
                array('field' => 'si_br_id', 'label' => 'Marca', 'required' => '', 'type' => 'combo', 'data' => $this->branch->listAll(), 'option-empy' => 'Selecciona una Marca', 'attr' => 'onchange="loadModel(this.value,0);"'),
                array('field' => 'si_mo_id', 'label' => 'Modelo', 'required' => '', 'type' => 'combo', 'data' => array(), 'option-empy' => 'Selecciona un Modelo'),
                array('field' => 'si_version', 'label' => 'Versión/Año', 'search' => true),
                array('field' => 'si_customer_address', 'label' => 'Dirección', 'search' => true, 'type' => 'textarea'),
                array('field' => 'suggested', 'label' => 'Sugeridos', 'type' => 'partial-view', 'file' => $this->view->parameters["controller"] . '/replacement.phtml', 'notsave' => true),
                array('field' => 'si_observation_loan', 'label' => 'Observaciones', 'search' => true, 'type' => 'textarea'),
                array('field' => 'si_loan', 'label' => 'Préstamos', 'search' => true, 'type' => 'textarea'),
                array('field' => 'activities', 'label' => 'Actividades', 'type' => 'partial-view', 'file' => $this->view->parameters["controller"] . '/activities.phtml', 'notsave' => true),
                array('field' => 'qr', 'label' => 'Código QR', 'type' => 'partial-view', 'file' => $this->view->parameters["controller"] . '/qr.phtml', 'notsave' => true),
                array('field' => 'ga_si_id', 'label' => 'Galería de Fotos', 'type' => 'gallery', 'data' => $this->gallery->showAll("ga_si_id='" . $this->_getParam("id", 0) . "'"), 'notsave' => true, 'resize' => '800|600'),
            );
            $this->view->fields = $this->fields;
            $this->actions = array(
                array('type' => 'link', 'label' => 'Agregar ' . $this->singular, 'icon' => 'plus', 'controller' => $this->view->parameters["controller"], 'action' => 'add'),
                array('type' => 'collapse', 'label' => 'Filtrar', 'icon' => 'filter', 'function' => 'openFilter()'),
                array('type' => 'link', 'label' => 'Listar ' . $this->plural, 'icon' => 'list', 'controller' => $this->view->parameters["controller"], 'action' => 'index'),
                array('type' => 'link', 'label' => 'Exportar a Excel ' . $this->plural, 'icon' => 'floppy-saved', 'controller' => $this->view->parameters["controller"], 'action' => 'excel'),
            );
            $this->view->actions = $this->actions;
            $this->options = array(
                array('type' => 'link', 'title' => 'Orden de Trabajo', 'icon' => 'glyphicon glyphicon-wrench text-primary', 'controller' => $this->view->parameters["controller"], 'action' => 'order', 'modal' => false),
                array('type' => 'link', 'title' => 'Detalle', 'icon' => 'glyphicon glyphicon-eye-open text-primary', 'controller' => $this->view->parameters["controller"], 'action' => 'detail', 'modal' => false),
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
            $this->model = new Model_DBTable_Sinister();
            $this->models = new Model_DBTable_Model();
            $this->sinister_accesory = new Model_DBTable_Sinisteraccesory();
            $this->sinister_activity = new Model_DBTable_Sinisteractivity();
        } catch (Zend_Exception $exc) {
            throw new Exception($exc->getMessage());
        }
    }

    public function indexAction() {
        try {
            
            $statusArray=$this->model->getStatus();
            $this->view->status=$statusArray;
            foreach ($statusArray as $key => $label)
                $status[]=array('id'=>$key,'name'=>$label);
            
            $this->view->filter = array(
                'si_co_id' => array('label' => 'Compañía Aseguradora', 'data' => $this->company->showAll(), 'id' => 'co_id', 'value' => 'co_name'),
                'si_status' => array('label' => 'Estado', 'data' => $status, 'id' => 'id', 'value' => 'name')
            );
            //SAVE MASIVE
            if ($this->getRequest()->isPost()){
                if ($_POST["save"] == 1){
                    if (count($this->fields)){
                        foreach ($this->fields as $field){
                            if ($field['list'] == true && $field['list-edit'] == true){
                                if (count($_POST[$field["field"]])){
                                    foreach ($_POST[$field["field"]] as $ID => $VALUE){
                                        $_POST[$field["field"]] = $VALUE;
                                        $this->_helper->Form->isValid(array(1 => $field));
                                        $this->data[$ID][$field["field"]] = $VALUE;
                                    }
                                }
                            }
                        }
                    }
                    if (count($this->data)){
                        foreach ($this->data as $ID => $data){
                            $this->model->edit($data, $ID);
                        }
                    }
                    $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => MESSAGE_EDI));
                    $this->_helper->Redirector->gotoSimple('index', null, null);
                }
            }
            $where = "";
            if ($this->view->parameters["filter"] == 1){
                foreach ($this->view->filter as $key => $f){
                    if ($this->view->parameters[$key] != ""){
                        $where.=" " . $key . "='" . $this->view->parameters[$key] . "' AND";
                    }
                }
                $where = substr($where, 0, -3);
            }
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
            $providers = $this->provider->showAll();
            foreach ($paginator->getCurrentItems() as $key => $item) {
                $items[$key]          = $item;
                $accesory             = $this->sinister_accesory->getBySinister($item['si_id']);
                $items[$key]['modal'] = $this->view->partial('sinister/modal.phtml',array('item'=>$item,'providers'=>$providers,'accesory'=>$accesory));
            }
            $this->view->items = $items;
            $this->view->results = $paginator;
            $this->view->enableSearch = true;
            // $this->renderScript('_list.phtml');
        } catch (Zend_Exception $exc) {
            $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage(), 'data' => $this->getRequest()->getPost()));
            $this->_helper->Redirector->gotoSimple('index');
        }
    }

    public function addAction() {
        try {
            if ($this->getRequest()->isPost()) {
                $amount = $_POST["amount"];
                $new_activity = $_POST["new_activity"];
                $countTotal = $_POST["count"];
                $stock = $_POST["stock"];
                $sa_pr_id = $_POST["sa_pr_id"];
                $sa_date_from = $_POST["sa_date_from"];
                $sa_date = $_POST["sa_date"];
                $sa_transport = $_POST["sa_transport"];
                $ga_si_id = $_POST["ga_si_id"];
                $type_ga_si_id = $_POST["type_ga_si_id"];
                unset($_POST["ga_si_id"]);
                unset($_POST["new_activity"]);
                unset($_POST["type_ga_si_id"]);
                unset($_POST["count"]);
                unset($_POST["amount"]);
                unset($_POST["submit"]);
                unset($_POST["stock"]);
                unset($_POST["token"]);
                unset($_POST["sa_pr_id"]);
                unset($_POST["sa_date_from"]);
                unset($_POST["sa_date"]);
                unset($_POST["sa_transport"]);
                foreach ($_POST as $key => $val){
                    $this->data[$key] = $val;
                }
                $this->data["si_date"] = $this->_helper->Date->getDateFormatted($this->data["si_date"]);
                $this->data["si_ba_date_from"] = $this->_helper->Date->getDateFormatted($this->data["si_ba_date_from"]);
                $this->data["si_ba_date"] = $this->_helper->Date->getDateFormatted($this->data["si_ba_date"]);
                $this->data["si_co_date_from"] = $this->_helper->Date->getDateFormatted($this->data["si_co_date_from"]);
                $this->data["si_co_date"] = $this->_helper->Date->getDateFormatted($this->data["si_co_date"]);
                $this->data["si_po_date"] = $this->_helper->Date->getDateFormatted($this->data["si_po_date"]);
                $this->data["si_po_date_from"] = $this->_helper->Date->getDateFormatted($this->data["si_po_date_from"]);
                $this->data["si_au_date"] = $this->_helper->Date->getDateFormatted($this->data["si_au_date"]);
                $this->data["si_au_date_from"] = $this->_helper->Date->getDateFormatted($this->data["si_au_date_from"]);
                $sinister_id = $this->model->add($this->data);
                $this->sinister_accesory->deleteBySinister($sinister_id);
                foreach ($amount as $ac_id => $count){
                    if ($count > 0){
                        if ($stock[$ac_id] == 1)
                            $stock_count++;
                        $this->sinister_accesory->add(array(
                            'sa_si_id' => $sinister_id,
                            'sa_ac_id' => $ac_id,
                            'sa_count' => $count,
                            'sa_in_stock' => $stock[$ac_id],
                            'sa_pr_id' => $sa_pr_id[$ac_id],
                            'sa_date' => $this->_helper->Date->getDateFormatted($sa_date[$ac_id]),
                            'sa_date_from' => $this->_helper->Date->getDateFormatted($sa_date_from[$ac_id]),
                            'sa_transport' => $sa_transport[$ac_id],
                        ));
                    }
                }
                //GALLERY
                if ($ga_si_id){
                    $ga_si_id = array_reverse($ga_si_id);
                    $type_ga_si_id = array_reverse($type_ga_si_id);
                    foreach ($ga_si_id as $key => $item){
                        $this->gallery->add(array('ga_si_id' => $sinister_id, 'ga_name' => $item, 'ga_type' => $type_ga_si_id[$key]));
                    }
                }
                /*
                 * Control de stock
                 */

                if ($this->data["si_stock_au"] == 1)
                    $stock_count++;
                if ($this->data["si_stock_po"] == 1)
                    $stock_count++;
                if ($this->data["si_stock_co"] == 1)
                    $stock_count++;
                if ($this->data["si_stock_ba"] == 1)
                    $stock_count++;

                if ($this->data["si_tamount_au"] > 0)
                    $count++;
                if ($this->data["si_amount_po"] > 0)
                    $count++;
                if ($this->data["si_amount_co"] > 0)
                    $count++;
                if ($this->data["si_amount_ba"] > 0)
                    $count++;
                
                if (count($count) > 0){
                    if ($stock_count == count($countTotal)){
                        $this->dataEdit["si_status"] = 3;
                    }else{
                        $this->dataEdit["si_status"] = 2;
                    }
                }else{
                    $this->dataEdit["si_status"] = 1;
                }
                if(!empty($this->data["si_fullname"]) && !empty($this->data["si_phone"])){
                    $this->dataEdit["si_data_complete"]=1;
                }
                $this->dataEdit["si_number"]=$this->data["si_number"];
                $this->dataEdit["si_domain"]=$this->data["si_domain"];
                $this->dataEdit["si_qr"]=$this->_helper->Qr->generate($sinister_id);
                $this->model->edit($this->dataEdit, $sinister_id);
                $this->sinister_activity->add(array('act_si_id' => $sinister_id, 'act_observation' => "Creado", 'act_ad_id' => $this->view->loginInfo["ad_id"]));
                if (count($new_activity)){
                    foreach ($new_activity as $act){
                        $this->sinister_activity->add(array(
                            'act_si_id' => $sinister_id,
                            'act_observation' => $act,
                            'act_ad_id' => $this->view->loginInfo["ad_id"]));
                    }
                }
                $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => MESSAGE_NEW));
                $this->_helper->Redirector->gotoSimple('add', null, null);
            }           
            $this->view->companies = $this->company->showAll();
            $this->view->providers = $this->provider->showAll();
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
            $this->view->result = $this->model->get($this->view->parameters["id"]);
            if ($this->getRequest()->isPost()) {
                /* SI NO VIENE AUXILIAR */
                $_POST["si_tb_id_au"]=$this->_getParam('si_tb_id_au',0);
                $_POST["si_tm_id_au"]=$this->_getParam('si_tm_id_au',0);
                $_POST["si_ts_id_au"]=$this->_getParam('si_ts_id_au',0);
                $_POST["si_tamount_au"]=$this->_getParam('si_tamount_au',0);
                /* SI NO VIENE POSICION */
                $_POST["si_tb_id_po"]=$this->_getParam('si_tb_id_po',0);
                $_POST["si_tm_id_po"]=$this->_getParam('si_tm_id_po',0);
                $_POST["si_ts_id_po"]=$this->_getParam('si_ts_id_po',0);
                $_POST["si_amount_po"]=$this->_getParam('si_amount_po',0);
                /* SI NO VIENE CUBIERTA */
                $_POST["si_cb_id"]=$this->_getParam('si_cb_id',0);
                $_POST["si_cm_id"]=$this->_getParam('si_cm_id',0);
                $_POST["si_amount_co"]=$this->_getParam('si_amount_co',0);
                /* SI NO VIENE BATERIA */
                $_POST["si_bb_id"]=$this->_getParam('si_bb_id',0);
                $_POST["si_bm_id"]=$this->_getParam('si_bm_id',0);
                $_POST["si_amount_ba"]=$this->_getParam('si_amount_ba',0);

                $new_activity = $_POST["new_activity"];
                $countTotal = $_POST["count"];
                $amount = $_POST["amount"];
                $stock = $_POST["stock"];
                $sa_pr_id = $_POST["sa_pr_id"];
                $sa_date_from = $_POST["sa_date_from"];
                $sa_date = $_POST["sa_date"];
                $sa_transport = $_POST["sa_transport"];
                $ga_si_id = $_POST["ga_si_id"];
                $type_ga_si_id = $_POST["type_ga_si_id"];
                unset($_POST["new_activity"]);
                unset($_POST["ga_si_id"]);
                unset($_POST["type_ga_si_id"]);
                unset($_POST["count"]);
                unset($_POST["amount"]);
                unset($_POST["submit"]);
                unset($_POST["stock"]);
                unset($_POST["token"]);
                unset($_POST["sa_pr_id"]);
                unset($_POST["sa_date_from"]);
                unset($_POST["sa_date"]);
                unset($_POST["sa_transport"]);
                foreach ($_POST as $key => $val){
                    $this->data[$key] = $val;
                }
                $this->data["si_date"] = $this->_helper->Date->getDateFormatted($this->data["si_date"]);
                $this->data["si_ba_date_from"] = $this->_helper->Date->getDateFormatted($this->data["si_ba_date_from"]);
                $this->data["si_ba_date"] = $this->_helper->Date->getDateFormatted($this->data["si_ba_date"]);
                $this->data["si_co_date_from"] = $this->_helper->Date->getDateFormatted($this->data["si_co_date_from"]);
                $this->data["si_co_date"] = $this->_helper->Date->getDateFormatted($this->data["si_co_date"]);
                $this->data["si_po_date"] = $this->_helper->Date->getDateFormatted($this->data["si_po_date"]);
                $this->data["si_po_date_from"] = $this->_helper->Date->getDateFormatted($this->data["si_po_date_from"]);
                $this->data["si_au_date"] = $this->_helper->Date->getDateFormatted($this->data["si_au_date"]);
                $this->data["si_au_date_from"] = $this->_helper->Date->getDateFormatted($this->data["si_au_date_from"]);
                $this->model->edit($this->data, $this->view->parameters["id"]);
                
                $sinister_id = $this->view->parameters["id"];
                $this->sinister_accesory->deleteBySinister($sinister_id);
                foreach ($amount as $ac_id => $count){
                    if ($count > 0){
                        if ($stock[$ac_id] == 1)
                            $stock_count++;
                        $this->sinister_accesory->add(array(
                            'sa_si_id' => $sinister_id,
                            'sa_ac_id' => $ac_id,
                            'sa_count' => $count,
                            'sa_in_stock' => $stock[$ac_id],
                            'sa_pr_id' => $sa_pr_id[$ac_id],
                            'sa_date' => $this->_helper->Date->getDateFormatted($sa_date[$ac_id]),
                            'sa_date_from' => $this->_helper->Date->getDateFormatted($sa_date_from[$ac_id]),
                            'sa_transport' => $sa_transport[$ac_id],
                        ));
                    }
                }
                //GALLERY
                $this->gallery->delete_All("ga_si_id=" . $this->view->parameters["id"]);
                if ($ga_si_id){
                    $ga_si_id = array_reverse($ga_si_id);
                    $type_ga_si_id = array_reverse($type_ga_si_id);
                    foreach ($ga_si_id as $key => $item){
                        $this->gallery->add(array('ga_si_id' => $this->view->parameters["id"], 'ga_name' => $item, 'ga_type' => $type_ga_si_id[$key]));
                    }
                }
                /*
                 * Control de stock
                 */

                if ($this->data["si_stock_au"] == 1)
                    $stock_count++;
                if ($this->data["si_stock_po"] == 1)
                    $stock_count++;
                if ($this->data["si_stock_co"] == 1)
                    $stock_count++;
                if ($this->data["si_stock_ba"] == 1)
                    $stock_count++;

                if ($this->data["si_tamount_au"] > 0)
                    $count++;
                if ($this->data["si_amount_po"] > 0)
                    $count++;
                if ($this->data["si_amount_co"] > 0)
                    $count++;
                if ($this->data["si_amount_ba"] > 0)
                    $count++;

                if (count($count) > 0){
                    if ($stock_count == count($countTotal)){
                        $this->dataEdit["si_status"] = 3;
                    }else{
                        $this->dataEdit["si_status"] = 2;
                    }
                }else{
                    $this->dataEdit["si_status"] = 1;
                }

                $act_observation = "Editado";
                if ($this->view->result['si_status'] != $this->dataEdit["si_status"]){
                    switch ($this->view->result['si_status']){
                        case 1:
                        $activity_pre = "De 'Faltan Definir Repuestos'";
                        break;
                        case 2:
                        $activity_pre = "De 'En espera de repuestos'";
                        break;
                        case 3:
                        $activity_pre = "De 'Ingresado sin entregar'";
                        break;
                    }
                    switch ($this->dataEdit["si_status"]){
                        case 1:
                        $activity_suf = " a 'Faltan Definir Repuestos'";
                        break;
                        case 2:
                        $activity_suf = " a 'En espera de repuestos'";
                        break;
                        case 3:
                        $activity_suf = " a 'Ingresado sin entregar'";
                        break;
                    }
                    $act_observation = $activity_pre . $activity_suf;
                }
                $this->sinister_activity->add(array(
                    'act_si_id' => $this->view->parameters["id"],
                    'act_observation' => $act_observation,
                    'act_ad_id' => $this->view->loginInfo["ad_id"]));
                if (count($new_activity)){
                    foreach ($new_activity as $act){
                        $this->sinister_activity->add(array(
                            'act_si_id' => $this->view->parameters["id"],
                            'act_observation' => $act,
                            'act_ad_id' => $this->view->loginInfo["ad_id"]));
                    }
                }
                $this->dataEdit["si_data_complete"]=0;
                if(!empty($this->data["si_fullname"]) && !empty($this->data["si_phone"])){
                    $this->dataEdit["si_data_complete"]=1;
                }
                $this->dataEdit["si_number"]=$this->data["si_number"];
                $this->dataEdit["si_domain"]=$this->data["si_domain"];
                $this->model->edit($this->dataEdit, $sinister_id);
                $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => MESSAGE_EDI));
                $this->_helper->Redirector->gotoSimple('index', null, null);
            }
            $this->view->partial = $this->view->parameters["controller"] . '/status.phtml';
            $this->view->providers = $this->provider->showAll();
            $this->view->title = $this->title . ' / Editar';
            $this->view->token = $this->_helper->Form->setToken();
            $this->view->result = $this->model->get($this->view->parameters["id"]);
            if ($this->view->result['si_status'] > 3){
                $this->view->formDisabled = true;
            }
            $this->view->sinister_activities = $this->sinister_activity->getBySinister($this->view->parameters["id"]);
            $this->view->sinister_accesory = $this->sinister_accesory->getBySinister($this->view->parameters["id"]);
            $this->view->js = 'loadModel(' . $this->view->result["si_br_id"] . ',' . $this->view->result["si_mo_id"] . ')';
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
            $this->view->providers = $this->provider->showAll();
            $this->view->sinister_accesory = $this->sinister_accesory->getBySinister($this->view->parameters["id"]);
            $this->view->sinister_activities = $this->sinister_activity->getBySinister($this->view->parameters["id"]);
            $this->view->partial = $this->view->parameters["controller"] . '/status.phtml';
            $this->view->title = $this->title . ' / Detalle';
            $this->view->formDisabled = true;
            $this->view->js = 'loadModel(' . $this->view->result["si_br_id"] . ',' . $this->view->result["si_mo_id"] . ')';
            $this->view->description = 'Detalle de ' . $this->title . ' "' . $this->view->result[$this->view->fields[1]["field"]] . '"';
            if ($this->getRequest()->isXmlHttpRequest()){
                $this->renderScript('_detail.phtml');
            }else{
                $this->renderScript('_form.phtml');
            }
        } catch (Zend_Exception $exc) {
            $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage()));
            $this->_helper->Redirector->gotoSimple('index');
        }
    }

    public function orderAction() {
        try {
            $this->view->result = $this->model->get($this->view->parameters["id"]);
            $this->view->result["si_date"] = $this->_helper->Date->getDateFormatted($this->view->result["si_date"]);
            $this->view->providers = $this->provider->showAll();
            $this->view->sinister_accesory = $this->sinister_accesory->getBySinister($this->view->parameters["id"]);
            $this->view->sinister_activities = $this->sinister_activity->getBySinister($this->view->parameters["id"]);
            $this->view->partial = $this->view->parameters["controller"] . '/status.phtml';
            $this->view->title = $this->title . ' / Detalle';
            $this->view->formDisabled = true;
            $this->view->js = 'loadModel(' . $this->view->result["si_br_id"] . ',' . $this->view->result["si_mo_id"] . ')';
            $this->view->description = 'Detalle de ' . $this->title . ' "' . $this->view->result[$this->view->fields[1]["field"]] . '"';
            $this->renderScript($this->view->parameters["controller"] . '/qr.phtml');
        } catch (Zend_Exception $exc) {
            $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage()));
            $this->_helper->Redirector->gotoSimple('index');
        }
    }

    public function addactivityAction() {
        try {

        } catch (Zend_Exception $exc) {

        }
    }

    public function excelAction() {
        try {
            set_time_limit(0);
            $this->_helper->getHelper('layout')->disableLayout();
            $results = $this->session_excel->results_all;
            foreach ($results as $key => $result)
                $results[$key]['accesory']=$this->sinister_accesory->getBySinister($result['si_id']);  
            $this->view->results=$results;
            $this->view->providers = $this->provider->listAll();
            $this->getResponse()->setRawHeader("Content-Type: application/vnd.ms-excel; charset=UTF-8")
            ->setRawHeader("Content-Disposition: attachment; filename=ListadoDeSiniestros.xls")
            ->setRawHeader("Content-Transfer-Encoding: binary")
            ->setRawHeader("Expires: 0")
            ->setRawHeader("Cache-Control: must-revalidate, post-check=0, pre-check=0")
            ->setRawHeader("Pragma: public")
            ->sendResponse();
            $this->renderScript('sinister/excel.phtml');
        } catch (Zend_Exception $exc) {
            throw new Exception($exc->getMessage());
        }
    }

    public function changeAction() {
        try {
            if ($this->getRequest()->isPost()){
                $this->sinister_activity->add(array(
                    'act_si_id' => $this->view->parameters["id"],
                    'act_observation' => "De 'Ingresado sin entregar' a 'Entregado'",
                    'act_ad_id' => $this->view->loginInfo["ad_id"]));
                $this->model->edit(array('si_status' => 4, 'si_finish_description' => $this->view->parameters['si_number_id']), $this->view->parameters["id"]);
                exit();
            }
        } catch (Exception $exc) {
            exit($exc->getMessage());
        }
    }
    public function changestatusAction() {
        try {
            if ($this->getRequest()->isPost()){
                if($_POST['select']){
                    $status=$this->model->getStatus();
                    foreach ($_POST['select'] as $key => $value) {
                        $this->sinister_activity->add(array(
                            'act_si_id'       => $value,
                            'act_observation' => "De '".$status[$_POST["old_status"][$key]]."' a '".$status[$_POST['new_status']]."'",
                            'act_ad_id'       => $this->view->loginInfo["ad_id"]
                        ));
                        $this->model->edit(array(
                            'si_status'             => $_POST['new_status']
                        ), $value);
                    }
                }
                $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => MESSAGE_EDI));
                $this->_helper->Redirector->gotoSimple('index', null, null);
            }
        } catch (Exception $exc) {
            $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage()));
            $this->_helper->Redirector->gotoSimple('index');
        }
    }
    public function paymentAction() {
        try {
            if ($this->getRequest()->isPost()){
                $this->sinister_activity->add(array(
                    'act_si_id' => $this->view->parameters["id"],
                    'act_observation' => "De 'Entregado' a 'Facturado'",
                    'act_ad_id' => $this->view->loginInfo["ad_id"]));
                $this->model->edit(array('si_status' => 5, 'si_finish_payment' => $this->view->parameters['si_number_id']), $this->view->parameters["id"]);
                exit();
            }
        } catch (Exception $exc) {
            exit($exc->getMessage());
        }
    }

    public function loadmodelAction() {
        try {
            if ($this->getRequest()->isPost()){
                $models = $this->models->listAll("mo_br_id=" . $_POST["br_id"]);
                echo '<option value="">Selecciona un Modelo</option>';
                foreach ($models as $mo_id => $mo_name){
                    $sel = ($mo_id == $_POST["mo_id"]) ? "selected='selected'" : "";
                    echo '<option value="' . $mo_id . '" ' . $sel . '>' . $mo_name . '</option>';
                }
            }
            exit();
        } catch (Exception $exc) {

        }
    }

}
