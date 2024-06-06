<?php
// update_status.php

if (!defined('_CODE')) {
    define('_CODE', true);
}

// Kiểm tra xem người dùng đã submit form cập nhật trạng thái đơn hàng chưa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Nhận dữ liệu từ form
    $order_id = intval($_POST['order_id']);
    
    // Nếu form có trường cập nhật trạng thái đơn hàng
    if (isset($_POST['order_status'])) {
        $order_status = $_POST['order_status'];
        
        // Tạo mảng dữ liệu cập nhật
        $dataUpdate = [
            'order_status' => $order_status
        ];
        
        // Thực hiện cập nhật vào cơ sở dữ liệu
        update('orderbooking', $dataUpdate, "order_id = $order_id");
        
        // Đặt flash message thông báo thành công
        setFlashData('smg', 'Trạng thái đơn hàng đã được cập nhật.');
        setFlashData('smg_type', 'success');
    } 
    // Nếu form có trường mã xác nhận
    elseif (isset($_POST['confirmation_code'])) {
        $confirmation_code = $_POST['confirmation_code'];
        
        // Kiểm tra mã xác nhận trong cơ sở dữ liệu
        $orderQuery = "SELECT confirmation_code FROM orderbooking WHERE order_id = '$order_id'";
        $order = oneRaw($orderQuery, "order_id = '$order_id'");
        
        if ($order && $order['confirmation_code'] === $confirmation_code) {
            // Cập nhật trạng thái đơn hàng thành 'checked_in'
            $dataUpdate = [
                'order_status' => 'checked_in'
            ];
            update('orderbooking', $dataUpdate, "order_id = $order_id");
            
            // Đặt flash message thông báo mã xác nhận đúng
            setFlashData('smg', 'Mã xác nhận đúng. Đã check in.');
            setFlashData('smg_type', 'success');
        } else {
            // Đặt flash message thông báo mã xác nhận sai
            setFlashData('smg', 'Mã xác nhận không đúng.');
            setFlashData('smg_type', 'danger');
        }
    }
    
    // Redirect đến trang danh sách đơn đặt phòng để hiển thị thông báo
    redirect('?modules=orderbooking&action=list');
} else {
    // Nếu không phải là phương thức POST, chuyển hướng người dùng đến trang danh sách đơn đặt phòng
    redirect('?modules=orderbooking&action=list');
}
?>