<?php
if(!defined('_CODE')){
    die('Access denied....');
}
$data = [
    'pageTitle' => 'Sửa loại phòng'
];
layouts('header', $data);



$review_id = $_GET['id'];
$type = oneRaw("SELECT * FROM reviews WHERE review_id = $review_id");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_id = $_POST['room_id'];
    $comment = $_POST['comment'];
    $user_id = $_POST['user_id'];
    $rating = $_POST['rating'];

    $dataUpdate = [
        'room_id' => $room_id,
        'comment' => $comment,
        'user_id' => $user_id,
        'rating' => $rating
    ];

    $result = update('reviews', $dataUpdate, "review_id = $review_id");
    if ($result) {
        setFlashData('msg', 'Cập nhật loại phòng thành công!');
        setFlashData('msg_type', 'success');
        redirect('?page=reviews/list');
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
                <h2>Sửa Đánh Giá</h2>

                <form action="" method="POST">
                    <div class="form-group">
                        <label for="room_id">Id phòng</label>
                        <input type="text" class="form-control" id="room_id" name="room_id"
                            value="<?php echo $type['room_id']; ?>" required>
                    </div>
                    <div class=" form-group">
                        <label for="user_id">User Id</label>
                        <textarea class="form-control" id="user_id" name="user_id"
                            required><?php echo $type['user_id']; ?></textarea>
                    </div>
                    <div class=" form-group">
                        <label for="rating">Rating</label>
                        <textarea class="form-control" id="rating" name="rating"
                            required><?php echo $type['rating']; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="comment">Đánh giá</label>
                        <textarea class="form-control" id="comment" name="comment"
                            required><?php echo $type['comment']; ?></textarea>
                    </div>
                    <button type=" submit" class="btn btn-primary">Cập nhật</button>
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