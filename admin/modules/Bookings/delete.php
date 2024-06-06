<?php
if (!defined('_CODE')) {
    die('Access denied....');
}

$bookingId = isset($_GET['id']) ? $_GET['id'] : 0;
$booking = oneRaw("SELECT * FROM bookings WHERE booking_id = $bookingId");

if (!$booking) {
    setFlashData('smg', 'Đặt phòng không tồn tại.');
    setFlashData('smg_type', 'danger');
    redirect('?page=Bookings/list');
}

$deleteStatus = delete('bookings', "booking_id = $bookingId");
if ($deleteStatus) {
    setFlashData('smg', 'Xóa đặt phòng thành công!');
    setFlashData('smg_type', 'success');
} else {
    setFlashData('smg', 'Xóa đặt phòng không thành công!');
    setFlashData('smg_type', 'danger');
}

redirect('?page=Bookings/list');
?>