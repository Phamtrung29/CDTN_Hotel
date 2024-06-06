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
    case 'booking_id':
    case 'room_id':
    case 'special_request':
        $query = "SELECT * FROM bookings WHERE $searchType LIKE '%$keyword%' ORDER BY check_in_date ";
        break;
    case 'check_in_date':
    case 'check_out_date':
        $query = "SELECT * FROM bookings WHERE $searchType = '$keyword'";
        break;
    case 'total_price':
        $query = "SELECT * FROM bookings WHERE $searchType >= '$keyword'";
        break;
    default:
        $query = "SELECT * FROM bookings WHERE booking_id LIKE '%$keyword%' OR room_id LIKE '%$keyword%' OR name LIKE '%$keyword%' OR phone LIKE '%$keyword%' OR check_in_date LIKE '%$keyword%' OR check_out_date LIKE '%$keyword%' OR total_price LIKE '%$keyword%' OR special_request LIKE '%$keyword%'";
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($results)) :
                            $count = 0;
                            foreach ($results as $index => $item) :
                                $count++;
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
                        </tr>
                        <?php endforeach;
                        else : ?>
                        <tr>
                            <td colspan="10" class="text-center">Không có kết quả tìm kiếm.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <p class="text-center"><a href="?page=Bookings/list">Quay lại</a></p>

        </div>
    </div>
</body>

<?php layouts('footer'); ?>