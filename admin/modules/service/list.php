<?php
if(!defined('_CODE')){
    die('Access denied....');
}
// Số lượng dòng dữ liệu trên mỗi trang
$rowsPerPage = 5;

// Trang hiện tại, mặc định là trang 1
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$offset = max(0, ($page - 1) * $rowsPerPage);
$data = [
    'pageTitle' => 'Danh sách booking'
];
layouts('header', $data);

// Truy vấn danh sách đặt phòng với offset và số lượng dòng dữ liệu trên mỗi trang
$listService = getRaw("SELECT * FROM services ORDER BY service_id LIMIT $offset, $rowsPerPage");
$countResult = getRaw("SELECT COUNT(*) AS total FROM services");
$countRow = $countResult[0]; // Lấy phần tử đầu tiên của mảng kết quả
$count = $countRow['total']; // Trích xuất giá trị 'total'

// Tính tổng số trang
$totalPages = ceil($count / $rowsPerPage);
$flashMsg = getFlashData('smg');
$flashMsgType = getFlashData('smg_type');
if (!empty($flashMsg)) {
    echo "<div class='alert alert-$flashMsgType'>$flashMsg</div>";
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
            <div class="content" id="content">
                <div class="container-fluid">
                    </hr>
                    <h2>Quản lý dịch vụ</h2>
                    <p><a href="?modules=service&action=add" class=" btn btn-success btn-sm">Thêm dịch vụ <i
                                class="fas fa-plus"></i></a>
                    </p>
                    <table class="table table-bordered">
                        <thead>
                            <th>STT</th>
                            <th>Tên dịch vụ</th>
                            <th>Mô tả</th>
                            <th>Giá</th>
                            <th width="5%">Sửa</th>
                            <th width="5%">Xóa</th>
                        </thead>
                        <tbody>
                            <?php
            if(!empty($listService)):
                foreach ($listService as $index => $item):
                    $count = $offset + $index + 1;
            ?>
                            <tr>
                                <td><?php echo $count;?></td>
                                <td><?php echo $item['service_name'];?></td>
                                <td><?php echo $item['description'];?></td>
                                <td><?php echo $item['price'];?></td>

                                <td><a href="<?php echo _WEB_HOST;?>?modules=service&action=edit&id=<?php echo $item['service_id'];?>"
                                        class=" btn btn-warning btn-sm"><i class="fas fa-pen"></i></a></td>
                                <td><a onclick="return confirm('Bạn có chắc chắn muốn xóa không?')"
                                        href="<?php echo _WEB_HOST;?>?modules=service&action=delete&id=<?php echo $item['service_id'];?>"
                                        class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a></td>
                            </tr>
                            <?php
                endforeach;
            else:
            ?>
                            <tr>
                                <td colspan="5">
                                    <div class="alert alert-danger text-center">Chưa có loại phòng nào</div>
                                </td>
                            </tr>
                            <?php
            endif;
            ?>
                        </tbody>
                    </table>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <?php if ($page > 1): ?>
                            <li class="page-item"><a class="page-link"
                                    href="?modules=service&action=list&page=<?php echo ($page - 1); ?>">Previous</a>
                            </li>
                            <?php endif; ?>
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php if ($page === $i) echo 'active'; ?>"><a class="page-link"
                                    href="?modules=service&action=list&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                            <?php endfor; ?>
                            <?php if ($page < $totalPages): ?>
                            <li class="page-item"><a class="page-link"
                                    href="?modules=service&action=list&page=<?php echo ($page + 1); ?>">Next</a></li>
                            <?php endif; ?>
                        </ul>
                    </nav>
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
                    <a class="btn btn-primary" href="?modules=auth&action=logout">Logout</a>
                </div>
            </div>
        </div>
    </div>

</body>

<?php 
layouts('footer');
?>