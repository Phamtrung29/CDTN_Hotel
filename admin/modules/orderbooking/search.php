<?php
if (!defined('_CODE')) {
    die('Access denied....');
}


$data = [
    'pageTitle' => 'Kết quả tìm kiếm'
];
layouts('header', $data);

// Lấy từ khóa tìm kiếm từ yêu cầu POST
$keyword = isset($_POST['keyword']) ? $_POST['keyword'] : '';
$searchType = isset($_POST['search_type']) ? $_POST['search_type'] : 'all';

// Tạo câu truy vấn tùy thuộc vào tiêu chí tìm kiếm được chọn
switch ($searchType) {
    case 'user_id':
    case 'room_id':
    default:
        $query = "SELECT * FROM orderbooking WHERE user_id LIKE '%$keyword%' OR room_id LIKE '%$keyword%'";
        break;
}

// Thực hiện truy vấn
$results = getRaw($query);

$flashMsg = getFlashData('smg');
$flashMsgType = getFlashData('smg_type');
if (!empty($flashMsg)) {
    echo "<div class='alert alert-$flashMsgType'>$flashMsg</div>";
}
?>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php require_once('templaces/layout/sidebar.php'); ?>

        <!-- Content Wrapper -->
        <div class="container-fluid">
            <?php
        require_once('templaces/layout/navbar.php');?>
            <h2 class="my-4">Kết quả tìm kiếm</h2>

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
            if (!empty($results)):
                $count = 0;
                foreach ($results as $index => $item):
                    $count++;
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
            <p class="text-center"><a href="?page=orderbooking/list">Quay lại</a></p>
        </div>

    </div>
    </div>
</body>

<?php layouts('footer'); ?>