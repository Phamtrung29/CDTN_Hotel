<?php
if (!defined('_CODE')) {
    die('Access denied....');
}

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isLogin()) {
    redirect('?modules=auth&action=login');
}

$data = [
    'pageTitle' => 'Payments'
];
$tokenLogin = getSession('tokenlogin'); 
$queryToken = oneRaw("SELECT user_id FROM tokenlogin WHERE token = '$tokenLogin'");
$user_id = $queryToken['user_id'];

layouts('header_lr', $data);

// Lấy booking_id từ POST request
if (isset($_POST['booking_id'])) {
    $booking_id = intval($_POST['booking_id']);

    // Lấy thông tin chi tiết đặt phòng từ database dựa trên booking_id
    $booking = oneRaw("SELECT * FROM bookings  WHERE booking_id = $booking_id AND user_id = $user_id");

    if (!empty($booking)) {
        // Lấy thông tin về phòng từ bảng rooms
        $room_id = $booking['room_id'];
        $room = oneRaw("SELECT * FROM rooms WHERE room_id = $room_id");
    } else {
        $booking = null;
    }
} else {
    $booking = null;
}

?>

<body>
    <div class="container-fluid bg-light p-5">
        <div class="container bg-white p-4 rounded shadow">
            <h1 class="text-center mb-5">Payment</h1>

            <?php if (!empty($booking) && !empty($room)) : ?>
            <div class="row">
                <div class="col-md-6">
                    <img class="img-fluid" src="<?php echo _WEB_HOST_TEMPLACES ?>/<?php echo $room['image_url']; ?>"
                        alt="">
                </div>
                <div class="col-md-6">
                    <h3>Booking Information</h3>
                    <p><strong>Booking ID:</strong> <?php echo $booking['booking_id']; ?></p>
                    <p><strong>Check-in Date:</strong> <?php echo $booking['check_in_date']; ?></p>
                    <p><strong>Check-out Date:</strong> <?php echo $booking['check_out_date']; ?></p>
                    <p><strong>Total Price:</strong> <?php echo $booking['total_price']; ?></p>
                    <div class="df" style=" display: flex; gap: 10px;">

                        <form action="?modules=payments&action=checkout" method="POST">
                            <input type="hidden" name="booking_id" value="<?php echo $booking['booking_id']; ?>">
                            <button type="submit">Payment card</button>
                        </form>
                        <form action="?modules=payments&action=cash" method="POST">
                            <input type="hidden" name="booking_id" value="<?php echo $booking['booking_id']; ?>">
                            <button type="submit">Payment cash</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php else : ?>
            <div class="alert alert-warning text-center" role="alert">
                Booking detail is not available.
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
</body>

<?php
layouts('footer', $data);
?>