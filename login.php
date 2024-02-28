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
    include_once('./php_files/login.php');
    include_once('./includes/footer.php');
    include_once('./database/connect.php');

    if(isset($_POST['submit'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
    
        $sql = "SELECT * FROM user WHERE email = '$email'";
        $result = $conn->query($sql);
    
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
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
                    header("Location: ./worker/application.php");
                } else if ($user['userType'] == 'Employer') {
                    header("Location: ./employer/find_a_worker.php");
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
