<?php
if (!defined('_CODE')) {
    define('_CODE', true);
}
$tokenLogin = getSession('tokenlogin'); 
$queryToken = oneRaw("SELECT user_id FROM tokenlogin WHERE token = '$tokenLogin'");
$user_id = $queryToken['user_id'];
$ifuser = oneRaw(("SELECT * FROM users WHERE id='$user_id'"))

?>

<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>


    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Alerts -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                <?php
        // Lấy thông báo mới từ cơ sở dữ liệu
        $new_alerts = getNewAlerts(); // Hàm này cần phải được định nghĩa để lấy thông báo mới từ cơ sở dữ liệu

        // Đếm số lượng thông báo mới
        $total_new_alerts = count($new_alerts);

        // Hiển thị số lượng thông báo mới trong badge
        echo '<span class="badge badge-danger badge-counter">' . $total_new_alerts . '</span>';
        ?>
            </a>
            <!-- Dropdown - Alerts -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                    Alerts Center
                </h6>
                <?php
        // Kiểm tra nếu có thông báo mới
        if (!empty($new_alerts)) {
            // Hiển thị từng thông báo mới
            foreach ($new_alerts as $alert) {
                echo '<a class="dropdown-item d-flex align-items-center" href="#">';
                echo '<div class="mr-3">';
                echo '<div class="icon-circle bg-primary">';
                echo '<i class="fas fa-file-alt text-white"></i>';
                echo '</div>';
                echo '</div>';
                echo '<div>';
                echo '<div class="small text-gray-500">' . date('M d, Y', strtotime($alert['created_at'])) . '</div>';
                echo '<span class="font-weight-bold">' . $alert['message'] . '</span>';
                echo '</div>';
                echo '</a>';
            }
        } else {
            // Hiển thị thông báo khi không có thông báo mới
            echo '<a class="dropdown-item text-center small text-gray-500" href="#">No new alerts</a>';
        }
        ?>
                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
            </div>
        </li>


        <!-- Nav Item - Messages -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-envelope fa-fw"></i>
                <!-- Counter - Messages -->
                <span class="badge badge-danger badge-counter">9</span>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="messagesDropdown">
                <h6 class="dropdown-header">
                    Message Center
                </h6>
                <?php
        // Lấy dữ liệu từ bảng contact
        $contacts = getRaw("SELECT * FROM contact ORDER BY created_at DESC LIMIT 5");

        // Kiểm tra xem có dữ liệu contact hay không
        if (!empty($contacts)) {
            foreach ($contacts as $contact) {
                echo '<a class="dropdown-item d-flex align-items-center" href="#">';
                echo '<div class="dropdown-list-image mr-3">';
                echo '<img class="rounded-circle" src="templaces/img/undraw_profile_1.svg" alt="...">';
                echo '<div class="status-indicator bg-success"></div>';
                echo '</div>';
                echo '<div class="font-weight-bold">';
                echo '<div class="text-truncate">' . $contact['message'] . '</div>';
                echo '<div class="small text-gray-500">' . $contact['name'] . ' · ' . date('M d, Y', strtotime($contact['created_at'])) . '</div>';
                echo '</div>';
                echo '</a>';
            }
        } else {
            // Nếu không có dữ liệu, hiển thị thông báo
            echo '<p class="dropdown-item text-center small text-gray-500">No messages</p>';
        }
        ?>
                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->

        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $ifuser['fullname']; ?></span>
                <img class="img-profile rounded-circle" src="templaces/img/undraw_profile.svg">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="?modules=admin&action=profile">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Settings
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>

    </ul>

</nav>
<!-- End of Topbar -->