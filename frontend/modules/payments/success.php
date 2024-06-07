<?php
if (!defined('_CODE')) {
    die('Access denied....');
}

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isLogin()) {
    redirect('?modules=auth&action=login');
}

if (!isset($_GET['session_id']) || !isset($_GET['booking_id'])) {
    die('Thiếu thông tin cần thiết.');
}

require 'C:/xampp/htdocs/web_fullstack/manager_users/frontend/vendor/autoload.php';
\Stripe\Stripe::setApiKey('sk_test_51PI4sXEykVZyeagGLLqMXLbwE03khskI3bKTLQRbypZ5g6DnSags0G5iGS7ezLnseB3By7Gd3wg8Sfdcu7HcXi9900xVTlg9uf');

$session_id = $_GET['session_id'];
$booking_id = $_GET['booking_id'];

// Lấy thông tin từ phiên thanh toán Stripe
try {
    $session = \Stripe\Checkout\Session::retrieve($session_id);
} catch (Exception $e) {
    die('Không thể lấy thông tin session: ' . $e->getMessage());
}

// Lấy thông tin chi tiết từ payment_intent
try {
    $payment_intent = \Stripe\PaymentIntent::retrieve($session->payment_intent);
} catch (Exception $e) {
    die('Không thể lấy thông tin payment intent: ' . $e->getMessage());
}

$tokenLogin = getSession('tokenlogin'); 
$queryToken = oneRaw("SELECT user_id FROM tokenlogin WHERE token = '$tokenLogin'");
$user_id = $queryToken['user_id'];
$ifuser = oneRaw("SELECT * FROM users WHERE id='$user_id'");

$booking = oneRaw("SELECT * FROM bookings WHERE booking_id = $booking_id AND user_id = $user_id");

if ($booking) {
    // Lưu thông tin giao dịch vào bảng orderbooking
    echo "Thanh toán thành công. Mã giao dịch: " . $payment_intent->id;
    echo "<a href='?modules=home&action=dashboard'>Return</a>";

    $orderData = [
        'user_id' => $user_id,
        'room_id' => $booking['room_id'],
        'check_in_date' => $booking['check_in_date'],
        'check_out_date' => $booking['check_out_date'],
        'total_price' => $payment_intent->amount_received / 100, // Chuyển đổi từ cents sang dollars
        'order_status' => 'Pending',
        'payment_method' => 'card',
        'payment_status' => 'paid',
        'booking_id' => $booking_id
    ];
    $dataud = [
        'process' => 'yes'
    ];
    $update_booking = update('bookings', $dataud,"booking_id='$booking_id'");
    $insert_order = insert('orderbooking', $orderData);

    if ($insert_order && $update_booking) {
        $subject = $ifuser['fullname'] . ' Đặt phòng thành công!!';
        $content = 'Chào ' . $ifuser['fullname'] . '.<br>';
        $content .= 'Cảm ơn bạn đã đặt phòng của chúng tôi!<br>';
        $content .= 'Phòng: ' . $booking['room_id'] . '.<br>';
        $content .= 'Trân trọng cảm ơn';
        $message = "Đơn hàng mới đã được tạo thành công.";

        $alert_data = [
                'message' => $message,
                'status' => 'new'
        ];

        insert('alerts', $alert_data);

        $sendEmail = sendMail($ifuser['email'], $subject, $content);


        if ($sendEmail) {
            echo "<script>alert('Order and email sent successfully!');</script>";
        } else {
            echo "<script>alert('Order was successful, but email sending failed.');</script>";
        }
    } else {
        echo "<script>alert('Order failed! Please try again.');</script>";
    }
} else {
    echo "<script>alert('Booking not found or you do not have permission to view this booking.');</script>";
}
?>
<div>
    Welcome to hotel check in room: <div><a href="?modules=home&action=myroom">My room</a></div>
</div>