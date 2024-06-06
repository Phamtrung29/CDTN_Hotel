<!-- Đăng ký -->
<?php
if (!defined('_CODE')) {
    define('_CODE', true);
}
$data = [
    'pageTitle' => 'Thêm người dùng'
];
layouts('header', $data);

if(isPost()){
    $filterAll = filter(); 
    $error = [];
    //validate họ tên
    if(empty($filterAll['fullname'])){
        $error['fullname']['require'] = 'Họ tên bắt buộc phải nhập.';
    }else{
        if(strlen($filterAll['fullname']) < 5){
            $error['fullname']['min'] = 'Họ tên có ít nhất 5 ký tự.';
        }
    }
    //validate email
    if(empty($filterAll['email'])){
            $error['email']['require'] = 'Email bắt buộc phải nhập.';
    }
    else{
        $email = $filterAll['email'];
        $sql = "SELECT id FROM users WHERE email = '$email'";
        if(getRows($sql) > 0){
            $error['email']['unique'] = 'Email đã tồn tại.';
        } 
    }
    //validate số điện thoại
    if(empty($filterAll['phone'])){
        $error['phone']['require'] = 'Số điện thoại bắt buộc phải nhập.';
    }else{
        if(!isPhone($filterAll['phone'])){
            $error['phone']['isphone'] = 'Số điện thoại không đúng định dạng';
        }
    }
    //validate password
    if(empty($filterAll['password'])){
        $error['password']['require'] = 'Mật khẩu bắt buộc phải nhập.';
    }else{
        if(strlen($filterAll['password']) < 8){
            $error['password']['min'] = 'Họ tên có ít nhất 8 ký tự.';
        }
    }
    //validate passwordconfirm
    if(empty($filterAll['passwordconfirm'])){
        $error['passwordconfirm']['require'] = 'Bạn chưa xác nhận mật khẩu.';
    }else{
        if($filterAll['password'] != $filterAll['passwordconfirm']){
            $error['passwordconfirm']['check'] = 'Bạn chưa xác nhận đúng mật khẩu';
        }
    }
    if(empty($error)){
        $activeToken = sha1(uniqid().time());
        $dataInsert = [
            'fullname' => $filterAll['fullname'],
            'email' => $filterAll['email'],
            'phone' => $filterAll['phone'],
            'password' => password_hash($filterAll['password'], PASSWORD_DEFAULT),
            'status' => $filterAll['status'],
            'create_at' => date('Y-m-d H:i:s'),
            'role' => $filterAll['role']
        ];

        $insertStatus = insert('users', $dataInsert);
        if($insertStatus){
            setFlashData('smg', 'Thêm user thành công!');
            setFlashData('smg_type', 'success'); 
            redirect('?page=users/list');
        }else{
            setFlashData('smg', 'Thêm user không thành công!');
            setFlashData('smg_type', 'danger');
            redirect('?page=users/add');
        }

    }else{
        setFlashData('smg', 'Vui lòng kiểm tra lại dữ liệu!');
        setFlashData('smg_type', 'danger');
        setFlashData('error', $error);
        setFlashData('old', $filterAll);
        redirect('?page=users/add');
    }
    

}


$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
$error = getFlashData('error');

$old = getFlashData('old');

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
            <div class="row">
                <div class="col-4 mg-home" style="margin: 50px auto;">
                    <h2 class="text-center text-uppercase">Thêm User</h2>
                    <?php
            if(!empty($smg)){
                getSmg($smg, $smg_type);
            } 
        ?>
                    <form action="" method="post">
                        <div class="form-group mg-form">
                            <label for="">Họ Tên</label>
                            <input name="fullname" type="fullname" class="form-control" placeholder="Họ Tên"
                                value="<?php echo old('fullname', $old);?>">
                            <?php
                echo form_error('fullname', '<span class="error">', '</span>', $error);
                ?>
                        </div>
                        <div class="form-group mg-form">
                            <label for="">Email</label>
                            <input name="email" type="email" class="form-control" placeholder="Địa chỉ email"
                                value="<?php echo old('email', $old);?>">
                            <?php 
                echo form_error('email', '<span class="error">', '</span>', $error);
                ?>
                        </div>
                        <div class=" form-group">
                            <label for="">Số điện thoại</label>
                            <input name="phone" type="phone" class="form-control" placeholder="Số điện thoại"
                                value="<?php echo old('phone', $old); ?>">
                            <?php 
                echo form_error('phone', '<span class="error">', '</span>', $error);
                ?>
                        </div>
                        <div class=" form-group">
                            <label for="">Password</label>
                            <input name="password" type="password" class="form-control" placeholder="Password"
                                value="<?php echo old('password', $old); ?>">
                            <?php 
                echo form_error('password', '<span class="error">', '</span>', $error);
                ?>
                        </div>
                        <div class=" form-group">
                            <label for="">Nhập lại password</label>
                            <input name="passwordconfirm" type="password" class="form-control"
                                placeholder="Nhập lại mật khẩu" value="<?php echo old('passwordconfirm', $old); ?>">
                            <?php
                echo form_error('passwordconfirm', '<span class="error">', '</span>', $error);
                ?>
                        </div>
                        <div class="form-group">
                            <select name="status" id="" class="form-control">
                                <option value="0" <?php echo (old('status', $old)) ? 'selected' : false; ?>>Chưa kích
                                    hoạt</option>
                                <option value="1" <?php echo (old('status', $old)) ? 'selected' : false; ?>>Đã kích hoạt
                                </option>
                            </select>
                            <div class="form-group">
                                <select name="role" id="" class="form-control">
                                    <option value="user" <?php echo (old('role', $old)) ? 'selected' : false; ?>>User
                                    </option>
                                    <option value="admin" <?php echo (old('role', $old)) ? 'selected' : false; ?>>Admin
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class=" mg-button">
                            <button type="submit" class="btn btn-primary btn-block">Cập nhật</button>
                        </div>
                        <hr>
                        <p class="text-center"><a href="?page=users/list">Quay lại</a></p>
                    </form>
                </div>
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