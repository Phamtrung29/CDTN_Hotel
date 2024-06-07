<?php
if (!defined('_CODE')) {
    die('Access denied....');
}

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isLogin()) {
    redirect('?modules=auth&action=login');
}

$data = [
    'pageTitle' => 'My room'
];
$tokenLogin = getSession('tokenlogin'); 
$queryToken = oneRaw("SELECT user_id FROM tokenlogin WHERE token = '$tokenLogin'");
$user_id = $queryToken['user_id'];

layouts('header_lr', $data);

// Lấy danh sách các đơn đặt phòng từ database dựa trên user_id
$status = isset($_GET['status']) ? $_GET['status'] : 'all';
$condition = ($status === 'all') ? "" : "AND order_status = '$status'";
$orders = getRaw("SELECT * FROM orderbooking WHERE user_id = $user_id $condition");

?>

<body>
    <div class="container-fluid bg-light p-5">
        <div class="container bg-white p-4 rounded shadow">
            <h1 class="text-center mb-5">My room</h1>

            <nav class="mb-4">
                <a href="?modules=home&action=myroom&status=all" class="btn btn-outline-primary">All</a>
                <a href="?modules=home&action=myroom&status=pending" class="btn btn-outline-warning">Pending</a>
                <a href="?modules=home&action=myroom&status=confirmed" class="btn btn-outline-success">Confirmed</a>
                <a href="?modules=home&action=myroom&status=checked_in" class="btn btn-outline-info">Checked In</a>
                <a href="?modules=home&action=myroom&status=completed" class="btn btn-outline-secondary">Completed</a>
                <a href="?modules=home&action=myroom&status=cancelled" class="btn btn-outline-danger">Cancelled</a>
            </nav>

            <h2 class="text-center mb-4">
                <?php 
                    switch ($status) {
                        case 'pending':
                            echo "Pending Bookings";
                            break;
                        case 'confirmed':
                            echo "Confirmed Bookings";
                            break;
                        case 'checked_in':
                            echo "Checked In Bookings";
                            break;
                        case 'completed':
                            echo "Completed Bookings";
                            break;
                        case 'cancelled':
                            echo "Cancelled Bookings";
                            break;
                        default:
                            echo "All Bookings";
                    }
                ?>
            </h2>
            <?php if (!empty($orders)) : ?>
            <?php foreach ($orders as $order) : ?>
            <?php
                    // Lấy thông tin về phòng từ bảng rooms
                    $room_id = $order['room_id'];
                    $room = oneRaw("SELECT * FROM rooms WHERE room_id = $room_id");
                ?>
            <div class="row mb-4">
                <div class="col-md-6">
                    <img class="img-fluid" src="<?php echo _WEB_HOST_TEMPLACES ?>/<?php echo $room['image_url']; ?>"
                        alt="">
                </div>
                <div class="col-md-6">
                    <h3>Room Information</h3>
                    <p><strong>Room ID:</strong> <?php echo $room['room_id']; ?></p>
                    <p><strong>Total price:</strong> <?php echo $order['total_price']; ?></p>
                    <p><strong>Check in date:</strong> <?php echo $order['check_in_date']; ?></p>
                    <p><strong>Check out date:</strong> <?php echo $order['check_out_date']; ?></p>
                    <p><strong>Order status:</strong> <?php echo $order['order_status']; ?></p>
                    <p><strong>Payment method:</strong> <?php echo $order['payment_method']; ?></p>
                    <p><strong>Payment status:</strong> <?php echo $order['payment_status']; ?></p>
                    <a href="?modules=home&action=order_detail&order_id=<?php echo $order['order_id']; ?>"
                        class="btn btn-info">Order Details</a>
                </div>
                <?php if ($status === 'confirmed') : ?>
                <div class="df" style="display: flex; gap: 10px;">
                    <form action="?modules=home&action=checkin" method="POST">
                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                        <button type="submit" class="btn btn-primary btn-sm">Check-in</button>
                    </form>
                    <form action="?modules=home&action=cancelroom" method="POST">
                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
            <hr>
            <?php endforeach; ?>
            <?php else : ?>
            <div class="alert alert-warning text-center" role="alert">
                No <?php echo strtolower($status); ?> booking information available.
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