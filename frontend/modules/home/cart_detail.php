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
$booking_id = isset($_POST['booking_id']) ? intval($_POST['booking_id']) : 0;

$booking = null;
$room = null;

if ($booking_id > 0) {
    $booking = oneRaw("SELECT * FROM bookings
    WHERE booking_id = $booking_id AND user_id = $user_id");

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

$all_services = getRaw("SELECT * FROM services");

// Lưu dịch vụ mới vào cơ sở dữ liệu nếu có dữ liệu được gửi từ form
if (isPost()) {
    $selected_services = isset($_POST['services']) ? $_POST['services'] : [];

    // Lấy thông tin chi tiết của đơn đặt phòng
    $booking = oneRaw("SELECT * FROM bookings WHERE booking_id = $booking_id AND user_id = $user_id");
    if (!$booking) {
        echo "<script>alert('Booking not found!'); window.location.href='?modules=home&action=cart';</script>";
        exit();
    }

    $total_price = $booking['total_price'];
    // Thêm chi phí dịch vụ
    foreach ($selected_services as $service_id) {
        $service = oneRaw("SELECT * FROM services WHERE service_id = '$service_id'");
        if ($service) {
            $total_price += $service['price'];
        }
    }
    $data1 = [
        'total_price' => $total_price
    ];

    // Cập nhật tổng giá trị của đơn đặt phòng
    $updated = update('bookings', $data1, "booking_id = '$booking_id'");
    if ($updated) {
        // Chèn các dịch vụ đã chọn vào bảng booking_services
        foreach ($selected_services as $service_id) {
            $service_data = [
                'booking_id' => $booking_id,
                'service_id' => $service_id
            ];
            insert('booking_services', $service_data);
            echo "<script>alert('Add service successful!'); window.location.href='?modules=home&action=cart';</script>";
        }
    } else {
        echo "<script>alert('Add service failed! Please try again.');</script>";
    }
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
                    <p><strong>Name:</strong> <?php echo $booking['name']; ?></p>
                    <p><strong>Price per Night:</strong> <?php echo $room['price_room']; ?></p>
                    <hr>
                    <h3>Booking Information</h3>
                    <p><strong>Booking ID:</strong> <?php echo $booking['booking_id']; ?></p>
                    <p><strong>Check-in Date:</strong> <?php echo $booking['check_in_date']; ?></p>
                    <p><strong>Check-out Date:</strong> <?php echo $booking['check_out_date']; ?></p>
                    <p><strong>Total Price:</strong> <?php echo $booking['total_price']; ?></p>
                    <?php foreach($services as $service): ?>
                    <p><strong>Service:</strong> <?php echo $service['service_name']; ?> -
                        <?php echo $service['price']; ?></p>
                    <?php endforeach ?>
                </div>
                <form action="" method="POST">
                    <?php
                    if (!empty($all_services)) {
                        echo '<div class="form-group">';
                        echo '<label for="services">Select Additional Service:</label>';
                        echo '<select class="form-control" id="services" name="services[]" multiple>';
                        // Lưu ý thuộc tính multiple ở đây
                        echo '<option value="">Select a service</option>';

                        foreach ($all_services as $service) {
                            echo '<option value="' . $service['service_id'] . '">' . $service['service_name'] . '</option>';
                        }

                        echo '</select>';
                        echo '</div>';
                    } else {
                        echo '<p>No services available.</p>';
                    }
                    ?>
                    <input type="hidden" name="booking_id" value="<?php echo $booking['booking_id']; ?>">
                    <button class="btn btn-primary w-2 py-2 mt-2" type="submit">Add Now</button>
                    <div>
                        <a href="?modules=home&action=cart" class="btn-return">Return</a>
                    </div>
                </form>
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