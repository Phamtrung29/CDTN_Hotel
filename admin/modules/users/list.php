<?php
if(!defined('_CODE')){
    die('Access denied....');
}
$data = [
    'pageTitle' => 'Danh sách người dùng'
];
layouts('header', $data);


$rowsPerPage = 5;

// Trang hiện tại, mặc định là trang 1
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$offset = max(0, ($page - 1) * $rowsPerPage);

// Truy vấn danh sách đặt phòng với offset và số lượng dòng dữ liệu trên mỗi trang
$listUsers = getRaw("SELECT * FROM users ORDER BY update_at LIMIT $offset, $rowsPerPage");
$countResult = getRaw("SELECT COUNT(*) AS total FROM users");
$countRow = $countResult[0]; // Lấy phần tử đầu tiên của mảng kết quả
$count = $countRow['total']; // Trích xuất giá trị 'total'

// Tính tổng số trang
$totalPages = ceil($count / $rowsPerPage);
$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
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
                    <h1 class="h3 mb-4 text-gray-800">Quản lý người dùng</h1>
                    <?php if (!empty($smg)): ?>
                    <div class="alert alert-<?php echo $smg_type; ?>">
                        <?php echo $smg; ?>
                    </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <a href="?modules=users&action=add" class="btn btn-success btn-sm">Thêm Người Dùng <i
                                class="fas fa-plus"></i></a>

                        <!-- Search Form -->
                        <form action="?modules=users&action=search" method="POST" class="form-inline d-sm-inline-block">
                            <div class="input-group">
                                <input type="text" class="form-control bg-light border-0 small"
                                    placeholder="Tìm kiếm..." aria-label="Tìm kiếm" aria-describedby="basic-addon2"
                                    name="keyword">
                                <div class="input-group-append">
                                    <button class="btn btn-info" type="submit">
                                        <i class="fas fa-search fa-sm"></i>
                                    </button>
                                </div>
                                <div class="input-group-append">
                                    <select class="form-control bg-light border-0 small" name="search_type">
                                        <option value="all">Tất cả</option>
                                        <option value="id">User ID</option>
                                        <option value="fullname">Name</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Danh sách người dùng</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Họ Tên</th>
                                            <th>Email</th>
                                            <th>Số Điện Thoại</th>
                                            <th>Trạng Thái</th>
                                            <th>Role</th>
                                            <th>Sửa</th>
                                            <th>Xóa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($listUsers)):
                                        foreach ($listUsers as $index => $item):
                                            $count = $offset + $index + 1;
                                    ?>
                                        <tr>
                                            <td><?php echo $count;?></td>
                                            <td><?php echo $item['fullname'];?></td>
                                            <td><?php echo $item['email'];?></td>
                                            <td><?php echo $item['phone'];?></td>
                                            <td><?php echo $item['status'] == 1 ? '<button class="btn btn-success btn-sm">Đã kích hoạt</button>' : '<button class="btn btn-warning btn-sm">Chưa kích hoạt</button>'; ?>
                                            </td>
                                            <td><?php echo $item['role'];?></td>
                                            <td><a href="<?php echo _WEB_HOST;?>?modules=users&action=edit&id=<?php echo $item['id'];?>"
                                                    class="btn btn-warning btn-sm"><i class="fas fa-pen"></i></a></td>
                                            <td><a onclick="return confirm('Bạn có chắc chắn muốn xóa không?')"
                                                    href="<?php echo _WEB_HOST;?>?modules=users&action=delete&id=<?php echo $item['id'];?>"
                                                    class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a></td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                <div class="alert alert-danger">Chưa có người dùng nào</div>
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination">
                                        <?php if ($page > 1): ?>
                                        <li class="page-item"><a class="page-link"
                                                href="?modules=users&action=list&page=<?php echo ($page - 1); ?>">Previous</a>
                                        </li>
                                        <?php endif; ?>
                                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                        <li class="page-item <?php if ($page === $i) echo 'active'; ?>"><a
                                                class="page-link"
                                                href="?modules=users&action=list&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                        </li>
                                        <?php endfor; ?>
                                        <?php if ($page < $totalPages): ?>
                                        <li class="page-item"><a class="page-link"
                                                href="?modules=users&action=list&page=<?php echo ($page + 1); ?>">Next</a>
                                        </li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
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