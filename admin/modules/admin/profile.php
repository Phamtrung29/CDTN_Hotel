<?php
if (!defined('_CODE')) {
    define('_CODE', true);
}
$data = [
    'pageTitle' => 'Admin Dashboard'
];
layouts('header', $data);

$tokenLogin = getSession('tokenlogin'); 
$queryToken = oneRaw("SELECT user_id FROM tokenlogin WHERE token = '$tokenLogin'");
$user_id = $queryToken['user_id'];

// Kiểm tra xem profile đã tồn tại hay chưa
$existingProfile = oneRaw("SELECT * FROM profile WHERE user_id = $user_id");

// Nếu profile chưa tồn tại, thêm một dòng mới vào bảng profile
if (!$existingProfile) {
    $defaultProfileData = [
        'user_id' => $user_id,
        'fullname' => '',
        'date_of_birth' => '',
        'address' => '',
        'phone_number' => ''
    ];
    insert('profile', $defaultProfileData);
}

$profile = oneRaw("SELECT * FROM profile WHERE user_id = $user_id");
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
                <h1>User Profile</h1>
                <form method="post" action="?modules=admin&action=udprofile">
                    <label for="fullname">Fullname:</label><br>
                    <input type="text" id="fullname" name="fullname"
                        value="<?php echo $profile['fullname']; ?>"><br><br>

                    <label for="date_of_birth">Date of Birth:</label><br>
                    <input type="date" id="date_of_birth" name="date_of_birth"
                        value="<?php echo $profile['date_of_birth']; ?>"><br><br>

                    <label for="address">Address:</label><br>
                    <input type="text" id="address" name="address" value="<?php echo $profile['address']; ?>"><br><br>

                    <label for="phone_number">Phone Number:</label><br>
                    <input type="text" id="phone_number" name="phone_number"
                        value="<?php echo $profile['phone_number']; ?>"><br><br>

                    <button type="submit" value="Update Profile">Cập nhật</button>
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