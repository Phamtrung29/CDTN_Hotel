<?php
if (!defined('_CODE')) {
    define('_CODE', true);
}
$data = [
    'pageTitle' => 'Admin Dashboard'
];
layouts('header', $data);



$query_monthly_earnings = "SELECT MONTH(check_in_date) as month, SUM(total_price) as monthly_earnings FROM orderbooking WHERE YEAR(check_in_date) = YEAR(CURDATE()) AND payment_status = 'paid' GROUP BY month";
$stmt_monthly_earnings = $conn->query($query_monthly_earnings);
$monthly_earnings = $stmt_monthly_earnings->fetchAll(PDO::FETCH_ASSOC);

$query_yearly_earnings = "SELECT YEAR(check_in_date) as year, SUM(total_price) as yearly_earnings FROM orderbooking WHERE payment_status = 'paid' GROUP BY year";
$stmt_yearly_earnings = $conn->query($query_yearly_earnings);
$yearly_earnings = $stmt_yearly_earnings->fetchAll(PDO::FETCH_ASSOC);

$countPd = getRow("SELECT * FROM orderbooking WHERE order_status = 'Pending'")

?>

<div id="content">
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        </div>

        <!-- Content Row -->
        <div class="row">
            <?php
                            foreach ($monthly_earnings as $monthly_earning):
                            ?>
            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">

                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Earnings (Monthly): <?php echo $monthly_earning['month']; ?></div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php echo $monthly_earning['monthly_earnings']; ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <?php
                endforeach;
            ?>
            <?php  foreach ($yearly_earnings as $yearly_earning):?>
            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Earnings (Annual): <?php echo $yearly_earning['year']; ?></div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php echo $yearly_earning['yearly_earnings']; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                endforeach;
            ?>


            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Pending Requests</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $countPd; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-comments fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row justify-content-center">
            <!-- Area Chart -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-header">Dropdown Header:</div>
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="myAreaChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Content Row -->


    </div>
    <!-- /.container-fluid -->

</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Lấy dữ liệu thu nhập hàng tháng từ PHP và chuyển sang JavaScript
    const monthlyEarnings = <?php echo json_encode($monthly_earnings); ?>;

    // Tạo mảng chứa các tháng và doanh thu tương ứng
    const months = monthlyEarnings.map(item => 'Tháng ' + item.month);
    const earnings = monthlyEarnings.map(item => item.monthly_earnings);

    // Tạo biểu đồ tuyến tính
    const ctx = document.getElementById("myAreaChart").getContext('2d');
    const myAreaChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Earnings',
                data: earnings,
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    ticks: {
                        beginAtZero: true
                    },
                    grid: {
                        color: "rgba(234, 236, 244, 1)"
                    }
                }
            }
        }
    });
});
</script>