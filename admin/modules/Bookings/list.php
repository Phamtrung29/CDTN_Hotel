<?php
if (!defined('_CODE')) {
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
$listBookings = getRaw("SELECT * FROM bookings ORDER BY check_in_date LIMIT $offset, $rowsPerPage");
$countResult = getRaw("SELECT COUNT(*) AS total FROM bookings");
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
        <div class="container-fluid">
            <h2 class="my-4">Quản lý đặt phòng</h2>
            <div class="mb-3">
                <a href="?modules=bookings&action=add" class="btn btn-success btn-sm">
                    Thêm Đặt Phòng <i class="fas fa-plus"></i>
                </a>

                <!-- Search Form -->
                <form action="?modules=Bookings&action=search" method="POST" class="form-inline d-sm-inline-block">
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
                                <option value="booking_id">Booking ID</option>
                                <option value="room_id">ID Phòng</option>
                                <option value="check_in_date">Ngày Nhận Phòng</option>
                                <option value="check_out_date">Ngày Trả Phòng</option>
                                <option value="total_price">Tổng Giá</option>
                                <option value="special_request">Yêu Cầu Đặc Biệt</option>
                            </select>
                        </div>
                    </div>
                </form>

            </div>
            <?php
    if (!empty($smg)) {
        echo "<div class='alert alert-$smg_type'>$smg</div>";
    }
    ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Booking ID</th>
                            <th>Người Đặt</th>
                            <th>Phòng</th>
                            <th>Tên Khách</th>
                            <th>Số Điện Thoại</th>
                            <th>Ngày Nhận Phòng</th>
                            <th>Ngày Trả Phòng</th>
                            <th>Tổng Giá</th>
                            <th>Yêu Cầu Đặc Biệt</th>
                            <th width="5%">Sửa</th>
                            <th width="5%">Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                if (!empty($listBookings)):
                    foreach ($listBookings as $index => $item):
                        $count = $offset + $index + 1;
                ?>

                        <tr>
                            <td><?php echo $count; ?></td>
                            <td><?php echo $item['booking_id']; ?></td>
                            <td><?php echo $item['user_id']; ?></td>
                            <td><?php echo $item['room_id']; ?></td>
                            <td><?php echo $item['name']; ?></td>
                            <td><?php echo $item['phone']; ?></td>
                            <td><?php echo $item['check_in_date']; ?></td>
                            <td><?php echo $item['check_out_date']; ?></td>
                            <td><?php echo $item['total_price']; ?></td>
                            <td><?php echo $item['special_request']; ?></td>
                            <td>
                                <a href="?modules=bookings&action=edit&id=<?php echo $item['booking_id']; ?>"
                                    class="btn btn-warning btn-sm">
                                    <i class="fas fa-pen"></i>
                                </a>
                            </td>
                            <td>
                                <a onclick="return confirm('Bạn có chắc chắn muốn xóa không?')"
                                    href="?modules=bookings&action=delete&id=<?php echo $item['booking_id']; ?>"
                                    class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php
                    endforeach;
                else:
                ?>
                        <tr>
                            <td colspan="11" class="text-center">
                                <div class="alert alert-danger">Chưa có đặt phòng nào</div>
                            </td>
                        </tr>
                        <?php
                endif;
                ?>
                    </tbody>
                </table>
            </div>
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <?php if ($page > 1): ?>
                    <li class="page-item"><a class="page-link"
                            href="?modules=Bookings&action=list&page=<?php echo ($page - 1); ?>">Previous</a></li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php if ($page === $i) echo 'active'; ?>"><a class="page-link"
                            href="?modules=Bookings&action=list&page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                    <?php endfor; ?>
                    <?php if ($page < $totalPages): ?>
                    <li class="page-item"><a class="page-link"
                            href="?modules=Bookings&action=list&page=<?php echo ($page + 1); ?>">Next</a></li>
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