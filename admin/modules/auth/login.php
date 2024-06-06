<?php
if (!defined('_CODE')) {
    die('Access denied....');
}
$data = [
    'pageTitle' => 'Đăng nhập Quản Trị Viên'
];
layouts('header', $data);

if (isLogin()) {
    redirect('?modules=admin&action=dashboard');
}

if (isPost()) {
    $filterAll = filter();
    if (!empty(trim($filterAll['email'])) && !empty(trim($filterAll['password']))) {
        $email = $filterAll['email'];
        $password = $filterAll['password'];

        // Truy vấn lấy thông tin users theo email
        $userQuery = oneRaw("SELECT password, id, role FROM users WHERE email = '$email'");
        if (!empty($userQuery)) {
            $passwordHash = $userQuery['password'];
            $userId = $userQuery['id'];
            $userRole = $userQuery['role'];

            if (password_verify($password, $passwordHash)) {
                if ($userRole === 'admin') {
                    // Tạo token
                    $tokenLogin = sha1(uniqid() . time());
                    // Kiểm tra xem tài khoản đã đăng nhập chưa
                    $userLogin = getRows("SELECT * FROM tokenlogin WHERE user_id = '$userId'");
                    if ($userLogin > 1) {
                        setFlashData('msg', 'Tài khoản được đăng nhập ở nơi khác!');
                        setFlashData('msg_type', 'danger');
                        redirect('?modules=auth&action=login');
                    } else {
                        // Insert bảng
                        $dataInsert = [
                            'user_id' => $userId,
                            'token' => $tokenLogin,
                            'create_at' => date('Y-m-d H:i:s')
                        ];
                        $insertStatus = insert('tokenlogin', $dataInsert);
                        if ($insertStatus) {
                            // Insert thành công
                            // Lưu vào session
                            setSession('tokenlogin', $tokenLogin);
                            setSession('userRole', $userRole);
                            redirect('?modules=admin&action=dashboard');
                        } else {
                            setFlashData('msg', 'Không thể đăng nhập vui lòng thử lại sau!');
                            setFlashData('msg_type', 'danger');
                        }
                    }
                } else {
                    setFlashData('msg', 'Bạn không có quyền truy cập!');
                    setFlashData('msg_type', 'danger');
                    redirect('?modules=auth&action=login');
                }
            } else {
                setFlashData('msg', 'Mật khẩu không chính xác!');
                setFlashData('msg_type', 'danger');
                redirect('?modules=auth&action=login');
            }
        } else {
            setFlashData('msg', 'Email không tồn tại');
            setFlashData('msg_type', 'danger');
            redirect('?modules=auth&action=admin_login');
        }
    } else {
        setFlashData('msg', 'Vui lòng nhập email và mật khẩu!');
        setFlashData('msg_type', 'danger');
        redirect('?modules=auth&action=login');
    }
}
$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
?>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back Admin!</h1>
                                    </div>
                                    <?php if (!empty($msg)): ?>
                                    <div class="alert alert-<?php echo $msg_type; ?>" role="alert">
                                        <?php echo $msg; ?>
                                    </div>
                                    <?php endif; ?>
                                    <form class="user" action="" method="post">
                                        <div class="form-group">
                                            <input name="email" type="email" class="form-control form-control-user"
                                                id="exampleInputEmail" aria-describedby="emailHelp"
                                                placeholder="Enter Email Address...">
                                        </div>
                                        <div class="form-group">
                                            <input name="password" type="password"
                                                class="form-control form-control-user" id="exampleInputPassword"
                                                placeholder="Password">
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="?modules=auth&action=forgot">Forgot Password?</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>


</body>

<?php 
layouts('footer');
?>