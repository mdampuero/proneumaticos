<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

class IndexController extends Zend_Controller_Action {

    public function init() {
        $this->_redirect('/admin/login');
    }
}
