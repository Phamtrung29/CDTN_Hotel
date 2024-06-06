<?php
if (!defined('_CODE')) {
    die('Access denied....');
}

// Kiểm tra và include file header
$data = [
    'pageTitle' => 'Xóa Đơn Đặt Phòng'
];
layouts('header', $data);

// Lấy ID của đơn đặt phòng cần xóa từ URL
$order_id = $_GET['id'];

// Xử lý xóa đơn đặt phòng khỏi cơ sở dữ liệu
$result = delete('orderbooking', "order_id = $order_id");

// Kiểm tra xem xóa thành công hay không
if ($result) {
    setFlashData('msg', 'Xóa đơn đặt phòng thành công!');
    setFlashData('msg_type', 'success');
    redirect('?page=orderbooking/list');
} else {
    setFlashData('msg', 'Xóa đơn đặt phòng thất bại!');
    setFlashData('msg_type', 'danger');
}

?>
<?php
layouts('footer');
?>