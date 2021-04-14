<?php

require_once 'PHPMailer/class.phpmailer.php';

class Zend_Controller_Action_Helper_Mail extends Zend_Controller_Action_Helper_Abstract {

    public function __construct() {
        $this->_setting = Zend_Controller_Action_HelperBroker::getStaticHelper('Setting');
        $setting = $this->_setting->getSetting();
        $this->host = $setting["se_email_host"];
        $this->port = $setting["se_email_port"];
        $this->username = $setting["se_email_user"];
        $this->password = $setting["se_email_password"];
        $this->email = $setting["se_email_email"];
        $this->name = $setting["se_email_name"];
        $this->secure = $setting["se_email_secure"];
    }

    public function sendEmail($contenido, $asunto, $toEmail = "", $toName = "") {
//        try {
             
            if (!$toEmail) {
                $toEmail = $this->email;
            }
            if (!$toName) {
                $toName = $this->name;
            }
            $this->mail = new PHPMailer();
            $this->mail->IsSMTP();
            if ($this->secure):
                $this->mail->SMTPSecure = $this->secure;
            endif;
            $this->mail->SMTPAuth = true;
            $this->mail->Host = $this->host;
            $this->mail->Port = $this->port;
            $this->mail->Username = $this->username;
            $this->mail->Password = $this->password;
            $this->mail->AddAddress($toEmail, $toName);
            $this->mail->SetFrom($this->email, $this->name);
            $this->mail->Subject = utf8_decode($asunto);
            $this->mail->MsgHTML(utf8_decode($contenido));
            if (!$this->mail->Send()) {
                throw new Zend_Controller_Action_Exception("Error de autenticación SMTP", 599);
            }
//        } catch (Exception $ex) {
//            throw new Zend_Controller_Action_Exception($ex->getMessage(), 599);
//        }
    }

}

?>