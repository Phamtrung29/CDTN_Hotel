<?php
if (!defined('_CODE')) {
    die('Access denied....');
}

if (!isLogin()) {
    redirect('?modules=auth&action=login');
}

$order_id = $_POST['order_id'];
$order = oneRaw("SELECT * FROM orderbooking WHERE order_id = $order_id");

// Tạo mã xác nhận ngẫu nhiên (có thể tùy chỉnh theo yêu cầu)
$confirmation_code = strtoupper(substr(md5(time() . $order_id), 0, 8));

// Cập nhật mã xác nhận vào cơ sở dữ liệu
$update = update('orderbooking', ['confirmation_code' => $confirmation_code], "order_id = $order_id");

if ($update) {
    $data = [
        'pageTitle' => 'Check-in Confirmation',
        'confirmation_code' => $confirmation_code
    ];
    layouts('header_lr', $data);
    ?>
<div class="container">
    <h2>Check-in Confirmation</h2>
    <p>Your confirmation code is: <strong><?php echo $confirmation_code; ?></strong></p>
    <p>Please show this code to the staff to complete your check-in.</p>
</div>
<?php
    layouts('footer', $data);
} else {
    setFlashData('msg', 'Failed to generate confirmation code.');
    setFlashData('msg_type', 'danger');
    redirect('?modules=home&action=myroom');
}
?>