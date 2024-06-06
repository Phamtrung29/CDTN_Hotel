<!--Quên mật khẩu -->
<?php
if(!defined('_CODE')){
    die('Access denied....');
}
$data = [
    'pageTitle' => 'Quên mật khẩu'
];

layouts('header', $data);
if(isPost()){
    $filterAll = filter();
    if(!empty($filterAll['email'])){
        $email = $filterAll['email'];

        $queryUser = oneRaw("SELECT id FROM users WHERE email = '$email'");
        if(!empty($queryUser)){
            $userid = $queryUser['id'];

            $forgotToken = sha1(uniqid().time());
            $dataUpdate = [
                'forgotToken' => $forgotToken
            ];

            $updateStatus = update('users', $dataUpdate, "id='$userid'");
            if($updateStatus){
                $linkReset = _WEB_HOST.'?modules=auth&action=resetpass&token='.$forgotToken;

                //gửi mail
                $subject = 'Yêu cầu khôi phục mật khẩu!';
                $content = 'Chào bạn.<br>';
                $content .= 'Chúng tôi nhận được yêu cầu khôi phục mật khẩu từ bạn! Vui lòng click vào link sau để đổi mật khẩu!.<br>';
                $content .= $linkReset.'<br>';
                $content .= 'Trân Trọng cảm ơn!';

                $sendMail = sendMail($email, $subject, $content);
                if($sendMail){
                    setFlashData('msg', 'Vui lòng kiểm tra email của bạn để xem hướng dẫn!');
                    setFlashData('msg_type', 'success');
                }else{
                    setFlashData('msg', 'Lỗi hệ thống vui lòng gửi lại sau!(email)');
                    setFlashData('msg_type', 'danger');
                }
            }else{
                setFlashData('msg', 'Lỗi hệ thống vui lòng gửi lại sau!');
                setFlashData('msg_type', 'danger');
            }
        }
    }
        
        
    }else{
        setFlashData('msg', 'Vui lòng nhập email!');
        setFlashData('msg_type', 'danger');
    }
$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');


?>
<div class="row">
    <div class="col-4 mg-home" style="margin: 50px auto;">
        <h2 class="text-center text-uppercase bg-info">Quên mật khẩu</h2>
        <?php
        if(!empty($msg)){
            getSmg($msg, $msg_type);
        } 
        ?>
        <form action="" method="post">
            <div class="form-group mg-form">
                <label for="">Email</label>
                <input name="email" type="email" class="form-control" placeholder="Địa chỉ email">
            </div>
            <div class="mg-button">
                <button type="submit" class="btn btn-info btn-block">Gửi</button>
            </div>
            <hr>
            <p class="text-center"><a href="?modules=auth&action=login">Đăng nhập</a></p>
            <p class="text-center"><a href="?modules=auth&action=register">Đăng ký</a></p>
        </form>
    </div>
</div>

<?php
layouts('footer_lr');
?>