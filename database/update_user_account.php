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
        
        if ($userType == 'Employer') {
            $sql = ""; // Initialize the SQL query string
        
            // Check if profile picture is uploaded
            if (isset($_FILES['employerProfilePic']) && is_uploaded_file($_FILES['employerProfilePic']['tmp_name'])) {
                $profilePic = addslashes(file_get_contents($_FILES['employerProfilePic']['tmp_name']));
                $sql .= "UPDATE employer SET profilePic = '$profilePic' WHERE idUser = $userId;";
            }
            
            // Check if valid ID is uploaded
            if (isset($_FILES['employerValidId']) && is_uploaded_file($_FILES['employerValidId']['tmp_name'])) {
                $validId = addslashes(file_get_contents($_FILES['employerValidId']['tmp_name']));
                $sql .= "UPDATE employer SET validId = '$validId' WHERE idUser = $userId;";
            }
            
            // Execute the SQL query if $sql is not empty
            if ($sql != "") {
                $conn->multi_query($sql);
            }

            $sql = "UPDATE employer SET verifyStatus = '" . $_POST['employerVerifyStatus'] . "' WHERE idUser = " . $userId;
            $conn->query($sql);

        } else if ($userType == 'Worker') {
            // Construct the SQL query to check for the existence of the entity
            $sql = "SELECT * FROM worker WHERE idUser = $userId";

            // Execute the SQL query
            $result = $conn->query($sql);

            // Check if any rows are returned
            if ($result->num_rows == 1) {
                $workerStatus = $_POST['workerStatus'];
                $workerType = $_POST['workerType'];
                $workerVerifyStatus = $_POST['workerVerifyStatus'];
                $paypalEmail = $_POST['paypalEmail'];
                $yearsOfExperience = $_POST['yearsOfExperience'];
                $height = $_POST['height'];

                            
                $sql = "UPDATE worker SET ";
                $sql .= "workerStatus = '$workerStatus', ";
                $sql .= "workerType = '$workerType', ";
                $sql .= "paypalEmail = '$paypalEmail', ";
                $sql .= "yearsOfExperience = '$yearsOfExperience', ";
                $sql .= "verifyStatus = '$workerVerifyStatus', ";
                $sql .= "height = '$height'";
                $sql .= "WHERE idUser = $userId";
                
                $conn->query($sql);

                $sql = "";
                //Check if profile picture is uploaded
                if (isset($_FILES['workerProfilePic']) && is_uploaded_file($_FILES['workerProfilePic']['tmp_name'])) {
                    $workerProfilePic = addslashes(file_get_contents($_FILES['workerProfilePic']['tmp_name']));
                    $sql .= "UPDATE worker SET profilePic = '$workerProfilePic' WHERE idUser = $userId;";
                }

                // Execute the SQL query if $sql is not empty
                if ($sql != "") {
                    $conn->multi_query($sql);
                }
                
            } else {
                echo "Entity does not exist in the worker table";
            }
            $idWorkerDocuments = $_POST['idWorkerDocuments'];

            $sql = "";
            // Check if curriculum vitae picture is uploaded
            if (isset($_FILES['curriculumVitae']) && is_uploaded_file($_FILES['curriculumVitae']['tmp_name'])) {
                $curriculumVitae = addslashes(file_get_contents($_FILES['curriculumVitae']['tmp_name']));
                $sql .= "UPDATE worker_documents SET curriculumVitae = '$curriculumVitae' WHERE idWorkerDocuments = $idWorkerDocuments;";
            }
            // Check if worker valid id picture is uploaded
            if (isset($_FILES['workerValidId']) && is_uploaded_file($_FILES['workerValidId']['tmp_name'])) {
                $workerValidId = addslashes(file_get_contents($_FILES['workerValidId']['tmp_name']));
                $sql .= "UPDATE worker_documents SET validID = '$workerValidId' WHERE idWorkerDocuments = $idWorkerDocuments;";
            }
            // Check if nbi picture is uploaded
            if (isset($_FILES['nbi']) && is_uploaded_file($_FILES['nbi']['tmp_name'])) {
                $nbi = addslashes(file_get_contents($_FILES['nbi']['tmp_name']));
                $sql .= "UPDATE worker_documents SET nbi = '$nbi' WHERE idWorkerDocuments = $idWorkerDocuments;";
            }
            // Check if medical picture is uploaded
            if (isset($_FILES['medical']) && is_uploaded_file($_FILES['medical']['tmp_name'])) {
                $medical = addslashes(file_get_contents($_FILES['medical']['tmp_name']));
                $sql .= "UPDATE worker_documents SET medical = '$medical' WHERE idWorkerDocuments = $idWorkerDocuments;";
            }
            // Check if certificate picture is uploaded
            if (isset($_FILES['certificate']) && is_uploaded_file($_FILES['certificate']['tmp_name'])) {
                $certificate = addslashes(file_get_contents($_FILES['certificate']['tmp_name']));
                $sql .= "UPDATE worker_documents SET certificate = '$certificate' WHERE idWorkerDocuments = $idWorkerDocuments;";
            }
            
            // Execute the SQL query if $sql is not empty
            if ($sql != "") {
                $conn->multi_query($sql);
            }
        }

        header('Location: ../admin/user_accounts.php');
        exit();

    }
?>