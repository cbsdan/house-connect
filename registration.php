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
                    echo "<script> $sql '<br>' $conn->error</script>";
                    exit();
                }

            } 
            
            echo "<script>alert('Registration successful!');</script>";
            echo "<script>window.location.href = 'login.php';</script>";
            exit(); // Exit PHP to prevent further execution
        } else {
            echo "<script> $sql '<br>' $conn->error</script>";
            exit();
        }
    }
?>
