<!-- Testimonial Start -->
<div class="container-xxl testimonial my-5 py-5 bg-dark wow zoomIn" data-wow-delay="0.1s">
    <div class="container">
        <div class="owl-carousel testimonial-carousel py-5">
            <?php
            // Lấy đánh giá từ cơ sở dữ liệu
            $reviews = getRaw("SELECT * FROM reviews INNER JOIN users ON users.id = reviews.user_id");

            // Mảng chứa đường dẫn tới các ảnh ngẫu nhiên
            $random_images = [
                _WEB_HOST_TEMPLACES . "/img/team-1.jpg",
                _WEB_HOST_TEMPLACES . "/img/team-2.jpg",
                _WEB_HOST_TEMPLACES . "/img/team-3.jpg",
                _WEB_HOST_TEMPLACES . "/img/team-4.jpg"
            ];

            // Kiểm tra nếu có đánh giá
            if (!empty($reviews)) {
                // Vòng lặp qua từng đánh giá
                foreach ($reviews as $review) {
                    // Chọn ngẫu nhiên một ảnh từ mảng
                    $random_image = $random_images[array_rand($random_images)];
            ?>
            <div class="testimonial-item position-relative bg-white rounded overflow-hidden d-flex flex-column">
                <p class="flex-grow-1"><?php echo htmlspecialchars($review['comment']); ?></p>
                <div class="d-flex align-items-center mt-3">
                    <img class="img-fluid flex-shrink-0 rounded" src="<?php echo $random_image; ?>"
                        style="width: 45px; height: 45px;">
                    <div class="ps-3">
                        <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($review['fullname']); ?></h6>
                        <small>Customer</small> <!-- Có thể thay 'Profession' bằng dữ liệu động nếu có -->
                    </div>
                </div>
                <i class="fa fa-quote-right fa-3x text-primary position-absolute end-0 bottom-0 me-4 mb-n1"></i>
            </div>
            <?php
                }
            } else {
                // Hiển thị thông báo nếu không có đánh giá nào
                echo "<p>Không có đánh giá nào cho phòng này.</p>";
            }
            ?>
        </div>
    </div>
</div>
<!-- Testimonial End -->

<script>
$(document).ready(function() {
    $(".owl-carousel").owlCarousel({
        items: 1,
        loop: true,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true
    });
});
</script>

<style>
.testimonial-item {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 60%;
    padding: 20px;
    box-sizing: border-box;
    min-height: 200px;
    /* Chiều cao tối thiểu cho các mục đánh giá */
}

.testimonial-item p {
    flex-grow: 1;
}

.testimonial-item .d-flex {
    margin-top: auto;
}
</style>