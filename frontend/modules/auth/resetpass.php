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
<div class="row">
    <div class="col-4 mg-home" style="margin: 50px auto;">
        <h2 class="text-center text-uppercase">Đặt lại mật khẩu</h2>
        <?php
            if(!empty($msg)){
                getSmg($msg, $msg_type);
            } 
        ?>
        <form action="" method="post">

            <div class=" form-group">
                <label for="">Password</label>
                <input name="password" type="password" class="form-control" placeholder="Password">
                <?php 
                echo form_error('password', '<span class="error">', '</span>', $error);
                ?>
            </div>
            <div class=" form-group">
                <label for="">Nhập lại password</label>
                <input name="passwordconfirm" type="password" class="form-control" placeholder="Nhập lại mật khẩu">
                <?php
                echo form_error('passwordconfirm', '<span class="error">', '</span>', $error);
                ?>
            </div>
            <input type="hidden" name="token" value="<?php echo $token ?>">
            <div class=" mg-button">
                <button type="submit" class="btn btn-primary btn-block">Gửi</button>
            </div>
            <hr>
            <p class="text-center"><a href="?modules=auth&action=login">Đăng nhập user</a></p>
        </form>
    </div>
</div>
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