<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Sinister.php';
require_once 'Sinisteraccesory.php';
require_once 'Sinisteractivity.php';
require_once 'Gallery.php';
require_once 'Users.php';
require_once 'Company.php';
require_once 'Branch.php';
require_once 'Tbranch.php';
require_once 'Cbranch.php';
require_once 'Bbranch.php';
require_once 'Model.php';
require_once 'Accesory.php';
require_once 'Provider.php';

class Api_SinisterController extends Zend_Rest_Controller {

    public $response;

    public function init() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $this->_helper->AjaxContext()
                ->addActionContext('index', 'json')
                ->addActionContext('get', 'json')
                ->addActionContext('post', 'json')
                ->addActionContext('put', 'json')
                ->addActionContext('delete', 'json')
                ->addActionContext('llantas', 'json')
                ->initContext('json');
        $this->response = new StdClass();
        $request = new Zend_Controller_Request_Http();
        $key = $request->getHeader('key', false);
        if ($key !== "Secreta007"):
            $this->response->response = 0;
            $this->response->message = 'Key inválida!.';
            $this->sendResponse($this->_helper->json($this->response));
        endif;
    }

    protected function getBaseUrl() {
        $view = Zend_Layout::getMvcInstance()->getView();
        return HOST . $view->baseUrl();
    }

    public function getDateFormatted($date) {
        $dateExploded = explode('-', $date);
        if (count($dateExploded) == 1) {
            return $date;
        }
        return ($dateExploded[2] . '-' . $dateExploded[1] . '-' . $dateExploded[0]);
    }

    public function indexAction() {
        try {
            $this->sinister = new Model_DBTable_Sinister();
            if ($this->getRequest()->isPost()):
                $where = ($_POST["where"]) ? $_POST["where"] : "";
                $limit = ($_POST["limit"]) ? $_POST["limit"] : 0;
            endif;
            $this->response->response = 1;
            $this->response->data = $this->sinister->showAll($where, NULL, "DESC", $limit);
            $this->sendResponse($this->_helper->json($this->response));
        } catch (Exception $ex) {
            $this->response->response = 0;
            $this->response->message = $ex->getMessage();
            $this->sendResponse($this->_helper->json($this->response));
        }
    }

    public function getAction() {
        try {
            $si_id = $this->getRequest()->getPost('si_id', 0);
            if (empty($si_id))
                throw new Exception('ID inválido: ' . $si_id);
            $this->sinister = new Model_DBTable_Sinister();
            $this->gallery = new Model_DBTable_Gallery();
            $this->sinister_accesory = new Model_DBTable_Sinisteraccesory();
            $this->sinister_activity = new Model_DBTable_Sinisteractivity();
            $sinister = $this->sinister->get($si_id);
            $sinister["base_url"] = $this->getBaseUrl() . "/";
            $sinister["si_date"] = $this->getDateFormatted($sinister["si_date"]);
            $sinister["pictures"] = $this->gallery->showAll("ga_si_id=" . $si_id);
            $sinister["accesories"] = $this->sinister_accesory->getBySinister($si_id);
            $sinister["activities"] = $this->sinister_activity->getBySinister($si_id);
            $this->response->response = 1;
            $this->response->data = $sinister;
            $this->sendResponse($this->_helper->json($this->response));
        } catch (Exception $ex) {
            $this->response->response = 0;
            $this->response->message = $ex->getMessage();
            $this->sendResponse($this->_helper->json($this->response));
        }
    }

    public function postAction() {
        try {
            $this->user = new Model_DBTable_Users();
            $this->company = new Model_DBTable_Company();
            $this->branch = new Model_DBTable_Branch();
            $this->model = new Model_DBTable_Model();
            $this->accesory = new Model_DBTable_Accesory();
            $this->provider = new Model_DBTable_Provider();
            $data = array();
            $data["users"] = $this->user->showAll("ad_ar_id<>1");
            $companies = $this->company->showAll("");
            $data["branchies"] = $this->branch->showAll("");
            $data["accesories"] = $this->accesory->listAll("");
            foreach ($data["branchies"] as $key => $b):
                $data["models"][$b["br_id"]] = $this->model->listAll("mo_br_id=" . $b["br_id"]);
            endforeach;
            foreach ($companies as $key => $c):
                $c["co_observation"] = nl2br(trim($c["co_observation"]));
                $data["companies"][$c["co_id"]] = $c;
            endforeach;
            $data["providers"] = $this->provider->listAll();
            $this->response->response = 1;
            $this->response->data = $data;
            $this->sendResponse($this->_helper->json($this->response));
        } catch (Exception $ex) {
            $this->response->response = 0;
            $this->response->message = $ex->getMessage();
            $this->sendResponse($this->_helper->json($this->response));
        }
    }

    public function llantasAction() {
        try {
            $this->tbranch = new Model_DBTable_Tbranch();
            $tbranch = $this->tbranch->showAll();
            $this->response->response = 1;
            $this->response->data = $tbranch;
            $this->sendResponse($this->_helper->json($this->response));
        } catch (Exception $ex) {
            $this->response->response = 0;
            $this->response->message = $ex->getMessage();
            $this->sendResponse($this->_helper->json($this->response));
        }
    }

    public function cubiertasAction() {
        try {
            $this->cbranch = new Model_DBTable_Cbranch();
            $cbranch = $this->cbranch->showAll();
            $this->response->response = 1;
            $this->response->data = $cbranch;
            $this->sendResponse($this->_helper->json($this->response));
        } catch (Exception $ex) {
            $this->response->response = 0;
            $this->response->message = $ex->getMessage();
            $this->sendResponse($this->_helper->json($this->response));
        }
    }

    public function bateriasAction() {
        try {
            $this->bbranch = new Model_DBTable_Bbranch();
            $bbranch = $this->bbranch->showAll();
            $this->response->response = 1;
            $this->response->data = $bbranch;
            $this->sendResponse($this->_helper->json($this->response));
        } catch (Exception $ex) {
            $this->response->response = 0;
            $this->response->message = $ex->getMessage();
            $this->sendResponse($this->_helper->json($this->response));
        }
    }

    public function putAction() {
        try {
            $this->sinister = new Model_DBTable_Sinister();
            $this->sinister_activity = new Model_DBTable_Sinisteractivity();
            $this->sinister_accesory = new Model_DBTable_Sinisteraccesory();
            $this->data = $this->getRequest()->getPost('data', null);
            if (empty($this->data["ad_id"]))
                throw new Exception('Vendedor inválido');
            if (empty($this->data["si_number"]))
                throw new Exception('Número de Siniestro inválido');
            if (empty($this->data["si_domain"]))
                throw new Exception('Número de Dominio inválido');
            if (empty($this->data["si_co_id"]))
                throw new Exception('Compañia inválido');
            $this->data["si_date"] = date("Y-m-d");
            if (!empty($this->data["si_fullname"]) && !empty($this->data["si_phone"])):
                $this->data["si_data_complete"] = 1;
            endif;
            $ad_id = $this->data["ad_id"];
            $countTotal = $this->data["count"];

            $amount = $this->data["amount"];
            $stock = $this->data["stock"];
            $sa_pr_id = $this->data["sa_pr_id"];
            $sa_date_from = $this->data["sa_date_from"];
            $sa_date = $this->data["sa_date"];
            $sa_transport = $this->data["sa_transport"];
            $ac_id = $this->data["ac_id"];

            unset($this->data["amount"]);
            unset($this->data["stock"]);
            unset($this->data["sa_pr_id"]);
            unset($this->data["sa_date_from"]);
            unset($this->data["sa_date"]);
            unset($this->data["sa_transport"]);
            unset($this->data["ac_id"]);

            unset($this->data["ad_id"]);
            unset($this->data["count"]);
            $sinister_id = $this->sinister->add($this->data, "json");
            foreach ($ac_id as $key => $id):
                $count++;
                if ($stock[$key] == 1)
                    $stock_count++;
                $this->sinister_accesory->add(array(
                    'sa_si_id' => $sinister_id,
                    'sa_ac_id' => $id,
                    'sa_count' => $amount[$key],
                    'sa_in_stock' => $stock[$key],
                    'sa_pr_id' => $sa_pr_id[$key],
                    'sa_date' => $sa_date[$key],
                    'sa_date_from' => $sa_date_from[$key],
                    'sa_transport' => $sa_transport[$key],
                ));
            endforeach;
            /* QR */
            $this->_qr = Zend_Controller_Action_HelperBroker::getStaticHelper('Qr');
            /* */
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

            if ($count > 0):
                if ($stock_count == $countTotal):
                    $this->dataEdit["si_status"] = 3;
                else:
                    $this->dataEdit["si_status"] = 2;
                endif;
            else:
                $this->dataEdit["si_status"] = 1;
            endif;
            if (!empty($this->data["si_fullname"]) && !empty($this->data["si_phone"])):
                $this->dataEdit["si_data_complete"] = 1;
            endif;
            $this->dataEdit["si_number"] = $this->data["si_number"];
            $this->dataEdit["si_domain"] = $this->data["si_domain"];
            $this->dataEdit["si_qr"] = $this->_qr->generate($sinister_id);
            $this->sinister->edit($this->dataEdit, $sinister_id);

            $this->sinister_activity->add(array('act_si_id' => $sinister_id, 'act_observation' => "Creado (App)", 'act_ad_id' => $ad_id));
            $this->response->response = 1;
            $this->response->data = $sinister_id;
            $this->sendResponse($this->_helper->json($this->response));
        } catch (Exception $ex) {
            $this->response->response = 0;
            $this->response->message = $ex->getMessage();
            $this->sendResponse($this->_helper->json($this->response));
        }
    }

    public function deleteAction() {
        
    }

}
