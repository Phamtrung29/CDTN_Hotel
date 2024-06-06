<?php
if (!defined('_CODE')) {
    die('Access denied....');
}

// Kiểm tra và include file header
$data = [
    'pageTitle' => 'Thêm Đơn Đặt Phòng'
];
layouts('header', $data);

// Xử lý khi người dùng submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $user_id = $_POST['user_id'];
    $room_id = $_POST['room_id'];
    $check_in_date = $_POST['check_in_date'];
    $check_out_date = $_POST['check_out_date'];
    $total_price = $_POST['total_price'];
    $order_status = $_POST['order_status'];
    $payment_method = $_POST['payment_method'];
    $payment_status = $_POST['payment_status'];
    $confirmation_code = $_POST['confirmation_code'];

    // Thực hiện thêm đơn đặt phòng vào cơ sở dữ liệu
    // Ví dụ sử dụng hàm insert() để thêm dữ liệu vào cơ sở dữ liệu
    $data = [
        'user_id' => $user_id,
        'room_id' => $room_id,
        'check_in_date' => $check_in_date,
        'check_out_date' => $check_out_date,
        'total_price' => $total_price,
        'order_status' => $order_status,
        'payment_method' => $payment_method,
        'payment_status' => $payment_status,
        'confirmation_code' => $confirmation_code
    ];

    $result = insert('orderbooking', $data);
    if ($result) {
        setFlashData('msg', 'Thêm đơn đặt phòng thành công!');
        setFlashData('msg_type', 'success');
        redirect('?modules=orderbooking&action=list');
    } else {
        setFlashData('msg', 'Thêm đơn đặt phòng thất bại!');
        setFlashData('msg_type', 'danger');
    }
}

// Hiển thị form thêm đơn đặt phòng
?>


<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php
        require_once('templaces/layout/sidebar.php');?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <?php
        require_once('templaces/layout/navbar.php');?>

            <div class="container-fluid">
                <h2>Thêm Đơn Đặt Phòng</h2>
                <form action="?modules=orderbooking&action=add" method="POST">
                    <!-- Các trường nhập liệu -->
                    <div class="form-group">
                        <label for="user_id">User ID:</label>
                        <input type="text" class="form-control" id="user_id" name="user_id" required>
                    </div>
                    <div class="form-group">
                        <label for="room_id">Room ID:</label>
                        <input type="text" class="form-control" id="room_id" name="room_id" required>
                    </div>
                    <div class="form-group">
                        <label for="check_in_date">Ngày Nhận Phòng:</label>
                        <input type="date" class="form-control" id="check_in_date" name="check_in_date" required>
                    </div>
                    <div class="form-group">
                        <label for="check_out_date">Ngày Trả Phòng:</label>
                        <input type="date" class="form-control" id="check_out_date" name="check_out_date" required>
                    </div>
                    <div class="form-group">
                        <label for="total_price">Tổng Giá:</label>
                        <input type="text" class="form-control" id="total_price" name="total_price" required>
                    </div>
                    <div class="form-group">
                        <label for="order_status">Trạng Thái Đơn Hàng:</label>
                        <input type="text" class="form-control" id="order_status" name="order_status" required>
                    </div>
                    <div class="form-group">
                        <label for="payment_method">Phương Thức Thanh Toán:</label>
                        <input type="text" class="form-control" id="payment_method" name="payment_method" required>
                    </div>
                    <div class="form-group">
                        <label for="payment_status">Trạng Thái Thanh Toán:</label>
                        <input type="text" class="form-control" id="payment_status" name="payment_status" required>
                    </div>
                    <div class="form-group">
                        <label for="confirmation_code">Mã Xác Nhận:</label>
                        <input type="text" class="form-control" id="confirmation_code" name="confirmation_code"
                            required>
                    </div>

                    <!-- Nút Submit -->
                    <button type="submit" class="btn btn-primary">Thêm Đơn Đặt Phòng</button>
                </form>
            </div>
        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="?modules=auth&action=logout">Logout</a>
                </div>
            </div>
        </div>
    </div>

</body>

<?php
// Kiểm tra và include file footer
layouts('footer');
?>