<?php
if(!defined('_CODE')){
    die('Access denied....');
}

$data = [
    'pageTitle' => 'Testimonial'
];
layouts('header_lr', $data);

// if(!isLogin()){
//     redirect('?modules=auth&action=login');
// }
?>


<body>
    <div class="container-xxl bg-white p-0">
        <?php require_once('templaces/layout/pagehead.php'); ?>
        <?php require_once('templaces/layout/book.php'); ?>
        <?php require_once('templaces/layout/comment.php'); ?>


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

</body>

<?php
layouts('footer', $data);
?>