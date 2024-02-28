<?php
    //Check ig a user is logged in or not
    
    session_start();

    if (!isset($_SESSION['userType'])) {
        //Redirect to login page
        if (file_exists('../login.php') && $_SERVER['PHP_SELF'] != '/house-connect/login.php') {
            header('Location: ../login.php');
        } else if (file_exists('./login.php') && $_SERVER['PHP_SELF'] != '/house-connect/login.php') {
            header('Location: ./login.php');
        }
        exit();
    }

?>