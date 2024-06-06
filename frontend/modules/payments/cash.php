<?php
if (!defined('_CODE')) {
    die('Access denied....');
}

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isLogin()) {
    redirect('?modules=auth&action=login');
}

if (isPost()) {
    // Lấy thông tin user_id từ tokenlogin
    $tokenLogin = getSession('tokenlogin'); 
    $queryToken = oneRaw("SELECT user_id FROM tokenlogin WHERE token = '$tokenLogin'");
    $user_id = $queryToken['user_id'];
    $ifuser = oneRaw("SELECT * FROM users WHERE id='$user_id'");

    // Lấy booking_id từ POST request
    $booking_id = isset($_POST['booking_id']) ? intval($_POST['booking_id']) : 0;

    // Lấy thông tin booking từ cơ sở dữ liệu
    $booking = oneRaw("SELECT * FROM bookings WHERE booking_id = $booking_id AND user_id = $user_id");

    if ($booking) {
        // Lưu thông tin giao dịch vào bảng orderbooking
        echo "Thanh toán thành công. Mã giao dịch: ";
        echo "<a href='?modules=home&action=dashboard'>Return</a>";

        $orderData = [
            'user_id' => $user_id,
            'room_id' => $booking['room_id'],
            'check_in_date' => $booking['check_in_date'],
            'check_out_date' => $booking['check_out_date'],
            'total_price' => $booking['total_price'], // Chuyển đổi từ cents sang dollars nếu cần
            'order_status' => 'Pending',
            'payment_method' => 'cash',
        ];

        $dataud = [
            'process' => 'yes'
        ];
        $update_booking = update('bookings', $dataud,"booking_id='$booking_id'");

        $insert_order = insert('orderbooking', $orderData);

        if($insert_order && $update_booking){
            $subject = $ifuser['fullname'] . ' Đặt phòng thành công!!';
            $content = 'Chào ' . $ifuser['fullname'] . '.<br>';
            $content .= 'Cảm ơn bạn đã đặt phòng của chúng tôi!<br>';
            $content .= 'Phòng: ' . $booking['room_id'] . '.<br>';
            $content .= 'Trân trọng cảm ơn';
            $message = "Đơn hàng mới đã được tạo thành công.";
            $sendEmail = sendMail($ifuser['email'], $subject, $content);
            $alert_data = [
                'message' => $message,
                'status' => 'new'
        ];

        insert('alerts', $alert_data);
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
}
?>
<div>
    Welcome to hotel check in room: <div><a href="?modules=home&action=myroom">My room</a></div>
</div>