<?php 
    include_once('./includes/header.php');
    include_once('./php_files/login.php');
    include_once('./includes/footer.php');
    include_once('./database/connect.php');

    //Check first if the user is logged in
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
    
    if(isset($_POST['submit'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
    
        $user = $userObj -> getUserByConditions(['email' => $email]);

        if ($user != false) {
            $user = $user[0];

            if ($password === $user['password']) {
                $_SESSION['idUser'] = $user['idUser'];
                $_SESSION['userType'] = $user['userType'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['fname'] = $user['fname'];
                $_SESSION['lname'] = $user['lname'];
                $_SESSION['sex'] = $user['sex'];
                $_SESSION['birthdate'] = $user['birthdate'];
                $_SESSION['address'] = $user['address'];
                $_SESSION['contactNo'] = $user['contactNo'];

                if($user['userType'] == "Worker") {
                    echo "<script>alert('Successful!');</script>";
                    header("Location: ./worker/application.php");
                } else if ($user['userType'] == 'Employer') {
                    echo "<script>alert('Successful!');</script>";
                    header("Location: ./employer/find_a_worker.php");
                } else {
                    echo "<script>alert('Successful!');</script>";
                    header("Location: ./admin/dashboard.php");
                }
                exit();
            } else {
                echo "<script>alert('Incorrect password. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('User with provided email does not exist.');</script>";
        }
    }
?>