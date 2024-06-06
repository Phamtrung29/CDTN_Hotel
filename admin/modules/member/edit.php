<?php
if (!defined('_CODE')) {
    die('Access denied....');
}

// Kiểm tra và include file header
$data = [
    'pageTitle' => 'Sửa Thành Viên'
];
layouts('header', $data);

// Lấy ID của thành viên cần sửa từ URL
$member_id = $_GET['id'];

// Truy vấn dữ liệu của thành viên từ cơ sở dữ liệu
$member = oneRaw("SELECT * FROM members WHERE member_id = $member_id");

// Xử lý khi người dùng submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $registration_date = $_POST['registration_date'];
    $image_url = $_POST['image_url'];
    $position = $_POST['position'];
    $linkface = $_POST['linkface'];
    $linkinsta = $_POST['linkinsta'];
    $linktwitter = $_POST['linktwitter'];
    $points = $_POST['points'];

    // Thực hiện cập nhật thành viên vào cơ sở dữ liệu
    $data = [
        'full_name' => $full_name,
        'email' => $email,
        'phone' => $phone,
        'address' => $address,
        'registration_date' => $registration_date,
        'image_url' => $image_url,
        'position' => $position,
        'linkface' => $linkface,
        'linkinsta' => $linkinsta,
        'linktwitter' => $linktwitter,
        'points' => $points
    ];

    $result = update('members', $data, "member_id = $member_id");
    if ($result) {
        setFlashData('msg', 'Cập nhật thành viên thành công!');
        setFlashData('msg_type', 'success');
        redirect('?page=member/list');
    } else {
        setFlashData('msg', 'Cập nhật thành viên thất bại!');
        setFlashData('msg_type', 'danger');
    }
}

// Hiển thị form sửa thành viên với dữ liệu hiện tại
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
                <h2>Sửa Thành Viên</h2>
                <form action="?modules=member&action=edit&id=<?php echo $member['member_id']; ?>" method="POST">
                    <!-- Các trường nhập liệu với giá trị hiện tại của thành viên -->
                    <div class="form-group">
                        <label for="full_name">Tên Thành Viên:</label>
                        <input type="text" class="form-control" id="full_name" name="full_name"
                            value="<?php echo $member['full_name']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?php echo $member['email']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="text" class="form-control" id="phone" name="phone"
                            value="<?php echo $member['phone']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Địa Chỉ:</label>
                        <input type="text" class="form-control" id="address" name="address"
                            value="<?php echo $member['address']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="registration_date">Ngày Đăng Ký:</label>
                        <input type="date" class="form-control" id="registration_date" name="registration_date"
                            value="<?php echo $member['registration_date']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="image_url">URL Hình Ảnh:</label>
                        <input type="text" class="form-control" id="image_url" name="image_url"
                            value="<?php echo $member['image_url']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="position">Vị Trí:</label>
                        <input type="text" class="form-control" id="position" name="position"
                            value="<?php echo $member['position']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="linkface">Link Facebook:</label>
                        <input type="text" class="form-control" id="linkface" name="linkface"
                            value="<?php echo $member['linkface']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="linkinsta">Link Instagram:</label>
                        <input type="text" class="form-control" id="linkinsta" name="linkinsta"
                            value="<?php echo $member['linkinsta']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="linktwitter">Link Twitter:</label>
                        <input type="text" class="form-control" id="linktwitter" name="linktwitter"
                            value="<?php echo $member['linktwitter']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="points">Điểm:</label>
                        <input type="number" class="form-control" id="points" name="points"
                            value="<?php echo $member['points']; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Cập Nhật Thành Viên</button>
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