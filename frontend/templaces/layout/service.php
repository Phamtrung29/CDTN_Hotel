<?php $sql = "SELECT * FROM Services";
$result = $conn->query($sql);?>

<div class="container-xxl bg-white p-0">

    <?php require_once('templaces/layout/pagehead.php'); ?>
    <?php require_once('templaces/layout/book.php'); ?>



    <!-- Service Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title text-center text-primary text-uppercase">Our Services</h6>
                <h1 class="mb-5">Explore Our <span class="text-primary text-uppercase">Services</span></h1>
            </div>

            <div class="row g-4">
                <?php
                    // Lấy dữ liệu về các dịch vụ từ cơ sở dữ liệu
                    $services = getRaw("SELECT * FROM services");
                    if (!empty($services)) {
                        foreach ($services as $service) {
                            ?>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="service-item rounded">
                        <div class="service-icon bg-transparent border rounded p-1">
                            <div class="w-100 h-100 border rounded d-flex align-items-center justify-content-center">
                                <!-- Icon của dịch vụ -->
                                <i class="<?php echo $service['icons']; ?> fa-2x text-primary"></i>
                            </div>
                        </div>
                        <h5 class="mb-3"><?php echo $service['service_name']; ?></h5>
                        <p class="text-body mb-0 text-primary"><?php echo $service['price']; ?>$</p>
                        <p class="text-body mb-0"><?php echo $service['description']; ?></p>

                    </div>
                </div>
                <?php
                        }
                    }
                    ?>
            </div>
        </div>
    </div>
    <!-- Service End -->