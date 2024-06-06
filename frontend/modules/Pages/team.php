<?php
if(!defined('_CODE')){
    die('Access denied....');
}


$data = [
    'pageTitle' => 'Our team'
];
layouts('header_lr', $data);

// if(!isLogin()){
//     redirect('?modules=auth&action=login');
// }
?>


<body>
    <?php require_once("templaces/layout/team.php") ?>

</body>


<?php
layouts('footer', $data);
?>