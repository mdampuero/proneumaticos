<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

class Model_DBTable_State extends Zend_Db_Table_Abstract {

    protected $_name = 'sg_state';
    protected $names = 'st_state';
    protected $primary = 'st_id';
    protected $deleted = 'st_deleted';
    protected $status = 'st_status';
    protected $modified = 'st_modified';
    protected $created = 'st_created';
    protected $defultSort = 'st_state';
    protected $defultOrder = 'ASC';

    public function init() {
        
    }

    public function showAll($where = null, $sort = null, $order = null) {

        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array($this->_name), array("*", "(CASE " . $this->_name . "." . $this->status . " when 1 then 'Habilitado' when 0 then 'Deshabilitado' end ) as " . $this->status . ""));
        $where = ($where == null) ? $this->deleted . "=0" : $this->deleted . "=0 AND " . $where;
        $sort = ($sort == null) ? $this->defultSort : $sort;
        $order = ($order == null) ? $this->defultOrder : $order;
        $select->where($where);
        $select->order($sort . ' ' . $order);
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

    public function logIn($data) {
        $row = $this->fetchRow("st_user='" . $data["user"] . "' and st_password='" . $data["password"] . "'");
        if ($row)
            return $row->toArray();
        else
            return null;
    }
    
    public function isExist($st_user, $id = 0) {
        if ($id == 0):
            $row = $this->fetchRow("st_user='" . $st_user . "'");
        else:
            $row = $this->fetchRow("st_user='" . $st_user . "' AND " . $this->primary . "<>" . $id);
        endif;
        if ($row) :
            return $row->toArray();
        else:
            return false;
        endif;
    }
    
    public function add($parameters) {
        $parameters[$this->created] = time();
        $parameters[$this->modified] = $parameters[$this->created];
        $id = $this->insert($parameters);
        if ($id > 0) {
            return $id;
        } else {
            return null;
        }
    }

    public function edit($parameters, $id) {

        $parameters[$this->modified] = time();
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

    public function clearData($conditions = 1) {
        $this->delete($conditions);
        return $this->info(Zend_Db_Table::NAME);
    }

}

?>