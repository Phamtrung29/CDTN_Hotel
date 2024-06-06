<?php
if(!defined('_CODE')){
    die('Access denied....');
}

$filterAll = filter();
if(!empty($filterAll['id'])){
    $userId = $filterAll['id'];
    $userDetail = getRaw("SELECT * FROM users WHERE id = '$userId'");

    if($userDetail > 0){
        $deleteToken = delete('tokenlogin', "user_id = '$userId'");
        if($deleteToken){
            $deleteUser = delete('users', "id='$userId'");
            if($deleteUser){
                setFlashData('smg', 'Xóa thành công!');
                setFlashData('smg_type', 'success');
            }else{
                setFlashData('smg', 'Xóa thất bại!');
                setFlashData('smg_type', 'danger');
            }
        }
    }else{
        setFlashData('smg', 'Người dùng không tồn tại!');
        setFlashData('smg_type', 'danger');
    }
}else{
    setFlashData('smg', 'Liên kết không tồn tại!');
    setFlashData('smg_type', 'danger');
}
redirect('?page=users/list');