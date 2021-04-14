<?php

/*
 * Author: Mauricio Ampuero 
 * Organization: Inamika Interactive
 * E-Mail: mdampuero@gmail.com
 */

require_once 'Gallery.php';

class Api_PictureController extends Zend_Rest_Controller {

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
                ->initContext('json');
        $this->response = new StdClass();
    }

    protected function getBaseUrl() {
        $view = Zend_Layout::getMvcInstance()->getView();
        return HOST . $view->baseUrl();
    }

    public function indexAction() {
        
    }

    public function getAction() {
        
    }

    public function postAction() {
        
    }

    public function putAction() {
        try {
            $si_id = $this->getRequest()->getPost('si_id', 0);
            if (empty($si_id))
                throw new Exception('ID inválido: ' . $si_id);
            if ($_FILES["picture"]["error"] === 0):
                $ext = strtolower(end(explode(".", $_FILES["picture"]["name"])));
                if ($ext != "jpg" && $ext != "jpeg" && $ext != "png" && $ext != "gif")
                    throw new Zend_Controller_Action_Exception("La imagen  no es válida, solo se admite JPG, PNG o GIF.");
                $fileName = uniqid() . "." . $ext;
                move_uploaded_file($_FILES["picture"]["tmp_name"], PATH_IMG_ORIGIN . $fileName);
                $image = Zend_Controller_Action_HelperBroker::getStaticHelper('Image');
                $image->smart_resize_image(PATH_IMG_ORIGIN . $fileName, 800, 0, TRUE, PATH_IMG_MEDIUM . $fileName, FALSE, FALSE);
                $image->smart_resize_image(PATH_IMG_ORIGIN . $fileName, 400, 0, TRUE, PATH_IMG_SMALL . $fileName, FALSE, FALSE);
                $image->smart_resize_image(PATH_IMG_ORIGIN . $fileName, 200, 0, TRUE, PATH_IMG_XSMALL . $fileName, FALSE, FALSE);
                $this->gallery = new Model_DBTable_Gallery();
                $ga_id = $this->gallery->add(array(
                    'ga_name' => $fileName,
                    'ga_type' => 'photo',
                    'ga_si_id' => $si_id
                ));
            else:
                throw new Zend_Controller_Action_Exception("La imagen no se subió.");
            endif;
            $this->response->response = 1;
            $this->response->data = $ga_id;
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
