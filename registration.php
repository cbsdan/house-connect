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
    
        // Check if the email is a Gmail address
        if (strpos($email, '@gmail.com') === false) {
            echo "<script>alert('Only Gmail accounts are allowed!');</script>";
            exit(); // Exit PHP to prevent further execution
        }
    
        // Calculate age based on birthdate
        $today = new DateTime();
        $diff = $today->diff(new DateTime($birthdate));
        $age = $diff->y;
    
        // Check if the user is at least 18 years old
        if ($age < 18) {
            echo "<script>alert('You must be at least 18 years old to register!');</script>";
            exit(); // Exit PHP to prevent further execution
        }
    
        // Check if email already exists in the database
        $checkEmailQuery = "SELECT * FROM user WHERE email = '$email'";
        $result = $conn->query($checkEmailQuery);
        if ($result->num_rows > 0) {
            echo "<script>alert('This email is already registered!');</script>";
            exit(); // Exit PHP to prevent further execution
        }
    
        // Insert data into the database
        $sql = "INSERT INTO user (userType, email, password, fname, lname, sex, birthdate, address, contactNo) 
        VALUES ('$userType', '$email', '$password', '$fname', '$lname', '$sex', '$birthdate', '$address', '$contactNo')";
    
        if ($conn->query($sql) === TRUE) {
            // Get the ID of the last inserted record
            $lastInsertedId = $conn->insert_id;
    
            if ($userType == 'Employer') {
                $sql = "INSERT INTO employer (verifyStatus, idUser) VALUES ('Not Verified', $lastInsertedId)";
                // Execute the second query
                if ($conn->query($sql) != TRUE) {
                    echo "<script>alert('Error occurred while inserting employer record!');</script>";
                    exit(); // Exit PHP to prevent further execution
                }
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
