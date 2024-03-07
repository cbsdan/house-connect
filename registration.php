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

    include_once('./includes/header.php');
    include_once('./php_files/registration.php');
    include_once('./includes/footer.php');
    include_once('./database/connect.php');
    
    require_once('./Classes/User.php');
    require_once('./Classes/Employer.php');

    $userObj = new User($conn);
    $employerObj = new Employer($conn);

    if(isset($_POST['submit'])) {
    
        // Retrieve form data
        $userType = $_POST['userType'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $sex = $_POST['sex'];
        $birthdate = $_POST['birthdate'];
        $address = $_POST['address'];
        $contactNo = $_POST['contact-no'];
        
        $age = calculateAge($birthdate);
    
        // Check if the user is at least 18 years old
        if ($age < 18) {
            echo "<script>alert('You must be at least 18 years old to register!');</script>";
            exit(); // Exit PHP to prevent further execution
        }
        
        $users = $userObj->getUsers();

        if (isset($users)) {
            foreach($users as $user) {
                if ($user['email'] == $email) {
                    echo "<script>alert('This email is already registered!');</script>";
                    exit(); // Exit PHP to prevent further execution
                }
            }
        }
        
        // Insert data into the database
        $newIdUser = $userObj->createUser($fname, $lname, $sex, $birthdate, $email, $password, $userType, $address, $contactNo);

        if ($newIdUser != false ) {
            $employerObj->createEmployer($newIdUser, 'Not Verified'); 

            if ($employerObj != true) {
                echo "<script>alert('Error occurred while inserting employer record!');</script>";
                exit(); // Exit PHP to prevent further execution
            }

            echo "<script>alert('Registration successful!');</script>";
            echo "<script>window.location.href = 'login.php';</script>";
            exit(); // Exit PHP to prevent further execution
        } else {
            echo "<script>alert('Error occurred while inserting user record!');</script>";
            exit(); // Exit PHP to prevent further execution
        }
    }
    
?>
