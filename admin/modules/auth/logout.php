<!-- Đăng xuất--->
<?php
if(!defined('_CODE')){
    die('Access denied....');
}

if(isLogin()){
    $token = getSession('tokenlogin');
    delete('tokenlogin', "token='$token'");
    removeSession('tokenlogin');
    redirect('?modules=auth&action=login');
}
?>