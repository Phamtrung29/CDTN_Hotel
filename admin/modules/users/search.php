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
    case 'id':
    case 'fullname':
    default:
        $query = "SELECT * FROM users WHERE id LIKE '%$keyword%' OR fullname LIKE '%$keyword%'";
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
                    <?php if (!empty($results)):
                        $count = 0;
                                        foreach ($results as $index => $item):
                                            $count++;
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
            <p class="text-center"><a href="?page=orderbooking/list">Quay lại</a></p>
        </div>

    </div>
    </div>
</body>

<?php layouts('footer'); ?>