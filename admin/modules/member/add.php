<?php
if (!defined('_CODE')) {
    die('Access denied....');
}

$data = [
    'pageTitle' => 'Thêm Thành Viên'
];
layouts('header', $data);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    $result = insert('members', $data);
    if ($result) {
        setFlashData('msg', 'Thêm thành viên thành công!');
        setFlashData('msg_type', 'success');
        redirect('?page=member/list');
    } else {
        setFlashData('msg', 'Thêm thành viên thất bại!');
        setFlashData('msg_type', 'danger');
    }
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

            <div class="container-fluid">
                <h2>Thêm Thành Viên</h2>
                <form action="?modules=member&action=add" method="POST">
                    <div class="form-group">
                        <label for="full_name">Tên thành viên:</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <input type="text" class="form-control" id="address" name="address" required>
                    </div>
                    <div class="form-group">
                        <label for="registration_date">Registration Date:</label>
                        <input type="date" class="form-control" id="registration_date" name="registration_date"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="image_url">Image URL:</label>
                        <input type="text" class="form-control" id="image_url" name="image_url">
                    </div>
                    <div class="form-group">
                        <label for="position">Position:</label>
                        <input type="text" class="form-control" id="position" name="position" required>
                    </div>
                    <div class="form-group">
                        <label for="linkface">Facebook Link:</label>
                        <input type="text" class="form-control" id="linkface" name="linkface">
                    </div>
                    <div class="form-group">
                        <label for="linkinsta">Instagram Link:</label>
                        <input type="text" class="form-control" id="linkinsta" name="linkinsta">
                    </div>
                    <div class="form-group">
                        <label for="linktwitter">Twitter Link:</label>
                        <input type="text" class="form-control" id="linktwitter" name="linktwitter">
                    </div>
                    <div class="form-group">
                        <label for="points">Points:</label>
                        <input type="number" class="form-control" id="points" name="points" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Thêm Thành Viên</button>
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