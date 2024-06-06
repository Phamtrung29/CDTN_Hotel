<?php
if(!defined('_CODE')){
    die('Access denied....');
}

$data = [
    'pageTitle' => 'Room'
];
layouts('header_lr', $data);

// if(!isLogin()){
//     redirect('?modules=auth&action=register');
// }

?>


<body>
    <?php require_once("templaces/layout/room.php") ?>

    <?php require_once("templaces/layout/comment.php") ?>
    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>


</body>


<?php
layouts('footer', $data);
?>