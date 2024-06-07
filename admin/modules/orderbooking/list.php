<?php
if (!defined('_CODE')) {
    die('Access denied....');
}

$data = [
    'pageTitle' => 'Danh sách đơn đặt phòng'
];
layouts('header', $data);
// Số lượng dòng dữ liệu trên mỗi trang
$rowsPerPage = 4;

// Trang hiện tại, mặc định là trang 1
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$offset = max(0, ($page - 1) * $rowsPerPage);

// Truy vấn danh sách đặt phòng với offset và số lượng dòng dữ liệu trên mỗi trang
$listOrders = getRaw("SELECT * FROM orderbooking ORDER BY check_in_date LIMIT $offset, $rowsPerPage");
$countResult = getRaw("SELECT COUNT(*) AS total FROM orderbooking");
$countRow = $countResult[0]; // Lấy phần tử đầu tiên của mảng kết quả
$count = $countRow['total']; // Trích xuất giá trị 'total'

// Tính tổng số trang
$totalPages = ceil($count / $rowsPerPage);
$flashMsg = getFlashData('smg');
$flashMsgType = getFlashData('smg_type');

?>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php
        require_once('templaces/layout/sidebar.php');?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Flash message -->

            <!-- Main Content -->
            <?php
        require_once('templaces/layout/navbar.php');?>
            <div class="container-fluid">
                <h2>Quản lý đơn đặt phòng</h2>
                <?php if (!empty($flashMsg)): ?>
                <div class="alert alert-<?php echo $flashMsgType; ?>"><?php echo $flashMsg; ?></div>
                <?php endif; ?>
                <div class="mb-3">
                    <a href="?modules=orderbooking&action=add" class="btn btn-success btn-sm">Thêm Đơn Đặt Phòng <i
                            class="fas fa-plus"></i></a>
                    <!-- Search Form -->
                    <form action="?modules=orderbooking&action=search" method="POST"
                        class="form-inline d-sm-inline-block">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Tìm kiếm..."
                                aria-label="Tìm kiếm" aria-describedby="basic-addon2" name="keyword">
                            <div class="input-group-append">
                                <button class="btn btn-info" type="submit">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                            <div class="input-group-append">
                                <select class="form-control bg-light border-0 small" name="search_type">
                                    <option value="all">Tất cả</option>
                                    <option value="user_id">User ID</option>
                                    <option value="room_id">ID Phòng</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <td>
                    <form method="POST" action="?modules=orderbooking&action=check_in">
                        <input type="hidden" name="order_id" value="<?php echo $item['order_id']; ?>">
                        <input type="text" name="confirmation_code" placeholder="Enter Confirmation Code" required>
                        <input type="submit" value="Check In" class="btn btn-primary btn-sm">
                    </form>
                </td>


                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User ID</th>
                            <th>Room ID</th>
                            <th>Ngày Nhận Phòng</th>
                            <th>Ngày Trả Phòng</th>
                            <th>Tổng Giá</th>
                            <th>Trạng Thái Đơn Hàng</th>
                            <th>Phương Thức Thanh Toán</th>
                            <th>Trạng Thái Thanh Toán</th>
                            <th>Nhập Xác Nhận</th>
                            <th width="5%">Cập Nhật Trạng Thái</th>
                            <th width="5%">Sửa</th>
                            <th width="5%">Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
            if (!empty($listOrders)):
                foreach ($listOrders as $index => $item):
                    $count = $offset + $index + 1;
            ?>
                        <tr>
                            <td><?php echo $count; ?></td>
                            <td><?php echo $item['user_id']; ?></td>
                            <td><?php echo $item['room_id']; ?></td>
                            <td><?php echo $item['check_in_date']; ?></td>
                            <td><?php echo $item['check_out_date']; ?></td>
                            <td><?php echo $item['total_price']; ?></td>
                            <td><?php echo $item['order_status']; ?></td>
                            <td><?php echo $item['payment_method']; ?></td>
                            <td><?php echo $item['payment_status']; ?></td>
                            <td>
                                <form method="POST" action="?modules=orderbooking&action=udstatus">
                                    <input type="hidden" name="order_id" value="<?php echo $item['order_id']; ?>">
                                    <input type="text" name="confirmation_code" placeholder="Enter Confirmation Code"
                                        required>
                                    <input type="submit" value="Check In" class="btn btn-primary btn-sm">
                                </form>
                            </td>
                            <td>
                                <form method="POST" action="?modules=orderbooking&action=udstatus">
                                    <input type="hidden" name="order_id" value="<?php echo $item['order_id']; ?>">
                                    <select name="order_status">
                                        <option value="pending"
                                            <?php if($item['order_status'] == 'pending') echo 'selected'; ?>>Pending
                                        </option>
                                        <option value="confirmed"
                                            <?php if($item['order_status'] == 'confirmed') echo 'selected'; ?>>Confirmed
                                        </option>
                                        <option value="checked_in"
                                            <?php if($item['order_status'] == 'checked_in') echo 'selected'; ?>>Checked
                                            In</option>
                                        <option value="completed"
                                            <?php if($item['order_status'] == 'completed') echo 'selected'; ?>>Completed
                                        </option>
                                        <option value="cancelled"
                                            <?php if($item['order_status'] == 'cancelled') echo 'selected'; ?>>Cancelled
                                        </option>
                                    </select>
                                    <input type="submit" value="Update" class="btn btn-success btn-sm">
                                </form>
                            </td>
                            <td><a href="?modules=orderbooking&action=edit&id=<?php echo $item['order_id']; ?>"
                                    class="btn btn-warning btn-sm"><i class="fas fa-pen"></i></a></td>
                            <td><a onclick="return confirm('Bạn có chắc chắn muốn xóa không?')"
                                    href="?modules=orderbooking&action=delete&id=<?php echo $item['order_id']; ?>"
                                    class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a></td>
                        </tr>
                        <?php
                endforeach;
            else:
            ?>
                        <tr>
                            <td colspan="12">
                                <div class="alert alert-danger text-center">Chưa có đơn đặt phòng nào</div>
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
                                href="?modules=orderbooking&action=list&page=<?php echo ($page - 1); ?>">Previous</a>
                        </li>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php if ($page === $i) echo 'active'; ?>"><a class="page-link"
                                href="?modules=orderbooking&action=list&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                        <?php endfor; ?>
                        <?php if ($page < $totalPages): ?>
                        <li class="page-item"><a class="page-link"
                                href="?modules=orderbooking&action=list&page=<?php echo ($page + 1); ?>">Next</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
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