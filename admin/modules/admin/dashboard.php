<?php
if (!defined('_CODE')) {
    define('_CODE', true);
}
if(!isLogin()){
    redirect('?modules=auth&action=login');
}
$data = [
    'pageTitle' => 'Admin Dashboard'
];
layouts('header', $data);
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
            <div class="content" id="content">
                <?php
            // Lấy giá trị của tham số 'page'
            $page = isset($_GET['page']) ? $_GET['page'] : 'admin/dashboard';
            
            // Tạo đường dẫn đến tệp tương ứng
            $path = 'modules/' . $page . '.php';
            
            // Kiểm tra xem tệp có tồn tại không
            if (file_exists($path)) {
                require_once($path);
            } else {
                echo '<p>Page not found</p>';
            }
            ?>
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
                    <a class="btn btn-primary" href="?modules=auth&action=logout">Logout</a>
                </div>
            </div>
        </div>
    </div>

</body>



<?php 
layouts('footer');
?>