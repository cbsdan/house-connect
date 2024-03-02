<?php
    include_once('./connect.php'); 
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userId = $_POST['userId'];
        $userType = $_POST['usertype'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $sex = $_POST['sex'];
        $birthdate = $_POST['birthdate'];
        $address = $_POST['address'];
        $contactNo = $_POST['contactNo'];

        // if ($userType == 'Worker') {
        //     //check if a user have an account on worker table
        //     $sql = "SELECT idWorker FROM worker WHERE idUser = $idUser";

        //     // Execute the SQL query
        //     $result = $conn->query($sql);

        //     // Check if any rows are returned (indicating the user exists in the worker table)
        //     if ($result->num_rows == 1) {
        //         $isWorkerExists = true;
        //     } else {
        //         $isWorkerExists = false;
        //     }
        // }

        // if (isset($_FILES['profilePic'])) {
        //     $profilePic = addslashes(file_get_contents($_FILES['profilePic']['tmp_name']));
        // } 

        // Construct the SQL query
        // if (isset($profilePic)) {
            //     $sql .= "profilePic = '$profilePic', ";
            // }
            
        $sql = "UPDATE user SET ";
        $sql .= "fname = '$fname', ";
        $sql .= "lname = '$lname', ";
        $sql .= "email = '$email', ";
        $sql .= "password = '$password', ";
        $sql .= "sex = '$sex', ";
        $sql .= "birthdate = '$birthdate', ";
        $sql .= "address = '$address', ";
        $sql .= "contactNo = '$contactNo' ";
        $sql .= "WHERE idUser = $userId";

        $conn->query($sql);
        header('Location: ../admin/user_accounts.php');
        exit();

    }
?>