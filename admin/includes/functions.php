<!-- Các hàm chung của project--->
<?php
if(!defined('_CODE')){
    die('Access denied....');
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
function layouts($layoutName = 'header', $data = []){
    if(file_exists(_WEB_PATH_TEMPLACES .'/layout/'.$layoutName.'.php')){
        require_once(_WEB_PATH_TEMPLACES .'/layout/'.$layoutName.'.php');
    }
}

//Hàm Gửi mail
function sendMail($to, $subject, $content){
//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'phamductrung2907@gmail.com';                     //SMTP username
    $mail->Password   = 'dfoy tldb bpmm vjkm';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('phamductrungtp29@gmail.com', 'trung29');
    $mail->addAddress($to);     //Add a recipient


    //Content
    $mail -> CharSet = "UTF-8";
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $content;

    $mail -> SMTPOptions = array(
        'ssl' => array(
            'verity_peer' => false,
            'verity_peer_name' => false,
            'allow_self_signed' => true
        )

        );


    $sendMail =  $mail->send();
    if($sendMail){
        return $sendMail;
    }
    // echo 'Gửi mail thành công!!';
} catch (Exception $e) {
    echo "Gửi mail thất bại!. Mailer Error: {$mail->ErrorInfo}";
}
}

//Kiểm tra phương thức Get
function isGet(){
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        return true;
    }
    return false;
}
function isPost(){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        return true;
    }
    return false;
}

function filter(){
    $filterArr = [];
    if(isGet()){
        // return $_GET;
        if(!empty($_GET)){
            foreach($_GET as $key => $value){
                $key = strip_tags($key);
                if(is_array($value)){
                $filterArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                }else{
                    $filterArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }
    }
    if(isPost()){
        // return $_GET;
        if(!empty($_GET)){
            foreach($_POST as $key => $value){
                $key = strip_tags($key);
                if(is_array($value)){
                    $filterArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                }else{
                    $filterArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
                
            }
        }
    }
    return $filterArr;
}

//Kiểm tra email
function isEmail($email){
    $checkEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
    return $checkEmail;
}
function isNumberInt($num){
    $checkNumber = filter_var($num, FILTER_VALIDATE_INT);
    return $checkNumber;
}

function isPhone($phone){
    $checkZero = true;
    
    if($phone[0] == 0){
        $checkZero = true;
        $phone = substr($phone,1);
    }

    $checkNumber = false;
    if(isNumberInt($phone) && strlen($phone) == 9){
        $checkNumber = true;
    }
    if($checkZero && $checkNumber){
        return true;
    }
    return false;
}

function form_error($fileName, $beforeHTML, $afterHTML, $error){
    return (!empty($error[$fileName])) ? $beforeHTML.reset($error[$fileName]).$afterHTML : null;
}

function old($fileName, $oldData, $default = null){
    return (!empty($oldData[$fileName]) ? $oldData[$fileName] : $default);
}

function isLogin(){
    $checkLogin = false;
    if(getSession('tokenlogin')){
        $tokenLogin = getSession('tokenlogin'); 
        $queryToken = oneRaw("SELECT u.id, u.role FROM tokenlogin t JOIN users u ON t.user_id = u.id WHERE t.token = '$tokenLogin'");
        if(!empty($queryToken)){
            $role = $queryToken['role'];
            if($role == 'admin'){
                $checkLogin = true;
            }
        }else{
            removeSession('tokenlogin');
        }
    }
    return $checkLogin;
}