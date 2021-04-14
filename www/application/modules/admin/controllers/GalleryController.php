<?php

class Admin_GalleryController extends Zend_Controller_Action {

    public function init() {
        try {
            $this->view->loginInfo = $this->_helper->Login->isLogin();
            $this->view->parameters = $this->_request->getParams();
            $this->view->setting = $this->_helper->Setting->getSetting();
            $this->view->messages = $this->_helper->flashMessenger->getMessages();
            if ($this->view->messages[0]["data"])
                $this->view->result = $this->view->messages[0]["data"];
            if ($this->view->parameters["popup"] == true):
                Zend_Layout::startMvc(array('layout' => 'iframe', 'layoutPath' => '../application/modules/' . $this->view->parameters['module'] . '/layouts/scripts/'));
            else:
                Zend_Layout::startMvc(array('layoutPath' => '../application/modules/' . $this->view->parameters['module'] . '/layouts/scripts/'));
            endif;
        } catch (Zend_Exception $exc) {
            throw new Exception($exc->getMessage());
        }
    }

    public function indexAction() {
        try {
            $this->view->icon = '';
            $this->view->title = '';
            $this->view->description = '';
        } catch (Zend_Exception $exc) {
            throw new Exception($exc->getMessage());
        }
    }

    public function uploadAction() {
        try {
            if ($this->getRequest()->isPost()):
                if ($_POST["submit"] == "photo"):
                    if ($_FILES["file"]["error"] == 0):
                        $ext = $this->_helper->Image->is_valid_image($_FILES["file"]["name"]);
                        $name_file = uniqid() . "." . $ext;
                        move_uploaded_file($_FILES["file"]["tmp_name"], PATH_IMG . $name_file);
                        $this->view->parameters["name_file"] = $name_file;
                        if (!empty($this->view->parameters["resize"])):
                            $newsize = explode("|", $this->view->parameters["resize"]);
                            $this->_helper->Image->smart_resize_image(PATH_IMG . $name_file, $newsize[0], 0, true, 'file', true);
                            $this->_helper->Image->smart_resize_image(PATH_IMG . $name_file, 800, 0, TRUE, PATH_IMG_MEDIUM . $name_file, FALSE, FALSE);
                            $this->_helper->Image->smart_resize_image(PATH_IMG . $name_file, 400, 0, TRUE, PATH_IMG_SMALL . $name_file, FALSE, FALSE);
                            $this->_helper->Image->smart_resize_image(PATH_IMG . $name_file, 200, 0, TRUE, PATH_IMG_XSMALL . $name_file, FALSE, FALSE);
                        endif;
                        if (!empty($this->view->parameters["cut"])):
                            $this->_helper->Redirector->gotoSimple('cut', 'gallery', 'admin', $this->view->parameters);
                        endif;
                        $this->_helper->Redirector->gotoSimple('return', 'gallery', 'admin', $this->view->parameters);
                    endif;
                else:
                    $this->view->parameters["tab_video"] = true;
                    $this->_helper->Image->is_valid_video($_POST["url"]);
                    $this->_helper->flashMessenger->addMessage(array('type' => 'success', 'message' => 'La url se agregó correctamente', 'data' => $this->getRequest()->getPost()));
                    unset($this->view->parameters["url"]);
                    $this->_helper->Redirector->gotoSimple('preview', 'gallery', 'admin', $this->view->parameters);
                endif;
            endif;
            $this->view->icon = 'picture';
            $this->view->title = 'Seleccione una imagen';
        } catch (Zend_Exception $exc) {
            $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage(), 'data' => $this->getRequest()->getPost()));
            $this->_helper->Redirector->gotoSimple('upload', 'gallery', 'admin', $this->view->parameters);
        }
    }

    public function uploadbyajaxAction() {
        $this->_helper->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        try {
            $response=array('response'=>false,'file'=>null);
            if ($this->getRequest()->isPost()){
                if ($_FILES["files"]["error"][0] == 0){
                    $ext = $this->_helper->Image->is_valid_image($_FILES["files"]["name"][0]);
                    $name_file = uniqid() . "." . $ext;
                    move_uploaded_file($_FILES["files"]["tmp_name"][0], PATH_IMG . $name_file);
                    $this->_helper->Image->smart_resize_image(PATH_IMG . $name_file, 800, 0, true, 'file', true);
                    
                    if (!file_exists(PATH_IMG_MEDIUM))
                        mkdir(PATH_IMG_MEDIUM, 0777, true);
                    $this->_helper->Image->smart_resize_image(PATH_IMG . $name_file, 800, 0, TRUE, PATH_IMG_MEDIUM . $name_file, FALSE, FALSE);

                    if (!file_exists(PATH_IMG_SMALL))
                        mkdir(PATH_IMG_SMALL, 0777, true);
                    $this->_helper->Image->smart_resize_image(PATH_IMG . $name_file, 400, 0, TRUE, PATH_IMG_SMALL . $name_file, FALSE, FALSE);

                    if (!file_exists(PATH_IMG_XSMALL))
                        mkdir(PATH_IMG_XSMALL, 0777, true);
                    $this->_helper->Image->smart_resize_image(PATH_IMG . $name_file, 200, 0, TRUE, PATH_IMG_XSMALL . $name_file, FALSE, FALSE);

                    $response=array('response'=>true,'file'=>$name_file);
                }
            }
            echo json_encode($response);
        } catch (Zend_Exception $exc) {
            $response=array('response'=>false,'message'=>$exc->getMessage());
            echo json_encode($response);
        }
    }
    public function uploadbyurlAction() {
        $this->_helper->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        try {
            $response=array('response'=>false,'file'=>null);
            if ($this->getRequest()->isPost()){
                if ($_POST["url"]){
                    $ext = $this->_helper->Image->is_valid_image($_POST["url"]);
                    $name_file = uniqid() . "." . $ext;
                    $upload = file_put_contents(PATH_IMG.$name_file,file_get_contents($_POST["url"]));
                    $this->_helper->Image->smart_resize_image(PATH_IMG . $name_file, 800, 0, true, 'file', true);
                    
                    if (!file_exists(PATH_IMG_MEDIUM))
                        mkdir(PATH_IMG_MEDIUM, 0777, true);
                    $this->_helper->Image->smart_resize_image(PATH_IMG . $name_file, 800, 0, TRUE, PATH_IMG_MEDIUM . $name_file, FALSE, FALSE);

                    if (!file_exists(PATH_IMG_SMALL))
                        mkdir(PATH_IMG_SMALL, 0777, true);
                    $this->_helper->Image->smart_resize_image(PATH_IMG . $name_file, 400, 0, TRUE, PATH_IMG_SMALL . $name_file, FALSE, FALSE);

                    if (!file_exists(PATH_IMG_XSMALL))
                        mkdir(PATH_IMG_XSMALL, 0777, true);
                    $this->_helper->Image->smart_resize_image(PATH_IMG . $name_file, 200, 0, TRUE, PATH_IMG_XSMALL . $name_file, FALSE, FALSE);

                    $response=array('response'=>true,'file'=>$name_file);
                }
            }
            echo json_encode($response);
        } catch (Zend_Exception $exc) {
            $response=array('response'=>false,'message'=>$exc->getMessage());
            echo json_encode($response);
        }
    }

    public function cutAction() {
        try {
            if ($this->getRequest()->isPost()):
                $this->_helper->Image->cutImage(PATH_IMG . $_POST["image"], NULL, $_POST["x1"], $_POST["y1"], $_POST["x2"], $_POST["y2"], $_POST["w"], $_POST["h"]);
                $this->_helper->Redirector->gotoSimple('return', 'gallery', 'admin', $this->view->parameters);
            endif;
            $this->view->icon = 'picture';
            $this->view->title = 'Seleccione el area de recorte';
            $cut = explode('|', $this->view->parameters["cut"]);
            $this->view->description = "Área mínima de recorte " . $cut[0] . "x" . $cut[1] . "px.";
        } catch (Zend_Exception $exc) {
            $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage(), 'data' => $this->getRequest()->getPost()));
            $this->_helper->Redirector->gotoSimple('upload', 'gallery', 'admin', $this->view->parameters);
        }
    }

    public function previewAction() {
        try {
            $this->view->icon = 'facetime-video';
            $this->view->title = 'Video';
            $this->view->description = "Haga clic en Aceptar si puede visualizar el video, sino vuelva a intentarlo con otra URL.";
        } catch (Zend_Exception $exc) {
            $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage(), 'data' => $this->getRequest()->getPost()));
            $this->_helper->Redirector->gotoSimple('upload', 'gallery', 'admin', $this->view->parameters);
        }
    }

    public function returnAction() {
        try {

        } catch (Zend_Exception $exc) {
            $this->_helper->flashMessenger->addMessage(array('type' => 'danger', 'message' => $exc->getMessage()));
            $this->_helper->Redirector->gotoSimple('upload', 'gallery', 'admin', $this->view->parameters);
        }
    }

}
