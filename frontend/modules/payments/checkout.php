<?php if (!defined('_CODE')) {
    die('Access denied....');
}

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isLogin()) {
    redirect('?modules=auth&action=login');
}

require 'C:/xampp/htdocs/web_fullstack/manager_users/frontend/vendor/autoload.php';



\Stripe\Stripe::setApiKey('sk_test_51PI4sXEykVZyeagGLLqMXLbwE03khskI3bKTLQRbypZ5g6DnSags0G5iGS7ezLnseB3By7Gd3wg8Sfdcu7HcXi9900xVTlg9uf');


if (isPost()) {
    // Lấy thông tin user_id từ tokenlogin
    $tokenLogin = getSession('tokenlogin'); 
    $queryToken = oneRaw("SELECT user_id FROM tokenlogin WHERE token = '$tokenLogin'");
    $user_id = $queryToken['user_id'];
    
    // Lấy booking_id từ POST request
    $booking_id = isset($_POST['booking_id']) ? intval($_POST['booking_id']) : 0;
    
    // Lấy thông tin booking từ cơ sở dữ liệu
    $booking = oneRaw("SELECT * FROM bookings WHERE booking_id = $booking_id AND user_id = $user_id");

    if (!$booking) {
        die('Invalid booking.');    
    }

    // Tính toán tổng số tiền
    $total_price = $booking['total_price'];

    // Tạo phiên thanh toán
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => 'Room Booking',
                ],
                'unit_amount' => $total_price * 100, // Số tiền tính bằng cents
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => 'http://localhost/web_fullstack/manager_users/frontend/?modules=payments&action=success&session_id={CHECKOUT_SESSION_ID}&booking_id=' . $booking_id,
        'cancel_url' => 'http://localhost/web_fullstack/manager_users/frontend/?modules=payments&action=cancel',
    ]);
    
    // Chuyển hướng đến URL thanh toán của Stripe
    header("Location: " . $session->url);
    exit;
}