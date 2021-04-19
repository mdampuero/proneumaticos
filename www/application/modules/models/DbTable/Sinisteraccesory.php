<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

class Model_DBTable_Sinisteraccesory extends Zend_Db_Table_Abstract {

    protected $_name = 'sg_sinister_accesory';
    protected $primary = 'sa_id';
    protected $defultSort = 'sa_id';
    protected $defultOrder = 'ASC';

    public function init() {
        
    }

    public function add($parameters) {
        $id = $this->insert($parameters);
        if ($id > 0) {
            return $id;
        } else {
            return null;
        }
    }

    public function edit($parameters, $id) {

        if ($id > 0) {
            $this->update($parameters, $this->primary . ' = ' . (int) $id);
        } else {
            return null;
        }
    }

    public function get($id) {

        $id = (int) $id;
        $select = $this->select();
        $select->from(array($this->_name), array("*"));
        $select->setIntegrityCheck(false);
        $select->where($this->primary . ' = ' . $id);
        $row = $this->fetchRow($select);
        if (!$row) {
            return null;
        }
        return $row->toArray();
    }
    
    public function getBySinister($si_id) {
        $si_id = (int) $si_id;
        $select = $this->select();
        $select->from(array($this->_name), array("*"));
        $select->setIntegrityCheck(false);
        $select->where('sa_si_id = ' . (int) $si_id);
        $select->joinLeft('sg_accesory as ac','ac.ac_id = '.$this->_name.'.sa_ac_id');
        $select->joinLeft('sg_provider as pr','pr.pr_id = '.$this->_name.'.sa_pr_id');
        $row = $this->fetchAll($select);
        if (!$row) {
            return null;
        }
        return $row->toArray();
    }

    public function deleteBySinister($si_id) {

        return $this->delete('sa_si_id = ' . (int) $si_id);
    }

}

?>