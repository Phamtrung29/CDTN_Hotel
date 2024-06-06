<?php
// udprofile.php

if (!defined('_CODE')) {
    define('_CODE', true);
}

// Kiểm tra xem người dùng đã submit form cập nhật thông tin chưa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Nhận dữ liệu từ form
    $fullname = $_POST['fullname'];
    $date_of_birth = $_POST['date_of_birth'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];


    $tokenLogin = getSession('tokenlogin'); 
    $queryToken = oneRaw("SELECT user_id FROM tokenlogin WHERE token = '$tokenLogin'");
    $user_id = $queryToken['user_id'];
    // Tạo mảng dữ liệu cập nhật
    $dataUpdate = [
        'fullname' => $fullname,
        'date_of_birth' => $date_of_birth,
        'address' => $address,
        'phone_number' => $phone_number
    ];

    // Thực hiện cập nhật vào cơ sở dữ liệu
    update('profile', $dataUpdate, "user_id = $user_id");

    // Redirect đến trang profile để hiển thị thông tin cập nhật
    redirect('?modules=admin&action=profile');
} else {
    // Nếu không phải là phương thức POST, chuyển hướng người dùng đến trang chính
    redirect('?modules=admin&action=profile');
}
?>