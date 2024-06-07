<?php
if (!defined('_CODE')) {
    die('Access denied....');
}

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isLogin()) {
    redirect('?modules=auth&action=login');
}

$data = [
    'pageTitle' => 'Order Detail'
];

layouts('header_lr', $data);

// Lấy order_id từ GET request
if (isset($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']);

    // Lấy thông tin về đơn đặt phòng từ bảng orderbooking
    $order = oneRaw("SELECT * FROM orderbooking WHERE order_id = $order_id");

    if (!empty($order)) {
        // Lấy thông tin về phòng từ bảng rooms
        $room_id = $order['room_id'];
        $booking_id = $order['booking_id'];

        $room = oneRaw("SELECT * FROM rooms WHERE room_id = $room_id");
        $booking = oneRaw("SELECT * FROM bookings WHERE booking_id = $booking_id");

        
        // Lấy thông tin về các dịch vụ liên quan đến đơn đặt phòng này
        $services = getRaw("SELECT services.* FROM services
                            INNER JOIN booking_services ON services.service_id = booking_services.service_id
                            WHERE booking_services.booking_id = $booking_id");
    } else {
        $order = null;
    }
} else {
    $order = null;
}

?>

<body>
    <div class="container-fluid bg-light p-5">
        <div class="container bg-white p-4 rounded shadow">
            <h1 class="text-center mb-5">Order Detail</h1>

            <?php if (!empty($order) && !empty($room)) : ?>
            <div class="row">
                <div class="col-md-6">
                    <img class="img-fluid" src="<?php echo _WEB_HOST_TEMPLACES ?>/<?php echo $room['image_url']; ?>"
                        alt="">
                </div>
                <div class="col-md-6">
                    <h3>Room Information</h3>
                    <p><strong>Room ID:</strong> <?php echo $room['room_id']; ?></p>
                    <p><strong>Name:</strong> <?php echo $booking['name'];?></p>
                    <p><strong>Price per Night:</strong> <?php echo $room['price_room']; ?></p>
                    <hr>
                    <h3>Booking Information</h3>
                    <p><strong>Order ID:</strong> <?php echo $order['order_id']; ?></p>
                    <p><strong>Total Price:</strong> <?php echo $order['total_price']; ?></p>
                    <p><strong>Check-in Date:</strong> <?php echo $order['check_in_date']; ?></p>
                    <p><strong>Check-out Date:</strong> <?php echo $order['check_out_date']; ?></p>
                    <p><strong>Order Status:</strong> <?php echo $order['order_status']; ?></p>
                    <p><strong>Payment Method:</strong> <?php echo $order['payment_method']; ?></p>
                    <p><strong>Payment Status:</strong> <?php echo $order['payment_status']; ?></p>
                    <hr>
                    <h3>Services</h3>
                    <?php if (!empty($services)) : ?>
                    <ul>
                        <?php foreach ($services as $service) : ?>
                        <li><?php echo $service['service_name']; ?> - <?php echo $service['price']; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else : ?>
                    <p>No additional services.</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php else : ?>
            <div class="alert alert-warning text-center" role="alert">
                Order detail is not available.
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