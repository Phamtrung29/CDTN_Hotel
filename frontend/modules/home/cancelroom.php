<?php
if (!defined('_CODE')) {
    die('Access denied....');
}

if (!isLogin()) {
    redirect('?modules=auth&action=login');
}

$order_id = $_POST['order_id'];
$order = oneRaw("SELECT * FROM orderbooking WHERE order_id = $order_id");
$check_in_date = strtotime($order['check_in_date']);
$current_date = strtotime(date('Y-m-d'));

// Kiểm tra nếu ngày hiện tại ít nhất 2 ngày trước ngày check-in
if (($check_in_date - $current_date) >= (2 * 24 * 60 * 60)) {
    // Cập nhật trạng thái đơn đặt phòng thành 'cancelled'
    $dataUpdate = [
        'order_status' => 'cancelled'
    ];
    $result = update('orderbooking', $dataUpdate, "order_id = $order_id");

    if ($result) {
        echo "<script>alert('Cancel room successful!');</script>";
    } else {
        echo "<script>alert('Cancel room failed. Please try again later.');</script>";
    }
} else {
    // Không cho phép hủy đặt phòng
    echo "<script>alert('You can only cancel bookings at least 2 days before the check-in date.');</script>";
}
?>

<div><a href="?modules=home&action=myroom">Return</a></div>