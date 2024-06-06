<?php
if (!defined('_CODE')) {
    die('Access denied....');
}

$room_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$room = oneRaw("SELECT * FROM rooms
                INNER JOIN typeroom ON rooms.type_id = typeroom.type_id
                WHERE rooms.room_id = $room_id;");

if (isPost()) {
        // Ensure data has been sent
    if(isLogin()){
        if (isset($_POST['comment']) && isset($_POST['rating'])) {
            // Get data from form
            $comment = $_POST['comment'];
            $rating = intval($_POST['rating']);
            
            $tokenLogin = getSession('tokenlogin'); 
            $queryToken = oneRaw("SELECT user_id FROM tokenlogin WHERE token = '$tokenLogin'");
            $user_id = $queryToken['user_id'];
            // Save review to database using insert function
            $data1 = [
                'user_id' => $user_id,
                'room_id' => $room_id,
                'rating' => $rating,
                'comment' => $comment
            ];
    
            $result = insert('reviews', $data1);
    
            if ($result) {
                // Notify user and redirect after successful submission
                setFlashData('success', 'Đánh giá của bạn đã được lưu thành công.');
                redirect("?modules=Room&action=room_detail&id=$room_id");
            } else {
                // Handle error if review could not be saved
                setFlashData('error', 'Đã xảy ra lỗi khi lưu đánh giá.');
                redirect("?modules=Room&action=room_detail&id=$room_id");
            }
        } else {
            // Handle error if data is invalid
            setFlashData('error', 'Vui lòng điền đầy đủ thông tin.');
            redirect("?modules=Room&action=room_detail&id=$room_id");
        }}else{
            echo "<script>alert('Bạn phải đăng nhập!'); window.location.href='?modules=Room&action=room_detail&id=$room_id';</script>";
        }
}

$data = [
    'pageTitle' => 'Room Detail'
];
layouts('header_lr', $data);
?>
<div class="container">
    <?php require_once('templaces/layout/pagehead.php'); ?>

    <?php if (!empty($room)) : ?>
    <div class="room-details">
        <div class="room-image mb-3">
            <img class="img-fluid" src="<?php echo _WEB_HOST_TEMPLACES ?>/<?php echo $room['image_url']; ?>" alt=""
                class="img-fluid rounded">
        </div>
        <div class="room-info">
            <h1 class="mb-4">Phòng <span class="text-primary text-uppercase"><?php echo $room['room_number']; ?></span>
            </h1>
            <p>Type: <?php echo $room['type_name']; ?></p>
            <p>Price: <?php echo $room['price_room']; ?> per night</p>
            <p>Beds: <?php echo $room['beds']; ?></p>
            <p>Bathrooms: <?php echo $room['bathrooms']; ?></p>
            <div class="room-dep">
                <p>Description: <?php echo $room['description']; ?></p>
            </div>
            <a class="btn btn-sm btn-dark rounded py-2 px-4"
                href="?modules=Pages&action=booking&id=<?php echo $room['room_id']; ?>">Book Now</a>
        </div>
    </div>





    <div class="review mt-5">
        <form id="reviewForm" method="post" action="">
            <label for="review">Đánh giá của bạn</label>
            <textarea id="review" name="comment" rows="4" placeholder="Nhập đánh giá của bạn..."></textarea>
            <div class="rating">
                <input type="hidden" id="rating" name="rating" value="0">
                <span class="star" data-value="1">&#9733;</span>
                <span class="star" data-value="2">&#9733;</span>
                <span class="star" data-value="3">&#9733;</span>
                <span class="star" data-value="4">&#9733;</span>
                <span class="star" data-value="5">&#9733;</span>
            </div>
            <button type="submit">Gửi đánh giá</button>
        </form>
    </div>

    <script>
    document.querySelectorAll('.star').forEach(star => {
        star.addEventListener('click', () => {
            document.querySelectorAll('.star').forEach(s => s.classList.remove('selected'));
            star.classList.add('selected');
            document.getElementById('rating').value = star.getAttribute('data-value');
        });
    });
    </script>
    <?php else : ?>
    <p>Room not found.</p>
    <?php endif; ?>
</div>

<?php layouts('footer'); ?>