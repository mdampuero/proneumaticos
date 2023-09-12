<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Sinister.php';

class Api_StatsController extends Zend_Rest_Controller
{

    public $response;

    public function init()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $this->_helper->AjaxContext()
            ->addActionContext('index', 'json')
            ->addActionContext('get', 'json')
            ->addActionContext('post', 'json')
            ->addActionContext('put', 'json')
            ->addActionContext('delete', 'json')
            ->addActionContext('totalbycompany', 'json')
            ->initContext('json');
        $this->response = new StdClass();
    }


    public function totalbycompanyAction()
    {
        try {
            $this->sinister = new Model_DBTable_Sinister();
            $this->sendResponse($this->_helper->json($this->sinister->totalByCompany($this->buildConditions($this->getRequest()))));
        } catch (Exception $ex) {
            $this->response->response = 0;
            $this->response->message = $ex->getMessage();
            $this->sendResponse($this->_helper->json($this->response));
        }
    }
    public function totalbystatusAction()
    {
        try {
            $this->sinister = new Model_DBTable_Sinister();
            $this->sendResponse($this->_helper->json($this->sinister->totalByStatus($this->buildConditions($this->getRequest()))));
        } catch (Exception $ex) {
            $this->response->response = 0;
            $this->response->message = $ex->getMessage();
            $this->sendResponse($this->_helper->json($this->response));
        }
    }
    public function totalbystateAction()
    {
        try {
            $this->sinister = new Model_DBTable_Sinister();
            $this->sendResponse($this->_helper->json($this->sinister->totalByState($this->buildConditions($this->getRequest()))));
        } catch (Exception $ex) {
            $this->response->response = 0;
            $this->response->message = $ex->getMessage();
            $this->sendResponse($this->_helper->json($this->response));
        }
    }
    public function totalbybrandAction()
    {
        try {
            $this->sinister = new Model_DBTable_Sinister();
            $this->sendResponse($this->_helper->json($this->sinister->totalByBrand($this->buildConditions($this->getRequest()))));
        } catch (Exception $ex) {
            $this->response->response = 0;
            $this->response->message = $ex->getMessage();
            $this->sendResponse($this->_helper->json($this->response));
        }
    }
    public function totalbydayAction()
    {
        try {
            $this->sinister = new Model_DBTable_Sinister();
            $this->sendResponse($this->_helper->json($this->sinister->totalByDay($this->buildConditions($this->getRequest()))));
        } catch (Exception $ex) {
            $this->response->response = 0;
            $this->response->message = $ex->getMessage();
            $this->sendResponse($this->_helper->json($this->response));
        }
    }
    public function totalbyneuAction()
    {
        try {
            $this->sinister = new Model_DBTable_Sinister();
            $this->sendResponse($this->_helper->json($this->sinister->totalByNeu($this->buildConditions($this->getRequest()))));
        } catch (Exception $ex) {
            $this->response->response = 0;
            $this->response->message = $ex->getMessage();
            $this->sendResponse($this->_helper->json($this->response));
        }
    }

    public function totalbyauxAction()
    {
        try {
            $this->sinister = new Model_DBTable_Sinister();
            $this->sendResponse($this->_helper->json($this->sinister->totalByAux($this->buildConditions($this->getRequest()))));
        } catch (Exception $ex) {
            $this->response->response = 0;
            $this->response->message = $ex->getMessage();
            $this->sendResponse($this->_helper->json($this->response));
        }
    }
    
    public function totalbyposAction()
    {
        try {
            $this->sinister = new Model_DBTable_Sinister();
            $this->sendResponse($this->_helper->json($this->sinister->totalByPos($this->buildConditions($this->getRequest()))));
        } catch (Exception $ex) {
            $this->response->response = 0;
            $this->response->message = $ex->getMessage();
            $this->sendResponse($this->_helper->json($this->response));
        }
    }

    public function indexAction()
    {

    }

    public function getAction()
    {

    }

    public function postAction()
    {

    }

    public function putAction()
    {

    }

    public function deleteAction()
    {

    }

    protected function buildConditions($request)
    {
        $conditions = array();
        if ($request->isPost()):
            $conditions[] = ($request->getPost('date_from', null)) ? "si_created >=" . strtotime(DateTime::createFromFormat('d/m/Y', $request->getPost('date_from', null))->format('Y-m-d') . " 00:00:00") : "";
            $conditions[] = ($request->getPost('date_to', null)) ? "si_created <=" . strtotime(DateTime::createFromFormat('d/m/Y', $request->getPost('date_to', null))->format('Y-m-d') . " 23:59:59") : "";
            if($request->getPost('si_co_id', null)){
                $conditions[] = "si_co_id = '" .$request->getPost('si_co_id'). "'";
            }
            if($request->getPost('si_br_id', null)){
                $conditions[] = "si_br_id = '" .$request->getPost('si_br_id'). "'";
            }
            if($request->getPost('si_status', null)){
                $conditions[] = "si_status = '" .$request->getPost('si_status'). "'";
            }
            if($request->getPost('si_st_id', null)){
                $conditions[] = "si_st_id = '" .$request->getPost('si_st_id'). "'";
            }
        endif;
        return $conditions;
    }

}