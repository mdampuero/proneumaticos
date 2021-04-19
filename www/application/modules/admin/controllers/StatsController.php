<?php

require_once 'Sinister.php';
require_once 'Sinisteraccesory.php';
require_once 'Provider.php';
require_once 'Common.php';

class Admin_StatsController extends Zend_Controller_Action {

    public function init() {
        $this->view->loginInfo = $this->_helper->Login->isLogin();
        $this->view->parameters = $this->_request->getParams();
        $this->view->setting = $this->_helper->Setting->getSetting();
        $this->view->messages = $this->_helper->flashMessenger->getMessages();
        $this->sinister=new Model_DBTable_Sinister();
        $this->sinister_accesory = new Model_DBTable_Sinisteraccesory();
        Zend_Layout::startMvc(array('layoutPath' => '../application/modules/' . $this->view->parameters['module'] . '/layouts/scripts/'));
    }

    public function indexAction() { }
    
    public function purchaseorderAction() {
        try {
            $this->_helper->getHelper('layout')->disableLayout();
            set_time_limit(0);
            $results = $this->sinister->showAll('si_status = 2');
            foreach ($results as $key => $result)
                $results[$key]['accesory']=$this->sinister_accesory->getBySinister($result['si_id']);
            $this->view->results=$results;
            $this->getResponse()->setRawHeader("Content-Type: application/vnd.ms-excel; charset=UTF-8")
            ->setRawHeader("Content-Disposition: attachment; filename=OrdenDeCompra_".date("Ymd").".xls")
            ->setRawHeader("Content-Transfer-Encoding: binary")
            ->setRawHeader("Expires: 0")
            ->setRawHeader("Cache-Control: must-revalidate, post-check=0, pre-check=0")
            ->setRawHeader("Pragma: public")
            ->sendResponse();
        } catch (Zend_Exception $exc) {
            throw new Exception($exc->getMessage());
        }
    }
    
    public function salesAction() {
        try {
            $this->_helper->getHelper('layout')->disableLayout();
            $this->provider = new Model_DBTable_Provider();
            $fromArray=explode('-',$this->view->parameters['date_from']);
            $toArray=explode('-',$this->view->parameters['date_to']);
            $results = $this->sinister->showAll("si_date>='" . $fromArray[2]."-".$fromArray[1]."-".$fromArray[0] . "' AND si_date<='" . $toArray[2]."-".$toArray[1]."-".$toArray[0] . "'");
            foreach ($results as $key => $result)
                $results[$key]['accesory']=$this->sinister_accesory->getBySinister($result['si_id']);  
            $this->view->results=$results;
            $this->view->providers = $this->provider->listAll();
            $this->getResponse()->setRawHeader("Content-Type: application/vnd.ms-excel; charset=UTF-8")
            ->setRawHeader("Content-Disposition: attachment; filename=TotalCostoVentaSiniestros.xls")
            ->setRawHeader("Content-Transfer-Encoding: binary")
            ->setRawHeader("Expires: 0")
            ->setRawHeader("Cache-Control: must-revalidate, post-check=0, pre-check=0")
            ->setRawHeader("Pragma: public")
            ->sendResponse();
        } catch (Zend_Exception $exc) {
            throw new Exception($exc->getMessage());
        }
    }
}
