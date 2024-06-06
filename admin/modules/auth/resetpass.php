<!-- Đổi password --->
<?php
if(!defined('_CODE')){
    die('Access denied....');
}
$data = [
    'pageTitle' => 'Đặt lại mật khẩu'
];
layouts('header', $data);


$token = filter()['token'];

if(!empty($token)){
    $tokenQuery = oneRaw("SELECT id, fullname, email FROM users WHERE forgotToken = '$token'");
    if(!empty($tokenQuery)){
        $userid = $tokenQuery['id'];
        $error = [];
        $filterAll = filter();
        if(isPost()){

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
                $passwordHash = password_hash($filterAll['password'], PASSWORD_DEFAULT);
                $dataUpdate = [
                    'password' => $passwordHash,
                    'forgotToken' => null,
                    'update_at' => date('Y-m-d H:i:s')
                ];
    
                $updateStatus = update('users', $dataUpdate, "id='$userid'");
                if($updateStatus){
                    setFlashData('msg', 'Thay đổi mật khẩu thành công!');
                    setFlashData('msg_type', 'success'); 
                    redirect('?modules=auth&action=login');
                }else{
                    setFlashData('msg', 'Lỗi hệ thống vui lòng thử lại sau!');
                    setFlashData('msg_type', 'danger');
                }
        
            }else{
                setFlashData('msg', 'Vui lòng kiểm tra lại dữ liệu!');
                setFlashData('msg_type', 'danger');
                setFlashData('error', $error);
                redirect('?modules=auth&action=resetpass&token='.$token);
            }
        }
        
    $msg = getFlashData('msg');
    $msg_type = getFlashData('msg_type');
    $error = getFlashData('error');


        ?>
<!--- Bảng đặt lại mật khẩu--->

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Đặt lại mật khẩu</h1>
                                    </div>
                                    <?php if (!empty($msg)): ?>
                                    <div class="alert alert-<?php echo $msg_type; ?>" role="alert">
                                        <?php echo $msg; ?>
                                    </div>
                                    <?php endif; ?>
                                    <form class="user" action="" method="post">
                                        <div class="form-group">
                                            <input name="password" type="password"
                                                class="form-control form-control-user" id="exampleInputPassword"
                                                placeholder="Password">
                                            <?php echo form_error('password', '<span class="error">', '</span>', $error); ?>
                                        </div>
                                        <div class="form-group">
                                            <input name="passwordconfirm" type="password"
                                                class="form-control form-control-user" id="exampleRepeatPassword"
                                                placeholder="Nhập lại mật khẩu">
                                            <?php echo form_error('passwordconfirm', '<span class="error">', '</span>', $error); ?>
                                        </div>
                                        <input type="hidden" name="token" value="<?php echo $token; ?>">
                                        <button type="submit" class="btn btn-primary btn-user btn-block">Gửi</button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="?modules=auth&action=login">Đăng nhập user</a>
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
    }  else{
        getSmg('Liên kết không tồn tại hoặc đã hết hạn.' ,'danger');
    }
}else{
    getSmg('Liên kết không tồn tại hoặc đã hết hạn.' ,'danger');
}

?>


<?php 
layouts('footer_lr');
?>