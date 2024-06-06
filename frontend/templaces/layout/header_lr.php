<?php
if (!defined('_CODE')) {
    die('Access denied....');
}

// Function to check if a given page is the current one
function isActive($page) {
    return isset($_GET['modules']) && $_GET['modules'] == $page ? 'active' : '';
}

function isActivePage($page, $action) {
    return isset($_GET['modules'], $_GET['action']) && $_GET['modules'] == $page && $_GET['action'] == $action ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo !empty($data['pageTitle']) ? $data['pageTitle'] : 'Trung Hotel' ?></title>
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLACES; ?>/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLACES?>/css/style.css?ver=<?php echo rand();?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="templaces/lib/animate/animate.min.css" rel="stylesheet">
    <link href="templaces/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="templaces/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
</head>

<body>


    <!-- Header Start -->
    <div class="container-fluid bg-dark px-0">
        <div class="row gx-0">
            <div class="col-lg-3 bg-dark d-none d-lg-block">
                <a href="?modules=home&action=dashboard"
                    class="navbar-brand w-100 h-100 m-0 p-0 d-flex align-items-center justify-content-center">
                    <img src="<?php echo _WEB_HOST_TEMPLACES?>/img/logo.png" alt="" width="150px" height="100px">
                </a>
            </div>
            <div class="col-lg-9">
                <div class="row gx-0 bg-white d-none d-lg-flex">
                    <div class="col-lg-7 px-5 text-start">
                        <div class="h-100 d-inline-flex align-items-center py-2 me-4">
                            <i class="fa fa-envelope text-primary me-2"></i>
                            <p class="mb-0">phamductrungtp12@gmail.com</p>
                        </div>
                        <div class="h-100 d-inline-flex align-items-center py-2">
                            <i class="fa fa-phone-alt text-primary me-2"></i>
                            <p class="mb-0">0398204557</p>
                        </div>
                    </div>
                    <div class="col-lg-5 px-5 text-end">
                        <div class="d-inline-flex align-items-center py-2">
                            <a class="me-3" href=""><i class="fab fa-facebook-f"></i></a>
                            <a class="me-3" href=""><i class="fab fa-twitter"></i></a>
                            <a class="me-3" href=""><i class="fab fa-linkedin-in"></i></a>
                            <a class="me-3" href=""><i class="fab fa-instagram"></i></a>
                            <a class="" href=""><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
                <nav class="navbar navbar-expand-lg bg-dark navbar-dark p-3 p-lg-0">
                    <a href="index.html" class="navbar-brand d-block d-lg-none">
                        <h1 class="m-0 text-primary text-uppercase">Hotelier</h1>
                    </a>
                    <button type="button" class="navbar-toggler" data-bs-toggle="collapse"
                        data-bs-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        <div class="navbar-nav mr-auto py-0">
                            <a href="?modules=home&action=dashboard"
                                class="nav-item nav-link <?php echo isActivePage('home', 'dashboard'); ?>">Home</a>
                            <a href="?modules=home&action=about"
                                class="nav-item nav-link <?php echo isActivePage('home', 'about'); ?>">About</a>
                            <a href="?modules=Room&action=service"
                                class="nav-item nav-link <?php echo isActivePage('Room', 'service'); ?>">Services</a>
                            <a href="?modules=Room&action=room"
                                class="nav-item nav-link <?php echo isActivePage('Room', 'room'); ?>">Rooms</a>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle <?php echo isActive('Pages'); ?>"
                                    data-bs-toggle="dropdown">Pages</a>
                                <div class="dropdown-menu rounded-0 m-0">
                                    <a href="?modules=Pages&action=team"
                                        class="dropdown-item <?php echo isActivePage('Pages', 'teams'); ?>">Our Team</a>
                                    <a href="?modules=Pages&action=reviews"
                                        class="dropdown-item <?php echo isActivePage('Pages', 'review'); ?>">Testimonial</a>
                                </div>
                            </div>
                            <a href="?modules=contact&action=contact"
                                class="nav-item nav-link <?php echo isActivePage('contact', 'contact'); ?>">Contact</a>
                        </div>
                        <div class="d-flex align-items-center">
                            <?php if (isLogin()): 
                                $tokenLogin = getSession('tokenlogin'); 
                                $queryToken = oneRaw("SELECT user_id FROM tokenlogin WHERE token = '$tokenLogin'");
                                $user_id = $queryToken['user_id'];
                                $user = oneRaw("SELECT * FROM users WHERE id='$user_id'")
                                ?>
                            <a href="?modules=home&action=cart" class="nav-link text-white">
                                <i class="fa fa-shopping-cart fa-1.5x text-primary"></i>
                            </a>
                            <a href="?modules=home&action=myroom" class="nav-item nav-link text-white">My room</a>
                            <div class="dropdown">
                                <a href="#" class="nav-link dropdown-toggle text-white me-3" role="button"
                                    id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span
                                        class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $user['fullname']?></span>
                                    <img class="img-profile rounded-circle"
                                        src="<?php echo _WEB_HOST_TEMPLACES?>/img/team-1.jpg"
                                        style="width: 30px; height: 30px;">
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="?modules=home&action=info">Thông tin cá nhân</a>
                                    </li>
                                    <li><a class="dropdown-item" href="#">Cài đặt</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="?modules=auth&action=logout" id="logoutB">Đăng
                                            xuất</a></li>
                                </ul>
                            </div>
                            <?php else: ?>
                            <button type="button" class="btn btn-outline-primary me-2" id="redirectBtn">Login</button>
                            <button type="button" class="btn btn-outline-secondary" id="registerBtn">Register</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>

    <!-- Header End -->
    <!-- Template Javascript -->
    <script>
    document.getElementById("redirectBtn").addEventListener("click", function() {
        window.location.href = "?modules=auth&action=login";
    });
    </script>
    <script>
    document.getElementById("registerBtn").addEventListener("click", function() {
        window.location.href = "?modules=auth&action=register";
    });
    </script>
    <script>
    document.querySelectorAll('.navbar-nav .nav-item').forEach(function(element) {
        element.addEventListener('click', function() {
            document.querySelectorAll('.navbar-nav .nav-item').forEach(function(nav) {
                nav.classList.remove('active');
            });
            this.classList.add('active');
        });
    });
    </script>

</body>

</html>