<?php 
    //Check first if the user is logged in
    session_start();
    if (isset($_SESSION['userType'])) {
        if ($_SESSION['userType'] == 'Worker') {
            header('Location: ./worker/application.php');
        } else if ($_SESSION['userType'] == 'Employer') {
            header('Location: ./employer/account_profile.php');
        } else {
            header('Location: ./admin/dashboard.php');

        }
        exit();
    }

    include_once('./includes/headerabout.php');
    include_once('./php_files/about.php');
    include_once('./includes/footer.php');

?>
