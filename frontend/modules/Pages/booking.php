<?php
if (!defined('_CODE')) {
    die('Access denied....');
}

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isLogin()) {
    redirect('?modules=auth&action=login');
}

$data = [
    'pageTitle' => 'Booking'
];

layouts('header_lr', $data);

$room_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$room = oneRaw("SELECT * FROM rooms WHERE room_id = $room_id");
$services = getRaw("SELECT * FROM services");

if (isPost()) {
    $user = oneRaw("SELECT * FROM tokenlogin");
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $checkin = isset($_POST['checkin']) ? $_POST['checkin'] : '';
    $checkout = isset($_POST['checkout']) ? $_POST['checkout'] : '';
    $special_request = isset($_POST['special_request']) ? trim($_POST['special_request']) : '';
    $selected_services = isset($_POST['services']) ? $_POST['services'] : [];

    $tokenLogin = getSession('tokenlogin'); 
    $queryToken = oneRaw("SELECT user_id FROM tokenlogin WHERE token = '$tokenLogin'");

    // Kiểm tra xem các trường bắt buộc đã được điền đầy đủ hay không
    if ($name && $phone && $checkin && $checkout) {
        // Truy vấn kiểm tra xem phòng đã được đặt trong khoảng thời gian này chưa
        $existing_booking = getRaw("SELECT * FROM bookings WHERE room_id = $room_id AND 
                                    (check_in_date <= '$checkout' AND check_out_date >= '$checkin')");

        if (!empty($existing_booking)) {
            echo "<script>alert('The room has already been booked for the selected dates. Please choose different dates.');</script>";
        } else {
            // Tính số đêm lưu trú
            $checkin_date = new DateTime($checkin);
            $checkout_date = new DateTime($checkout);
            $interval = $checkin_date->diff($checkout_date);
            $num_nights = $interval->days;

            // Tính tổng giá trị booking
            $total_price = $num_nights * $room['price_room'];

            // Thêm chi phí dịch vụ
            foreach ($selected_services as $service_id) {
                $service = oneRaw("SELECT * FROM services WHERE service_id = '$service_id'");
                if ($service) {
                    $total_price += $service['price'];
                }
            }

            // Chuẩn bị dữ liệu để chèn vào bảng booking
            $data = [
                'user_id' => $queryToken['user_id'], // Lấy user_id từ session
                'room_id' => $room_id,
                'name' => $name,
                'phone' => $phone,
                'check_in_date' => $checkin,
                'check_out_date' => $checkout,
                'total_price' => $total_price,
                'special_request' => $special_request
            ];

            // Chèn dữ liệu vào bảng booking
            if (insert('bookings', $data)) {
                $booking_id = $conn->lastInsertId(); // Lấy ID của booking vừa chèn

                // Chèn các dịch vụ đã chọn vào bảng booking_services
                foreach ($selected_services as $service_id) {
                    $service_data = [
                        'booking_id' => $booking_id,
                        'service_id' => $service_id
                    ];
                    insert('booking_services', $service_data);
                }
                echo "<script>alert('Booking successful!'); window.location.href='?modules=home&action=cart';</script>";
            } else {
                echo "<script>alert('Booking failed! Please try again.');</script>";
            }
        }
    } else {
        echo "<script>alert('Please fill in all required fields.');</script>";
    }
}
?>

<body>
    <div class="container-xxl bg-white p-0">

        <?php require_once('templaces/layout/pagehead.php'); ?>
        <?php require_once('templaces/layout/book.php'); ?>

        <!-- Booking Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                    <h6 class="section-title text-center text-primary text-uppercase">Room Booking</h6>
                    <h1 class="mb-5">Book A <span class="text-primary text-uppercase">Luxury Room</span></h1>
                </div>
                <div class="row g-5">
                    <div class="col-lg-6">
                        <div class="row g-3">
                            <div class="col-6 text-end">
                                <img class="img-fluid rounded w-75 wow zoomIn" data-wow-delay="0.1s"
                                    src="<?php echo _WEB_HOST_TEMPLACES ?>/img/about-1.jpg" style="margin-top: 25%;">
                            </div>
                            <div class="col-6 text-start">
                                <img class="img-fluid rounded w-100 wow zoomIn" data-wow-delay="0.3s"
                                    src="<?php echo _WEB_HOST_TEMPLACES ?>/img/about-2.jpg">
                            </div>
                            <div class="col-6 text-end">
                                <img class="img-fluid rounded w-50 wow zoomIn" data-wow-delay="0.5s"
                                    src="<?php echo _WEB_HOST_TEMPLACES ?>/img/about-3.jpg">
                            </div>
                            <div class="col-6 text-start">
                                <img class="img-fluid rounded w-75 wow zoomIn" data-wow-delay="0.7s"
                                    src="<?php echo _WEB_HOST_TEMPLACES ?>/img/about-4.jpg">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="wow fadeInUp" data-wow-delay="0.2s">
                            <form method="POST" action="">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <h1 class="mb-4">Phòng <span
                                                class="text-primary text-uppercase"><?php echo $room['room_number']; ?></span>
                                        </h1>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <p>Prices: <?php echo $room['price_room']; ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <p>Beds: <?php echo $room['beds']; ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <p>Bathrooms: <?php echo $room['bathrooms']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="Your Name" required>
                                            <label for="name">Your Name</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="phone" class="form-control" id="phone" name="phone"
                                                placeholder="Your Phone" required>
                                            <label for="phone">Your Phone</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating date" id="date3" data-target-input="nearest">
                                            <input type="date" class="form-control datetimepicker-input" id="checkin"
                                                name="checkin" placeholder="Check In" required>
                                            <label for="checkin">Check In</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating date" id="date4" data-target-input="nearest">
                                            <input type="date" class="form-control datetimepicker-input" id="checkout"
                                                name="checkout" placeholder="Check Out" required>
                                            <label for="checkout">Check Out</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <textarea class="form-control" placeholder="Special Request"
                                                id="special_request" name="special_request"
                                                style="height: 100px"></textarea>
                                            <label for="special_request">Special Request</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <?php
                                            if (!empty($services)) {
                                                echo '<div class="form-group">';
                                                echo '<label for="services">Select Additional Service:</label>';
                                                echo '<select class="form-control" id="services" name="services[]" multiple>';
                                                // Lưu ý thuộc tính multiple ở đây
                                                echo '<option value="">Select a service</option>';

                                                foreach ($services as $service) {
                                                    echo '<option value="' . $service['service_id'] . '">' . $service['service_name'] . '</option>';
                                                }

                                                echo '</select>';
                                                echo '</div>';
                                            } else {
                                                echo '<p>No services available.</p>';
                                            }
                                            ?>
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn-primary w-100 py-3" type="submit">Book Now</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Booking End -->

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>
</body>

<?php
layouts('footer', $data);
?>