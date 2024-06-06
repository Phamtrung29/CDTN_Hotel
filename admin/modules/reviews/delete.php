<?php
if(!defined('_CODE')){
    die('Access denied....');
}

$review_id = $_GET['id'];
$result = delete('reviews', "review_id = $review_id");

if ($result) {
    setFlashData('msg', 'Xóa loại phòng thành công!');
    setFlashData('msg_type', 'success');
} else {
    setFlashData('msg', 'Xóa loại phòng thất bại!');
    setFlashData('msg_type', 'danger');
}

redirect('?page=reviews/list');
?>