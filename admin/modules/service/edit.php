<?php
if(!defined('_CODE')){
    die('Access denied....');
}
$data = [
    'pageTitle' => 'Sửa dịch vụ'
];
layouts('header', $data);


$service_id = $_GET['id'];
$type = oneRaw("SELECT * FROM services WHERE service_id = $service_id");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $service_name = $_POST['service_name'];
    $description = $_POST['description'];

    $dataUpdate = [
        'service_name' => $service_name,
        'description' => $description
    ];

    $result = update('services', $dataUpdate, "service_id = $service_id");
    if ($result) {
        setFlashData('msg', 'Cập nhật loại phòng thành công!');
        setFlashData('msg_type', 'success');
        redirect('?page=service/list');
    } else {
        setFlashData('msg', 'Cập nhật loại phòng thất bại!');
        setFlashData('msg_type', 'danger');
    }
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
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

            <div class="container">
                <h2>Sửa dịch vụ</h2>
                <?php if (!empty($msg)): ?>
                <div class="alert alert-<?php echo $msg_type; ?>">
                    <?php echo $msg; ?>
                </div>
                <?php endif; ?>
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="type_name">Tên dịch vụ</label>
                        <input type="text" class="form-control" id="service_name" name="service_name"
                            value="<?php echo $type['service_name']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Mô tả</label>
                        <textarea class="form-control" id="description" name="description"
                            required><?php echo $type['description']; ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
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
layouts('footer');
?>