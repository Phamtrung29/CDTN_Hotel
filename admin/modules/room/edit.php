<?php
if (!defined('_CODE')) {
    die('Access denied....');
}
$data = [
    'pageTitle' => 'Sửa thông tin phòng'
];
layouts('header', $data);
$room_id =  $_GET['id'];
$room = oneRaw("SELECT * FROM rooms WHERE room_id = $room_id");

if (isPost()) {
    $filterAll = filter();
    $error = [];

    // Validate dữ liệu nhập vào
    if (empty($filterAll['room_number'])) {
        $error['room_number']['require'] = 'Số phòng bắt buộc phải nhập.';
    }
    if (empty($filterAll['type_id'])) {
        $error['type_id']['require'] = 'Loại phòng bắt buộc phải chọn.';
    }
    if (empty($filterAll['price_room'])) {
        $error['price_room']['require'] = 'Giá phòng bắt buộc phải nhập.';
    }
    if (empty($filterAll['beds'])) {
        $error['beds']['require'] = 'Số giường bắt buộc phải nhập.';
    }
    if (empty($filterAll['bathrooms'])) {
        $error['bathrooms']['require'] = 'Số phòng tắm bắt buộc phải nhập.';
    }

    if (empty($error)) {
        $dataUpdate = [
            'room_number' => $filterAll['room_number'],
            'type_id' => $filterAll['type_id'],
            'price_room' => $filterAll['price_room'],
            'image_url' => $filterAll['image_url'],
            'beds' => $filterAll['beds'],
            'bathrooms' => $filterAll['bathrooms'],
            'status' => $filterAll['status'],
        ];

        $updateStatus = update('rooms', $dataUpdate, "room_id = $room_id");
        if ($updateStatus) {
            setFlashData('smg', 'Sửa phòng thành công!');
            setFlashData('smg_type', 'success');
            redirect('?page=room/list');
        } else {
            setFlashData('smg', 'Sửa phòng không thành công!');
            setFlashData('smg_type', 'danger');
            redirect('?modules=room7action=edit&id=' . $room_id);
        }
    } else {
        setFlashData('smg', 'Vui lòng kiểm tra lại dữ liệu!');
        setFlashData('smg_type', 'danger');
        setFlashData('error', $error);
        setFlashData('old', $filterAll);
        redirect('?modules=room7action=edit&id=' . $room_id);
    }
}

$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
$error = getFlashData('error');
$old = getFlashData('old');

if (!$old) {
    $old = $room;
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

                        <!-- End of Main Content -->
                        <div class="row">
                            <div class="col-4 mg-home" style="margin: 50px auto;">
                                <h2 class="text-center text-uppercase">Sửa phòng</h2>
                                <?php
        if (!empty($smg)) {
            echo "<div class='alert alert-$smg_type'>$smg</div>";
        }
        ?>
                                <form action="" method="post">
                                    <div class="form-group mg-form">
                                        <label for="">Số Phòng</label>
                                        <input name="room_number" type="text" class="form-control"
                                            placeholder="Số Phòng" value="<?php echo old('room_number', $old); ?>">
                                        <?php
                echo form_error('room_number', '<span class="error">', '</span>', $error);
                ?>
                                    </div>
                                    <div class="form-group mg-form">
                                        <label for="">Loại Phòng</label>
                                        <select name="type_id" class="form-control">
                                            <!-- Duyệt qua các loại phòng -->
                                            <?php
                    $listTypeRoom = getRaw("SELECT * FROM typeroom");
                    foreach ($listTypeRoom as $typeRoom):
                    ?>
                                            <option value="<?php echo $typeRoom['type_id']; ?>"
                                                <?php echo (old('type_id', $old) == $typeRoom['type_id']) ? 'selected' : ''; ?>>
                                                <?php echo $typeRoom['type_name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php
                echo form_error('type_id', '<span class="error">', '</span>', $error);
                ?>
                                    </div>
                                    <div class="form-group mg-form">
                                        <label for="">Giá Phòng</label>
                                        <input name="price_room" type="text" class="form-control"
                                            placeholder="Giá Phòng" value="<?php echo old('price_room', $old); ?>">
                                        <?php
                echo form_error('price_room', '<span class="error">', '</span>', $error);
                ?>
                                    </div>
                                    <div class="form-group mg-form">
                                        <label for="">Hình Ảnh</label>
                                        <input name="image_url" type="text" class="form-control"
                                            placeholder="Đường dẫn hình ảnh"
                                            value="<?php echo old('image_url', $old); ?>">
                                    </div>
                                    <div class="form-group mg-form">
                                        <label for="">Số Giường</label>
                                        <input name="beds" type="text" class="form-control" placeholder="Số Giường"
                                            value="<?php echo old('beds', $old); ?>">
                                        <?php
                echo form_error('beds', '<span class="error">', '</span>', $error);
                ?>
                                    </div>
                                    <div class="form-group mg-form">
                                        <label for="">Số Phòng Tắm</label>
                                        <input name="bathrooms" type="text" class="form-control"
                                            placeholder="Số Phòng Tắm" value="<?php echo old('bathrooms', $old); ?>">
                                        <?php
                echo form_error('bathrooms', '<span class="error">', '</span>', $error);
                ?>
                                    </div>
                                    <div class="form-group mg-form">
                                        <label for="">Trạng Thái</label>
                                        <select name="status" class="form-control">
                                            <option value="available"
                                                <?php echo (old('status', $old) == 'available') ? 'selected' : ''; ?>>
                                                Available</option>
                                            <option value="booked"
                                                <?php echo (old('status', $old) == 'booked') ? 'selected' : ''; ?>>
                                                Booked</option>
                                            <option value="maintenance"
                                                <?php echo (old('status', $old) == 'maintenance') ? 'selected' : ''; ?>>
                                                Maintenance</option>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Sửa Phòng</button>
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
                            <div class="modal-body">Select "Logout" below if you are ready to end your current session.
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                <a class="btn btn-primary" href="?modules=auth&action=logout">Logout</a>
                            </div>
                        </div>
                    </div>
                </div>

            </body>
            <!-- End of Main Content -->


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

<?php layouts('footer');?>