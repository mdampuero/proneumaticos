<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */
require_once 'Sinister.php';
require_once 'Accesory.php';
require_once 'Tbranch.php';
require_once 'Cbranch.php';
require_once 'Bbranch.php';
require_once 'Provider.php';

class Admin_ReplacementController extends Zend_Controller_Action {

    public function init() {
        try {
            $this->view->setting = $this->_helper->Setting->getSetting();
            $this->view->parameters = $this->_request->getParams();
            $this->view->messages = $this->_helper->flashMessenger->getMessages();
            $this->response = $this->getResponse();
            if ($this->getRequest()->isXmlHttpRequest()) {
                $this->_helper->getHelper('layout')->disableLayout();
            } elseif ($this->view->parameters["popup"] == true) {
                Zend_Layout::startMvc(array('layout' => 'iframe', 'layoutPath' => '../application/modules/' . $this->view->parameters['module'] . '/layouts/scripts/'));
            } else {
                Zend_Layout::startMvc(array('layoutPath' => '../application/modules/' . $this->view->parameters['module'] . '/layouts/scripts/'));
            }
            //MODELS
            $this->model = new Model_DBTable_Sinister();
            $this->tbranch = new Model_DBTable_Tbranch();
            $this->cbranch = new Model_DBTable_Cbranch();
            $this->accesory = new Model_DBTable_Accesory();
            $this->bbranch = new Model_DBTable_Bbranch();
            $this->provider = new Model_DBTable_Provider();
        } catch (Zend_Exception $exc) {
            throw new Exception($exc->getMessage());
        }
    }

    public function addAction() {
        try {
            if ($this->getRequest()->isPost()):
                $this->_helper->viewRenderer->setNoRender(TRUE);
                $response["type"] = $_POST["type"];
                switch ($_POST["type"]):
                    case "au":
                        $response["si_tb_id_au"] = $this->tbranch->get($_POST["si_tb_id_au"]);
                        $response["si_tamount_au"] = $_POST["si_tamount_au"];
                        break;
                    case "po":
                        $response["si_tb_id_po"] = $this->tbranch->get($_POST["si_tb_id_po"]);
                        $response["si_tamount_po"] = $_POST["si_tamount_po"];
                    case "co":
                        list($si_cm_id, $size) = explode("###", $_POST["si_cm_id"]);
                        $response["si_cb_id"] = $this->cbranch->get($_POST["si_cb_id"]);
                        $response["size"] = $size;
                        $response["si_amount_co"] = $_POST["si_amount_co"];
                        break;
                    case "ba":
                        $response["si_bb_id"] = $this->bbranch->get($_POST["si_bb_id"]);
                        $response["si_amount_ba"] = $_POST["si_amount_ba"];
                        break;
                    default:
                        if ($_POST["type"] > 0):
                            $response["accesory"] = $this->accesory->get($_POST["type"]);
                            $response["amount"] = $_POST["amount"];
                        endif;
                        break;
                endswitch;
                $this->view->js = 'window.parent.addReplacement(\'' . json_encode($response) . '\')';
            else :
                $this->view->types = $this->_helper->Common->types;
                $this->view->accesories = $this->accesory->listAll();
                $this->view->tbranchies = $this->tbranch->listAll();
                $this->view->cbranchies = $this->cbranch->listAll();
                $this->view->bbranchies = $this->bbranch->listAll();
            endif;
        } catch (Zend_Exception $exc) {
            
        }
    }

    public function receiveAction() {
        try {
            $this->_helper->getHelper('layout')->disableLayout();
            if ($this->getRequest()->isPost()):
                $response["type"] = $_POST["type"];
                switch ($_POST["type"]):
                    case "au":
                        $response["si_tb_id_au"] = $this->tbranch->get($_POST["si_tb_id_au"]);
                        $response["si_tamount_au"] = $_POST["si_tamount_au"];
                        break;
                    case "po":
                        $response["si_tb_id_po"] = $this->tbranch->get($_POST["si_tb_id_po"]);
                        $response["si_tamount_po"] = $_POST["si_tamount_po"];
                    case "co":
                        list($si_cm_id, $size) = explode("###", $_POST["si_cm_id"]);
                        $response["si_cb_id"] = $this->cbranch->get($_POST["si_cb_id"]);
                        $response["size"] = $size;
                        $response["si_amount_co"] = $_POST["si_amount_co"];
                        break;
                    case "ba":
                        $response["si_bb_id"] = $this->bbranch->get($_POST["si_bb_id"]);
                        $response["si_amount_ba"] = $_POST["si_amount_ba"];
                        break;
                    default:
                        if ($_POST["type"] > 0):
                            $response["accesory"] = $this->accesory->get($_POST["type"]);
                            $response["amount"] = $_POST["amount"];
                        endif;
                        break;
                endswitch;
                $this->view->providers = $this->provider->showAll();
                $this->view->result = $response;
            endif;
        } catch (Zend_Exception $exc) {
            
        }
    }

    public function loadcmodelAction() {
        try {
            if ($this->getRequest()->isPost()):
                $models = $this->cmodel->showAll("cm_cb_id=" . $_POST["cb_id"]);
                echo '<option value=""></option>';
                foreach ($models as $key => $model):
                    echo '<option value="' . $model["cm_id"] . '###' . $model["cm_size"] . '">' . $model["cm_name"] . '</option>';
                endforeach;
            endif;
            exit();
        } catch (Exception $exc) {
            
        }
    }
    public function loadtsizeAction() {
        try {
            if ($this->getRequest()->isPost()):
                $conditions="ts_id <>0 ";
                if($_POST["ts_tb_id"])
                    $conditions.=" AND ts_tb_id='" . $_POST["ts_tb_id"]."'";
                if($_POST["ts_tm_id"])
                    $conditions.=" AND ts_tm_id='" . $_POST["ts_tm_id"]."'";
                $models = $this->tsize->showAll($conditions);
                echo '<option value=""></option>';
                foreach ($models as $key => $model):
                    echo '<option value="' . $model["ts_id"] . '">' . $model["ts_name"] . '</option>';
                endforeach;
            endif;
            exit();
        } catch (Exception $exc) {
            
        }
    }

}
