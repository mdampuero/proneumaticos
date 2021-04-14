<?php
ini_set('memory_limit', '-1');
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('America/Argentina/Buenos_Aires');
error_reporting(E_ERROR);
ini_set("soap.wsdl_cache_enabled", 0);
// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
defined('PUBLIC_PATH') || define('PUBLIC_PATH', realpath(dirname(__FILE__)));

// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    realpath(APPLICATION_PATH . '/../library/Inamika'),
    realpath(APPLICATION_PATH . '/../application/models/DbTable'),
    realpath(APPLICATION_PATH . '/../library/html2pdf_v4.03'),
    get_include_path(),
)));

//PAGINATOR
define('COUNTPERPAGE', 30);
define('TIME_SESSION',10);
define('MAX_AMOUNT',24);
define('PAGERANGE', 20);
//define('DBPREFIX', "us3_");
define('FORCE_CUT_GALLERY', true);
define('DS', DIRECTORY_SEPARATOR);
define('URL_IMG','/files/');
define('PATH_IMG', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR."files".DIRECTORY_SEPARATOR);
define('PATH_IMG_ORIGIN', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR."files".DIRECTORY_SEPARATOR."o".DIRECTORY_SEPARATOR);
define('PATH_IMG_MEDIUM', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR."files".DIRECTORY_SEPARATOR."m".DIRECTORY_SEPARATOR);
define('PATH_IMG_SMALL', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR."files".DIRECTORY_SEPARATOR."s".DIRECTORY_SEPARATOR);
define('PATH_IMG_XSMALL', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR."files".DIRECTORY_SEPARATOR."x".DIRECTORY_SEPARATOR);
define('URL_SERVICE','http://' . $_SERVER['HTTP_HOST'].str_replace("index.php", "", $_SERVER["SCRIPT_NAME"]).'service/index/');
define('PREFIX_GALLERY', "usina_");
define('TITLE_SITE', "Titulo"); //
define('COPYRIGTH', "Pie"); //
define('PATH_CONTENT', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR."files".DIRECTORY_SEPARATOR."content".DIRECTORY_SEPARATOR);

define('SHOW_MESSAGE_CHAT', 4); 
define('MESSAGE_ERR', "Ocurri&oacute; algun error en la ejecuci&oacute;n de la transacci&oacute;n."); 
define('MESSAGE_NEW', "<b>Informaci&oacute;n:</b> Alta exitosa!"); 
define('MESSAGE_EDI', "<b>Informaci&oacute;n:</b> Edici&oacute;n exitosa!");
define('MESSAGE_DEL', "<b>Informaci&oacute;n:</b> Eliminaci&oacute;n exitosa!"); 
define('MESSAGE_CAN', "<b>Informaci&oacute;n:</b> Operaci&oacute;n cancelada!"); 

/** Zend_Application */
require_once 'Zend/Application.php';
define('HOST', 'http://' . $_SERVER['HTTP_HOST']);

// Create application, bootstrap, and run
$application = new Zend_Application(
        APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini'
);

$application->bootstrap()->run();
