<?php
if(!defined('_CODE')){
    die('Access denied....');
}

$room_id =  $_GET['id'];

$deleteStatus = delete('rooms', "room_id = $room_id");
if ($deleteStatus) {
    setFlashData('smg', 'Xóa phòng thành công!');
    setFlashData('smg_type', 'success');
} else {
    setFlashData('smg', 'Xóa phòng không thành công!');
    setFlashData('smg_type', 'danger');
}

redirect('?page=room/list');
?>