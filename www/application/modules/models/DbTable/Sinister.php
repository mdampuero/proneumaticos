<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

class Model_DBTable_Sinister extends Zend_Db_Table_Abstract {

    protected $_name = 'sg_sinister';
    protected $names = 'si_name';
    protected $primary = 'si_id';
    protected $deleted = 'si_deleted';
    protected $status = 'si_status';
    protected $modified = 'si_modified';
    protected $created = 'si_created';
    protected $defultSort = 'si_id';
    protected $defultOrder = 'DESC';

    public function getStatus(){
        return array(
            1  => 'Faltan Definir Repuestos',
            2  => 'En espera de repuestos',
            3  => 'Ingresado sin entregar',
            4  => 'Entregado',
            5  => 'Facturado',
            60 => 'Dado de baja'
        );
    }
    public function init() {
        $this->view = Zend_Layout::getMvcInstance()->getView();
    }

    public function showAll($where = null, $sort = null, $order = null, $limit = 0) {

        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array($this->_name), array("*", "(CASE " . $this->_name . "." . $this->status . " "
            . "when 1 then '<span class=\"text-danger\"><b>Faltan Definir Repuestos</b></span>' "
            . "when 2 then '<span class=\"text-warning\"><b>En espera de repuestos</b></span>' "
            . "when 3 then '<span class=\"text-secondary\"><b>Ingresado sin entregar</b></span>' "
            . "when 4 then '<span class=\"text-primary\"><b>Entregado</b></span>' "
            . "when 5 then '<span class=\"text-success\"><b>Facturado</b></span>' "
            . "when 60 then '<span class=\"text-muted\"><b>Dado de baja</b></span>' "
            . " end ) as status_label", "(CASE " . $this->_name . ".si_data_complete "
            . "when 1 then '<span class=\"text-success\"><b><span class=\"glyphicon glyphicon-ok-circle\"></span></span></b>' "
            . "when 0 then '<span class=\"text-danger\"><b><span class=\"glyphicon glyphicon-exclamation-sign\"></span></span></b>' "
            . " end ) as si_data_complete","si_days as days"));
        $where = ($where == null) ? $this->deleted . "=0" : $this->deleted . "=0 AND " . $where;
        $sort = ($sort == null) ? $this->defultSort : $sort;
        $order = ($order == null) ? $this->defultOrder : $order;
        $select->where($where);
        $select->order($sort . ' ' . $order);
        $select->joinLeft("sg_company as co", "co.co_id=" . $this->_name . ".si_co_id");
        $select->joinLeft("sg_branch as br", "br.br_id=" . $this->_name . ".si_br_id");
        $select->joinLeft("sg_model as mo", "mo.mo_id=" . $this->_name . ".si_mo_id");
        $select->joinLeft("sg_tbranch as tb_au", "tb_au.tb_id=" . $this->_name . ".si_tb_id_au", array("CONCAT('[',COALESCE(tb_au.tb_code,''),'] - ',tb_au.tb_name) as tb_name_au"));
        $select->joinLeft("sg_tbranch as tb_po", "tb_po.tb_id=" . $this->_name . ".si_tb_id_po", array("CONCAT('[',COALESCE(tb_po.tb_code,''),'] - ',tb_po.tb_name) as tb_name_po"));
        $select->joinLeft("sg_cbranch as cb", "cb.cb_id=" . $this->_name . ".si_cb_id",array("CONCAT('[',COALESCE(cb.cb_code,''),'] - ',cb.cb_name) as cb_name"));
        $select->joinLeft("sg_bbranch as bb", "bb.bb_id=" . $this->_name . ".si_bb_id",array("CONCAT('[',COALESCE(bb.bb_code,''),'] - ',bb.bb_name) as bb_name"));
        $select->joinLeft("sg_provider as pr_po", "pr_po.pr_id=" . $this->_name . ".si_po_pr_id", array("pr_po.pr_name as pr_po_name"));
        $select->joinLeft("sg_provider as pr_ba", "pr_ba.pr_id=" . $this->_name . ".si_ba_pr_id", array("pr_ba.pr_name as pr_ba_name"));
        $select->joinLeft("sg_provider as pr_au", "pr_au.pr_id=" . $this->_name . ".si_au_pr_id", array("pr_au.pr_name as pr_au_name"));
        $select->joinLeft("sg_provider as pr_co", "pr_co.pr_id=" . $this->_name . ".si_co_pr_id", array("pr_co.pr_name as pr_co_name"));
        $select->limit($limit);
        $results = $this->fetchAll($select);
        $resultsArray=$results->toArray();
        // echo count($resultsArray);
        return $results->toArray();
    }
    public function showAllCustomer($where = null, $sort = null, $order = null, $limit = 0) {

        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array($this->_name), array("DISTINCT(si_fullname)","si_id","si_fullname","si_phone","si_email","si_customer_address"));
        $where = ($where == null) ? $this->deleted . "=0 AND (si_fullname<>'' OR si_phone<>'' OR si_email<>'')" : $this->deleted . "=0 AND (si_fullname<>'' OR si_phone<>'' OR si_email<>'') AND " . $where;
        $sort = ($sort == null) ? $this->defultSort : $sort;
        $order = ($order == null) ? $this->defultOrder : $order;
        $select->where($where);
        $select->order($sort . ' ' . $order);
        $select->joinLeft("sg_company as co", "co.co_id=" . $this->_name . ".si_co_id");
        $select->limit($limit);
        $results = $this->fetchAll($select);
        return $results->toArray();
    }

    public function listAll($where = null, $sort = null, $order = null) {
        $result = $this->showAll($where, $sort, $order);
        if (count($result)):
            foreach ($result as $value):
                foreach ($value as $key => $val):
                    if ($key == $this->primary):
                        $names = explode("|", $this->names);
                        $contact = null;
                        foreach ($names as $name):
                            $contact.=$value[$name] . " ";
                        endforeach;
                        $list[$val] = trim($contact);
                    endif;
                endforeach;
            endforeach;
        else:
            $list = null;
        endif;
        return $list;
    }

    public function isDuplicated($si_number,$si_domain,$si_id=0, $formato="html") {
        $select = $this->select();
        $select->from(array($this->_name), array("si_id"));
        $select->setIntegrityCheck(false);
        $select->where('si_number = "' . $si_number.'" AND si_domain="'.$si_domain.'" AND si_id<>'.$si_id.' AND si_deleted=0');
        $row = $this->fetchRow($select);
        $url=$this->view->url(array('action'=>'detail','id'=>2));
        $link=null;
        if($formato=="html"):
            $link="haga click <a href='".$url."'>AQUÍ</a> para verlo.";
        endif;
        if ($row) 
            throw new Zend_Controller_Action_Exception("Ya existe un Siniestro cargado con el Nº $si_number y Dominio $si_domain.".$link);
        return FALSE;
    }
    
    public function add($parameters,$formato="html") {
        if($this->isDuplicated($parameters["si_number"],$parameters["si_domain"],0,$formato));
        $parameters[$this->created] = time();
        $parameters[$this->modified] = $parameters[$this->created];
        $id = $this->insert($parameters);
        if ($id > 0) {
            return $id;
        } else {
            return null;
        }
    }

    public function edit($parameters, $id,$formato="html") {
        if($this->isDuplicated($parameters["si_number"],$parameters["si_domain"],$id,$formato));
        $parameters[$this->modified] = time();
        if ($id > 0) {
            $this->update($parameters, $this->primary . ' = ' . (int) $id);
        } else {
            return null;
        }
    }
   
    public function updateDays() {
        return $this->update(array("si_days"=>new Zend_Db_Expr("CONCAT(datediff('".date('Y-m-d')."',si_date),'')")), "si_status NOT IN(60,5)");
    }

    public function get($id) {
        $id = (int) $id;
        $select = $this->select();
        $select->from(array($this->_name), array("*", "(CASE " . $this->_name . "." . $this->status . " "
            . "when 1 then 'Faltan Definir Repuestos' "
            . "when 2 then 'En espera de repuestos' "
            . "when 3 then 'Ingresado sin entregar' "
            . "when 4 then 'Entregado' "
            . "when 5 then 'Facturado' "
            . " end ) as status_label"));
        $select->setIntegrityCheck(false);
        $select->where($this->primary . ' = ' . $id);
        $select->joinLeft("sg_seller as se", "se.se_id=" . $this->_name . ".si_se_id");
        $select->joinLeft("sg_company as co", "co.co_id=" . $this->_name . ".si_co_id");
        $select->joinLeft("sg_branch as br", "br.br_id=" . $this->_name . ".si_br_id");
        $select->joinLeft("sg_model as mo", "mo.mo_id=" . $this->_name . ".si_mo_id");
        $select->joinLeft("sg_state as st", "st.st_id=" . $this->_name . ".si_st_id",array("st.st_state as state_name"));
        $select->joinLeft("sg_tbranch as tb_au", "tb_au.tb_id=" . $this->_name . ".si_tb_id_au", array("CONCAT('[',COALESCE(tb_au.tb_code,''),'] - ',tb_au.tb_name) as tb_name_au"));
        $select->joinLeft("sg_tbranch as tb_po", "tb_po.tb_id=" . $this->_name . ".si_tb_id_po", array("CONCAT('[',COALESCE(tb_po.tb_code,''),'] - ',tb_po.tb_name) as tb_name_po"));
        $select->joinLeft("sg_cbranch as cb", "cb.cb_id=" . $this->_name . ".si_cb_id",array("CONCAT('[',COALESCE(cb.cb_code,''),'] - ',cb.cb_name) as cb_name"));
        $select->joinLeft("sg_bbranch as bb", "bb.bb_id=" . $this->_name . ".si_bb_id",array("CONCAT('[',COALESCE(bb.bb_code,''),'] - ',bb.bb_name) as bb_name"));
        $select->joinLeft("sg_provider as pr_po", "pr_po.pr_id=" . $this->_name . ".si_po_pr_id", array("pr_po.pr_name as pr_po_name"));
        $select->joinLeft("sg_provider as pr_ba", "pr_ba.pr_id=" . $this->_name . ".si_ba_pr_id", array("pr_ba.pr_name as pr_ba_name"));
        $select->joinLeft("sg_provider as pr_au", "pr_au.pr_id=" . $this->_name . ".si_au_pr_id", array("pr_au.pr_name as pr_au_name"));
        $select->joinLeft("sg_provider as pr_co", "pr_co.pr_id=" . $this->_name . ".si_co_pr_id", array("pr_co.pr_name as pr_co_name"));
        $row = $this->fetchRow($select);
        if (!$row) {
            return null;
        }
        return $row->toArray();
    }

    public function delete_row($id) {

        return $this->delete($this->primary . ' = ' . (int) $id);
    }

    public function delete_slow($id) {

        $parameters[$this->modified] = time();
        $parameters[$this->deleted] = 1;
        if ($id > 0) {
            $this->update($parameters, $this->primary . ' = ' . (int) $id);
        } else {
            return null;
        }
    }
    public function optimized(){
        $this->update(array('si_deleted'=>1),'si_date  <  DATE_SUB(curdate(), INTERVAL 1 YEAR)');
        return true;
    }

    public function clearData($conditions = 1) {
        $this->delete($conditions);
        return $this->info(Zend_Db_Table::NAME);
    }

}

?>