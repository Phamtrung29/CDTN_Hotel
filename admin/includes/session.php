<?php
if(!defined('_CODE')){
    die('Access denied....');
}

//Hàm gán session cho trang admin
function setSession($key, $value){
    return $_SESSION['admin_' . $key] = $value;
}

function getSession($key = ''){
    if(empty($key)){
        return $_SESSION;
    }else{
        if(isset($_SESSION['admin_' . $key])){
            return $_SESSION['admin_' . $key];
        }
    }
}

function removeSession($key){
    if(empty($key)){
        session_destroy();
        return true;
    }else{
        if(isset($_SESSION['admin_' . $key])){
            unset($_SESSION['admin_' . $key]);
        }
        return true;
    }
}

function setFlashData($key, $value){
    $key = 'admin_flash_'.$key;
    return setSession($key, $value);
}

function getFlashData($key){
    $key = 'admin_flash_'.$key;
    $data = getSession($key);
    removeSession($key);
    return $data;
}

function getSmg($smg, $type = 'success'){
    echo '<div class="alert alert-'.$type.'">';
    echo $smg;
    echo '</div>';
}

function redirect($path='index.php'){
    header("Location: $path");
    exit;
}