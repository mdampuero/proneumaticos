<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

class Model_DBTable_Sinisteractivity extends Zend_Db_Table_Abstract {

    protected $_name = 'sg_sinister_activity';
    protected $primary = 'act_id';
    protected $defultSort = 'act_id';
    protected $defultOrder = 'ASC';

    public function init() {
        
    }

    public function add($parameters) {
        $parameters["act_created"]=time();
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
        $select->where('act_si_id = ' . (int) $si_id);
        $select->joinLeft('sg_admin as ad','ad.ad_id = '.$this->_name.'.act_ad_id');
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