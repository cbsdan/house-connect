<?php 
    //Check first if the user is logged in
    session_start();
    if (isset($_SESSION['userType'])) {
        if ($_SESSION['userType'] == 'Worker') {
            header('Location: ./worker/application.php');
        } else if ($_SESSION['userType'] == 'Employer') {
            header('Location: ./employer/find_a_worker.php');
        }
        exit();
    }

    include_once('./includes/header.php');
    include_once('./php_files/home.php');
    include_once('./includes/footer.php');

?>


