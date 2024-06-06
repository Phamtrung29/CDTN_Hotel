<?php
session_start();
require_once('config.php');
require_once('./includes/connect.php');
require_once('./includes/phpmailer/Exception.php');
require_once('./includes/phpmailer/PHPMailer.php');
require_once('./includes/phpmailer/SMTP.php');
require_once('./includes/functions.php');
require_once('./includes/database.php');
require_once('./includes/session.php');

// sendMail('phamductrungtp12@gmail.com', 'Pham Trung Gui Email', 'Test mail cho web mới!!');

$modules = _MODULES;
$action = _ACTION;
if(!empty($_GET['modules'])){
    if(is_string($_GET['modules'])){
        $modules = trim($_GET['modules']);
    }
}
if(!empty($_GET['action'])){
    if(is_string($_GET['action'])){
        $action = trim($_GET['action']);
    }
}

$path = 'modules/'. $modules.'/'. $action. '.php';

if(file_exists($path)){
    require_once($path);
}else{
    require_once('modules/errors/404.php');
}