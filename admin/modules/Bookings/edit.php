<?php
if (!defined('_CODE')) {
    die('Access denied....');
}

$data = [
    'pageTitle' => 'Sửa đặt phòng'
];
layouts('header', $data);

$bookingId = isset($_GET['id']) ? $_GET['id'] : 0;
$booking = oneRaw("SELECT * FROM bookings WHERE booking_id = $bookingId");

if (!$booking) {
    setFlashData('smg', 'Đặt phòng không tồn tại.');
    setFlashData('smg_type', 'danger');
    redirect('?page=Bookings/list');
}

if (isPost()) {
    $filterAll = filter();
    $error = [];

    // Validate dữ liệu nhập vào
    if (empty($filterAll['user_id'])) {
        $error['user_id']['require'] = 'User ID bắt buộc phải nhập.';
    }
    if (empty($filterAll['room_id'])) {
        $error['room_id']['require'] = 'Room ID bắt buộc phải nhập.';
    }
    if (empty($filterAll['name'])) {
        $error['name']['require'] = 'Tên bắt buộc phải nhập.';
    }
    if (empty($filterAll['phone'])) {
        $error['phone']['require'] = 'Điện thoại bắt buộc phải nhập.';
    }
    if (empty($filterAll['check_in_date'])) {
        $error['check_in_date']['require'] = 'Ngày nhận phòng bắt buộc phải nhập.';
    }
    if (empty($filterAll['check_out_date'])) {
        $error['check_out_date']['require'] = 'Ngày trả phòng bắt buộc phải nhập.';
    }
    if (empty($filterAll['total_price'])) {
        $error['total_price']['require'] = 'Tổng giá bắt buộc phải nhập.';
    }

    if (empty($error)) {
        $dataUpdate = [
            'user_id' => $filterAll['user_id'],
            'room_id' => $filterAll['room_id'],
            'name' => $filterAll['name'],
            'phone' => $filterAll['phone'],
            'check_in_date' => $filterAll['check_in_date'],
            'check_out_date' => $filterAll['check_out_date'],
            'total_price' => $filterAll['total_price'],
            'special_request' => $filterAll['special_request'],
        ];

        $updateStatus = update('bookings', $dataUpdate, "booking_id = $bookingId");
        if ($updateStatus) {
            setFlashData('smg', 'Sửa đặt phòng thành công!');
            setFlashData('smg_type', 'success');
            redirect('?page=Bookings/list');
        } else {
            setFlashData('smg', 'Sửa đặt phòng không thành công!');
            setFlashData('smg_type', 'danger');
            redirect('?modules=Bookings&action=edit&id=' . $bookingId);
        }
    } else {
        setFlashData('smg', 'Vui lòng kiểm tra lại dữ liệu!');
        setFlashData('smg_type', 'danger');
        setFlashData('error', $error);
        setFlashData('old', $filterAll);
        redirect('?modules=Bookings&action=edit&id=' . $bookingId);
    }
}

$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
$error = getFlashData('error');
$old = getFlashData('old');

if (!$old) {
    $old = $booking;
}
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

            <div class="row">
                <div class="col-4 mg-home" style="margin: 50px auto;">
                    <h2 class="text-center text-uppercase">Sửa đặt phòng</h2>
                    <?php
        if (!empty($smg)) {
            echo "<div class='alert alert-$smg_type'>$smg</div>";
        }
        ?>
                    <form action="" method="post">
                        <div class="form-group mg-form">
                            <label for="">User ID</label>
                            <input name="user_id" type="text" class="form-control" placeholder="User ID"
                                value="<?php echo old('user_id', $old); ?>">
                            <?php
                echo form_error('user_id', '<span class="error">', '</span>', $error);
                ?>
                        </div>
                        <div class="form-group mg-form">
                            <label for="">Room ID</label>
                            <input name="room_id" type="text" class="form-control" placeholder="Room ID"
                                value="<?php echo old('room_id', $old); ?>">
                            <?php
                echo form_error('room_id', '<span class="error">', '</span>', $error);
                ?>
                        </div>
                        <div class="form-group mg-form">
                            <label for="">Tên</label>
                            <input name="name" type="text" class="form-control" placeholder="Tên"
                                value="<?php echo old('name', $old); ?>">
                            <?php
                echo form_error('name', '<span class="error">', '</span>', $error);
                ?>
                        </div>
                        <div class="form-group mg-form">
                            <label for="">Điện Thoại</label>
                            <input name="phone" type="text" class="form-control" placeholder="Điện Thoại"
                                value="<?php echo old('phone', $old); ?>">
                            <?php
                echo form_error('phone', '<span class="error">', '</span>', $error);
                ?>
                        </div>
                        <div class="form-group mg-form">
                            <label for="">Ngày Nhận Phòng</label>
                            <input name="check_in_date" type="date" class="form-control"
                                value="<?php echo old('check_in_date', $old); ?>">
                            <?php
                echo form_error('check_in_date', '<span class="error">', '</span>', $error);
                ?>
                        </div>
                        <div class="form-group mg-form">
                            <label for="">Ngày Trả Phòng</label>
                            <input name="check_out_date" type="date" class="form-control"
                                value="<?php echo old('check_out_date', $old); ?>">
                            <?php
                echo form_error('check_out_date', '<span class="error">', '</span>', $error);
                ?>
                        </div>
                        <div class="form-group mg-form">
                            <label for="">Tổng Giá</label>
                            <input name="total_price" type="text" class="form-control" placeholder="Tổng Giá"
                                value="<?php echo old('total_price', $old); ?>">
                            <?php
                echo form_error('total_price', '<span class="error">', '</span>', $error);
                ?>
                        </div>
                        <div class="form-group mg-form">
                            <label for="">Yêu Cầu Đặc Biệt</label>
                            <textarea name="special_request" class="form-control"
                                placeholder="Yêu Cầu Đặc Biệt"><?php echo old('special_request', $old); ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Sửa Đặt Phòng</button>
                    </form>
                </div>
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
layouts('footer');
?>