<?php
if(!defined('_CODE')){
    die('Access denied....');
}

$service_id = $_GET['id'];
$result = delete('services', "service_id = '$service_id'");

if ($result) {
    setFlashData('msg', 'Xóa loại phòng thành công!');
    setFlashData('msg_type', 'success');
} else {
    setFlashData('msg', 'Xóa loại phòng thất bại!');
    setFlashData('msg_type', 'danger');
}

redirect('?page=service/list');
?>