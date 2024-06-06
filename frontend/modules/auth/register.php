<!-- Đăng ký -->
<?php
if(!defined('_CODE')){
    die('Access denied....');
}
$data = [
    'pageTitle' => 'Đăng ký tài khoản'
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
            'activeToken' => $activeToken,
            'create_at' => date('Y-m-d H:i:s')
        ];

        $insertStatus = insert('users', $dataInsert);
        if($insertStatus){
            
            //thiết lập gửi mail
            $linkMail = _WEB_HOST . '?modules=auth&action=active&token='. $activeToken;

            $subject = $filterAll['fullname'].' Vui lòng xác thực tài khoản!!';
            $content = 'Chào '.$filterAll['fullname'].'.<br>';
            $content .= 'Vui lòng click vào đường link dưới đây để xác thực tài khoản của bạn!'.'.<br>';
            $content .= $linkMail . '<br>';
            $content .= 'Trân trọng cảm ơn';

            $sendEmail = sendMail($filterAll['email'], $subject, $content);
            
            if($sendEmail){
                setFlashData('smg', 'Đăng ký thành công! Vui lòng kiểm tra email để kích hoạt!');
                setFlashData('smg_type', 'success');
            }else{
                setFlashData('smg', 'Hệ thống đang gặp sự cố vui lòng thử lại sau!');
                setFlashData('smg_type', 'danger');
            }

            
        }else{
            setFlashData('smg', 'Đăng ký không thành công!');
                setFlashData('smg_type', 'danger');
        }

    }else{
        setFlashData('smg', 'Vui lòng kiểm tra lại dữ liệu!');
        setFlashData('smg_type', 'danger');
        setFlashData('error', $error);
        setFlashData('old', $filterAll);
        redirect('?modules=auth&action=register');
    }
    

}


$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
$error = getFlashData('error');

$old = getFlashData('old');
    // echo '<pre>';
    // print_r($old);
    // echo '</pre>';
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 mt-5">
            <div class="card">
                <div class="card-header bg-info">
                    <h2 class="text-center text-uppercase">Đăng Ký user</h2>
                </div>
                <div class="card-body">
                    <?php if(!empty($smg)): ?>
                    <div class="alert alert-<?php echo $smg_type; ?>" role="alert">
                        <?php echo $smg; ?>
                    </div>
                    <?php endif; ?>
                    <form action="" method="post">
                        <div class="form-group">
                            <label for="fullname">Họ Tên</label>
                            <input name="fullname" type="fullname" class="form-control" id="fullname"
                                placeholder="Họ Tên" value="<?php echo old('fullname', $old);?>">
                            <?php echo form_error('fullname', '<span class="error">', '</span>', $error); ?>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input name="email" type="email" class="form-control" id="email" placeholder="Địa chỉ email"
                                value="<?php echo old('email', $old);?>">
                            <?php echo form_error('email', '<span class="error">', '</span>', $error); ?>
                        </div>
                        <div class="form-group">
                            <label for="phone">Số điện thoại</label>
                            <input name="phone" type="phone" class="form-control" id="phone" placeholder="Số điện thoại"
                                value="<?php echo old('phone', $old); ?>">
                            <?php echo form_error('phone', '<span class="error">', '</span>', $error); ?>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input name="password" type="password" class="form-control" id="password"
                                placeholder="Password" value="<?php echo old('password', $old); ?>">
                            <?php echo form_error('password', '<span class="error">', '</span>', $error); ?>
                        </div>
                        <div class="form-group">
                            <label for="passwordconfirm">Nhập lại password</label>
                            <input name="passwordconfirm" type="password" class="form-control" id="passwordconfirm"
                                placeholder="Nhập lại mật khẩu" value="<?php echo old('passwordconfirm', $old); ?>">
                            <?php echo form_error('passwordconfirm', '<span class="error">', '</span>', $error); ?>
                        </div>
                        <button type="submit" class="btn btn-info btn-block">Đăng Ký</button>
                    </form>
                    <hr>
                    <p class="text-center">Đã có tài khoản? <a href="?modules=auth&action=login">Đăng nhập</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
layouts('footer_lr');
?>