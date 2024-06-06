<?php
if (!defined('_CODE')) {
    die('Access denied....');
}

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isLogin()) {
    redirect('?modules=auth&action=login');
}

$data = [
    'pageTitle' => 'Cart'
];
$tokenLogin = getSession('tokenlogin'); 
$queryToken = oneRaw("SELECT user_id FROM tokenlogin WHERE token = '$tokenLogin'");
$user_id = $queryToken['user_id'];

layouts('header_lr', $data);

// Lấy thông tin đặt hàng từ database dựa trên user_id
$bookings = getRaw("SELECT * FROM bookings WHERE user_id = $user_id AND process = 'no'");

?>

<body>
    <div class="container-fluid bg-light p-5">
        <div class="container bg-white p-4 rounded shadow">
            <h1 class="text-center mb-5">Your Booking Information</h1>

            <?php if (!empty($bookings)) : ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Room Image</th>
                            <th scope="col">Room ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Check-in Date</th>
                            <th scope="col">Check-out Date</th>
                            <th scope="col">Total Price</th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking) : ?>
                        <tr>
                            <td class="table-image">
                                <?php 
                                $room_id = $booking['room_id'];
                                // Lấy thông tin về các phòng từ bảng rooms
                                $room = oneRaw("SELECT * FROM rooms WHERE room_id = $room_id");
                                ?>
                                <img class="img-fluid centered-image"
                                    src="<?php echo _WEB_HOST_TEMPLACES?>/<?php echo $room['image_url']; ?>" alt="">
                            </td>
                            <td class="align-middle"><?php echo $booking['room_id']; ?></td>
                            <td class="align-middle"><?php echo $booking['name']; ?></td>
                            <td class="align-middle"><?php echo $booking['check_in_date']; ?></td>
                            <td class="align-middle"><?php echo $booking['check_out_date']; ?></td>
                            <td class="align-middle"><?php echo $booking['total_price']; ?></td>
                            <td class="align-middle">
                                <form action="??modules=home&action=cart_detail" method="POST">
                                    <input type="hidden" name="booking_id"
                                        value="<?php echo $booking['booking_id']; ?>">
                                    <button type="submit">Details</button>
                                </form>
                            </td>
                            <td class="align-middle">
                                <form action="?modules=payments&action=payment" method="POST">
                                    <input type="hidden" name="booking_id"
                                        value="<?php echo $booking['booking_id']; ?>">
                                    <button type="submit">Payments</button>
                                </form>
                            </td>
                            <td class="align-middle">
                                <form action="?modules=home&action=remove_cart" method="POST">
                                    <input type="hidden" name="booking_id"
                                        value="<?php echo $booking['booking_id']; ?>">
                                    <button type="submit">Delete</button>
                                </form>
                            </td>

                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else : ?>
            <div class="alert alert-warning text-center" role="alert">
                No booking information available.
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