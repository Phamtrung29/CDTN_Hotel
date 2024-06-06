<?php
if (!defined('_CODE')) {
    die('Access denied....');
}

$data = [
    'pageTitle' => 'Search Results'
];

layouts('header_lr', $data);

?>

<body>
    <div class="container-fluid bg-light p-5">
        <div class="container bg-white p-4 rounded shadow">
            <h1 class="text-center mb-5">Search Results</h1>

            <?php
            $check_in = isset($_GET['check_in']) ? $_GET['check_in'] : '';
            $check_out = isset($_GET['check_out']) ? $_GET['check_out'] : '';
            $beds = isset($_GET['beds']) ? $_GET['beds'] : '';
            $bathrooms = isset($_GET['bathrooms']) ? $_GET['bathrooms'] : '';
            $room_name = isset($_GET['room_name']) ? $_GET['room_name'] : '';
            $room_id = isset($_GET['room_id']) ? $_GET['room_id'] : '';

            // Tạo câu truy vấn tìm kiếm
            $query = "SELECT * FROM rooms WHERE 1=1";

            if ($beds) {
                $query .= " AND beds = $beds";
            }
            if ($bathrooms) {
                $query .= " AND bathrooms = $bathrooms";
            }
            if ($room_name) {
                $query .= " AND room_number LIKE '%$room_name%'";
            }
            if ($room_id) {
                $query .= " AND room_id = '$room_id'";
            }

            // Lọc các phòng đã được đặt trong khoảng thời gian yêu cầu
            if ($check_in && $check_out) {
                $query .= " AND room_id NOT IN (
                    SELECT room_id FROM bookings
                    WHERE ('$check_in' < check_out_date) AND ('$check_out' > check_in_date)
                )";
            }

            // Lấy kết quả tìm kiếm từ cơ sở dữ liệu
            $rooms = getRaw($query);

            ?>

            <?php if (!empty($rooms)) : ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Room Image</th>
                            <th scope="col">Room ID</th>
                            <th scope="col">Beds</th>
                            <th scope="col">Bathrooms</th>
                            <th scope="col">Price per Night</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rooms as $room) : ?>
                        <tr>
                            <td class="table-image">
                                <img class="img-fluid centered-image"
                                    src="<?php echo _WEB_HOST_TEMPLACES ?>/<?php echo $room['image_url']; ?>" alt="">
                            </td>
                            <td class="align-middle"><?php echo $room['room_id']; ?></td>
                            <td class="align-middle"><?php echo $room['beds']; ?></td>
                            <td class="align-middle"><?php echo $room['bathrooms']; ?></td>
                            <td class="align-middle"><?php echo $room['price_room']; ?></td>
                            <td><a class="btn btn-sm btn-dark rounded py-2 px-4"
                                    href="?modules=Pages&action=booking&id=<?php echo $room['room_id']; ?>">Book Now</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else : ?>
            <div class="alert alert-warning text-center" role="alert">
                No rooms match your search criteria.
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
</body>

<?php
layouts('footer', $data);
?>