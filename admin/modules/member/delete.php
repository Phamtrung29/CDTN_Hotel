<?php
if(!defined('_CODE')){
    die('Access denied....');
}

$member_id = $_GET['id'];
$result = delete('members', "member_id = $member_id");

if ($result) {
    setFlashData('msg', 'Xóa loại phòng thành công!');
    setFlashData('msg_type', 'success');
} else {
    setFlashData('msg', 'Xóa loại phòng thất bại!');
    setFlashData('msg_type', 'danger');
}

redirect('?page=member/list');
?>