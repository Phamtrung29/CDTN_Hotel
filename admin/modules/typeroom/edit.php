<?php
if(!defined('_CODE')){
    die('Access denied....');
}
$data = [
    'pageTitle' => 'Sửa loại phòng'
];
layouts('header', $data);



$type_id = $_GET['id'];
$type = oneRaw("SELECT * FROM typeroom WHERE type_id = $type_id");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type_name = $_POST['type_name'];
    $description = $_POST['description'];

    $dataUpdate = [
        'type_name' => $type_name,
        'description' => $description
    ];

    $result = update('typeroom', $dataUpdate, "type_id = $type_id");
    if ($result) {
        setFlashData('msg', 'Cập nhật loại phòng thành công!');
        setFlashData('msg_type', 'success');
        redirect('?modules=typerooms&action=list');
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
                <h2>Sửa Loại Phòng</h2>
                <?php if (!empty($msg)): ?>
                <div class="alert alert-<?php echo $msg_type; ?>">
                    <?php echo $msg; ?>
                </div>
                <?php endif; ?>
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="type_name">Tên loại phòng</label>
                        <input type="text" class="form-control" id="type_name" name="type_name"
                            value="<?php echo $type['type_name']; ?>" required>
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
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

</body>

<?php
layouts('footer');
?>