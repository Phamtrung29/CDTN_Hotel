<div class="container-xxl bg-white p-0">

    <?php require_once('templaces/layout/pagehead.php'); ?>
    <?php require_once('templaces/layout/book.php'); ?>



    <!-- Team Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title text-center text-primary text-uppercase">Our Team</h6>
                <h1 class="mb-5">Explore Our <span class="text-primary text-uppercase">Staffs</span></h1>
            </div>

            <div class="row g-4">
                <?php
                    // Lấy dữ liệu về các dịch vụ từ cơ sở dữ liệu
                    $members = getRaw("SELECT * FROM members");
                    if (!empty($members)){
                        foreach ($members as $member) {
                    ?>
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="rounded shadow overflow-hidden">
                        <div class="position-relative">
                            <img class="img-fluid"
                                src="<?php echo _WEB_HOST_TEMPLACES?>/img/<?php echo $member['image_url']; ?>" alt="">
                            <div class="position-absolute start-50 top-100 translate-middle d-flex align-items-center">
                                <a class="btn btn-square btn-primary mx-1" href="<?php echo $member['linkface']; ?>"><i
                                        class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-square btn-primary mx-1" href="<?php echo $member['linkinsta']; ?>"><i
                                        class="fab fa-twitter"></i></a>
                                <a class="btn btn-square btn-primary mx-1"
                                    href="<?php echo $member['linktwitter']; ?>"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                        <div class="text-center p-4 mt-3">
                            <h5 class="fw-bold mb-0"><?php echo $member['full_name']; ?></h5>
                            <small><?php echo $member['position'];?></small>
                        </div>
                    </div>
                </div>
                <?php }}?>
            </div>
        </div>
    </div>
    <!-- Team End -->



    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
</div>