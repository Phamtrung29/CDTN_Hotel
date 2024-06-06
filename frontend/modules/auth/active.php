<!--Xác thực -->
<?php
if(!defined('_CODE')){
    die('Access denied....');
}
$data = [
    'pageTitle' => 'Active'
];
layouts('header', $data);
$token = filter()['token'];

if(!empty($token)){
    $tokenQuery = oneRaw("SELECT id FROM users WHERE activeToken = '$token'");
    if(!empty($tokenQuery)){
        $userid = $tokenQuery['id'];
        $dataUpdate = [
            'status' => 1,
            'activeToken' => null
        ];
        $updateStatus = update('users', $dataUpdate, "id='$userid'");
        if($updateStatus){
            setFlashData('msg', 'Kích hoạt thành công bạn có thể đăng nhập ngay bây giờ !');
            setFlashData('msg_type', 'success');
        }else{
            setFlashData('msg', 'Kích hoạt tài khoản không thành công, vui lòng liên hệ quản trị viên!');
            setFlashData('msg_type', 'danger');
        }
        redirect('?modules=auth&action=login');
    }else{
        getSmg('Liên kết không tồn tại hoặc đã hết hạn!!', 'danger');
    }
}else{
    getSmg('Liên kết không tồn tại hoặc đã hết hạn!!', 'danger');
}
?>







<?php
layouts('footer_lr');
?>