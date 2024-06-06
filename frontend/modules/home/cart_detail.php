<?php
if (!defined('_CODE')) {
    die('Access denied....');
}

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isLogin()) {
    redirect('?modules=auth&action=login');
}

$data = [
    'pageTitle' => 'Booking Detail'
];
$tokenLogin = getSession('tokenlogin'); 
$queryToken = oneRaw("SELECT user_id FROM tokenlogin WHERE token = '$tokenLogin'");
$user_id = $queryToken['user_id'];

layouts('header_lr', $data);

// Lấy booking_id từ POST request
if (isset($_POST['booking_id'])) {
    $booking_id = intval($_POST['booking_id']);

    $booking = oneRaw("SELECT * FROM bookings
    INNER JOIN booking_services ON bookings.booking_id = booking_services.booking_id
    INNER JOIN services ON services.service_id = booking_services.service_id
    WHERE bookings.booking_id = $booking_id AND bookings.user_id = $user_id");


    if (!empty($booking)) {
        // Lấy thông tin về phòng từ bảng rooms
        $room_id = $booking['room_id'];
        $room = oneRaw("SELECT * FROM rooms WHERE room_id = $room_id");
         // Lấy tất cả các dịch vụ liên quan đến đặt phòng này
         $services = getRaw("SELECT services.* FROM services
         INNER JOIN booking_services ON services.service_id = booking_services.service_id
         WHERE booking_services.booking_id = $booking_id");
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
            <h1 class="text-center mb-5">Booking Detail</h1>

            <?php if (!empty($booking) && !empty($room)) : ?>
            <div class="row">
                <div class="col-md-6">
                    <img class="img-fluid" src="<?php echo _WEB_HOST_TEMPLACES ?>/<?php echo $room['image_url']; ?>"
                        alt="">
                </div>
                <div class="col-md-6">
                    <h3>Room Information</h3>
                    <p><strong>Room ID:</strong> <?php echo $room['room_id']; ?></p>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($booking['name']); ?></p>
                    <p><strong>Price per Night:</strong> <?php echo $room['price_room']; ?></p>
                    <hr>
                    <h3>Booking Information</h3>
                    <p><strong>Booking ID:</strong> <?php echo $booking['booking_id']; ?></p>
                    <p><strong>Check-in Date:</strong> <?php echo $booking['check_in_date']; ?></p>
                    <p><strong>Check-out Date:</strong> <?php echo $booking['check_out_date']; ?></p>
                    <p><strong>Total Price:</strong> <?php echo $booking['total_price']; ?></p>
                    <?php foreach($services as $service): ?>
                    <p><strong>Dịch vụ:</strong> <?php echo $service['service_id']; ?></p>
                    <?php endforeach ?>

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