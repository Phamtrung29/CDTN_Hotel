<?php
if (!defined('_CODE')) {
    die('Access denied....');
}

if (isPost()) {
    if(isset($_POST['booking_id'])) {
        // Lấy giá trị booking_id từ form
        $booking_id = $_POST['booking_id'];
        delete('bookings', "booking_id='$booking_id'");

    setFlashData('msg', 'Room removed from cart successfully.');
    setFlashData('msg_type', 'success');
    redirect('?modules=home&action=cart');
} else {
    redirect('?modules=home&action=dashboard');
}
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Booking</title>
</head>

<body>
    <h2>Delete Booking</h2>
    <p>Are you sure you want to delete this booking?</p>
    <form action="" method="POST">
        <input type="hidden" name="booking_id"
            value="<?php echo isset($_POST['booking_id']) ? $_POST['booking_id'] : ''; ?>">
        <button type="submit">Yes, Delete</button>
        <a href="index.php">No, Cancel</a>
    </form>
</body>

</html>