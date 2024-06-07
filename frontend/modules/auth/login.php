<!--- Đăng nhập -->
<?php

if(!defined('_CODE')){
    die('Access denied....');
}
$data = [
    'pageTitle' => 'Đăng nhập tài khoản'
];

layouts('header', $data);
if(isLogin()){
    redirect('?modules=home&action=dashboard');
}


if(isPost()){
    $filterAll = filter();
    if(!empty(trim($filterAll['email']) && !empty(trim($filterAll['password'])))){
        $email = $filterAll['email'];
        $password = $filterAll['password'];

       //truy vấn lấy thông tin users theo email
       $userQuery = oneRaw("SELECT password, id FROM users WHERE email = '$email'");
       if(!empty($userQuery)) {

        $passwordHash = $userQuery['password'];
        $use_id = $userQuery['id'];
        if(password_verify($password, $passwordHash)){
            //tạo token
            $tokenLogin = sha1(uniqid().time());
            //kiểm tra xem tk login chưa
            $userLogin = getRows("SELECT * FROM tokenlogin WHERE user_id = '$use_id'");
            if($userLogin > 1){
                setFlashData('msg', 'Tài khoản được đăng nhập ở nơi khác!');
                setFlashData('msg_type', 'danger');
                redirect('?modules=auth&action=login');
            }else{

                //insert bảng
                $dataInsert = [
                    'user_id' => $use_id,
                    'token' => $tokenLogin,
                    'create_at' => date('Y-m-d H:i:s'),
                    'expiry_date' => date('Y-m-d H:i:s', strtotime('+1 hour'))
                ];  
                $insertStatus = insert('tokenlogin', $dataInsert);
                if($insertStatus){
                    //insert thành công
    
                    //lưu vào session
                    setSession('tokenlogin', $tokenLogin);
    
                    redirect('?modules=home&action=dashboard');
                }else{
                    setFlashData('msg', 'Không thể đăng nhập vui lòng thử lại sau!');
                    setFlashData('msg_type', 'danger');
    
                }
            }
        }else{
            setFlashData('msg', 'Mật khẩu không chính xác!');
            setFlashData('msg_type', 'danger');
            redirect('?modules=auth&action=login');
        }
       }else{
        setFlashData('msg', 'Email không tồn tại');
        setFlashData('msg_type', 'danger');
        redirect('?modules=auth&action=login');
       }
        
        
    }else{
        setFlashData('msg', 'Vui lòng nhập email và mật khẩu!');
        setFlashData('msg_type', 'danger');
        redirect('?modules=auth&action=login');
    }
}   
$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');


?>
<div class="container height-5" id="wrapper1">
    <div class="row justify-content-center ">
        <div class="col-md-4">
            <div class="card mt-5">
                <div class="card-header bg-info">
                    <h3 class="text-center">Đăng nhập user</h3>
                </div>
                <div class="card-body">
                    <?php if(!empty($msg)): ?>
                    <div class="alert alert-<?php echo $msg_type; ?>" role="alert">
                        <?php echo $msg; ?>
                    </div>
                    <?php endif; ?>
                    <form action="" method="post">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input name="email" type="email" class="form-control" id="email"
                                placeholder="Địa chỉ email">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input name="password" type="password" class="form-control" id="password"
                                placeholder="Password">
                        </div>
                        <button type="submit" class="btn btn-info btn-block mt-2">Đăng Nhập</button>
                    </form>
                    <hr>
                    <p class="text-center"><a href="?modules=auth&action=forgot">Quên mật khẩu</a></p>

                    <p class="text-center"><a href="?modules=auth&action=register">Đăng ký</a></p>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
layouts('footer_lr');
?>