<?php
if (!defined('_CODE')) {
    define('_CODE', true);
}
$data = [
    'pageTitle' => 'Profile'
];
layouts('header_lr', $data);

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
    <div class="container">
        <h1>User Profile</h1>
        <form method="post" action="?modules=admin&action=udprofile">
            <label for="fullname">Fullname:</label><br>
            <input type="text" id="fullname" name="fullname" value="<?php echo $profile['fullname']; ?>"><br><br>

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


</body>

<?php 
layouts('footer');
?>