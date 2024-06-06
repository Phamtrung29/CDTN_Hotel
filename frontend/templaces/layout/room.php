<div class="container-xxl bg-white p-0">

    <?php require_once('templaces/layout/pagehead.php'); ?>
    <?php require_once('templaces/layout/book.php'); ?>

    <!-- Room Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title text-center text-primary text-uppercase">Our Rooms</h6>
                <h1 class="mb-5">Explore Our <span class="text-primary text-uppercase">Rooms</span></h1>
            </div>
            <div class="row g-4">
                <?php
                    // Lấy dữ liệu về các dịch vụ từ cơ sở dữ liệu
                    $rooms = getRaw("SELECT * FROM rooms INNER JOIN typeroom ON rooms.type_id = typeroom.type_id;");
                    if (!empty($rooms)){
                        foreach ($rooms as $room) {
                ?>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="room-item shadow rounded overflow-hidden d-flex flex-column">
                        <div class="position-relative">
                            <img class="img-fluid"
                                src="<?php echo _WEB_HOST_TEMPLACES?>/<?php echo $room['image_url']; ?>" alt="">
                            <small
                                class="position-absolute start-0 top-100 translate-middle-y bg-primary text-white rounded py-1 px-3 ms-4"><?php echo $room['price_room']?>/Night</small>
                        </div>
                        <div class="p-4 mt-2 d-flex flex-column flex-grow-1">
                            <div class="d-flex justify-content-between mb-3">
                                <h5 class="mb-0"><?php echo $room['type_name']; ?></h5>
                                <div class="ps-2">
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <small class="border-end me-3 pe-3"><i
                                        class="fa fa-bed text-primary me-2"></i><?php echo $room['beds']; ?></small>
                                <small class="border-end me-3 pe-3"><i
                                        class="fa fa-bath text-primary me-2"></i><?php echo $room['bathrooms']; ?></small>
                                <small><i class="fa fa-wifi text-primary me-2"></i>Wifi</small>
                            </div>
                            <p class="text-body mb-3 flex-grow-1"><?php echo $room['description']; ?></p>
                            <div class="d-flex justify-content-between mt-auto">
                                <a class="btn btn-sm btn-primary rounded py-2 px-4"
                                    href="?modules=Room&action=room_detail&id=<?php echo $room['room_id']; ?>">View
                                    Detail</a>
                                <a class="btn btn-sm btn-dark rounded py-2 px-4"
                                    href="?modules=Pages&action=booking&id=<?php echo $room['room_id'];?>">Book Now</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
                        }
                    }
                ?>
            </div>
        </div>
    </div>
    <!-- Room End -->

</div>

<style>
.room-item {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.room-item img {
    max-height: 200px;
    /* Set a maximum height for the image */
    width: 100%;
    object-fit: cover;
}

.room-item .p-4 {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.room-item .p-4 .mb-3 {
    margin-bottom: auto;
}
</style>