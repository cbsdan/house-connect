<?php
    include_once('./connect.php'); 
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
        $workType = $_POST['workType'];
        $yearsOfExperience = $_POST['yearsOfExperience'];
        $height = $_POST['height'];
        $profilePic = addslashes(file_get_contents($_FILES['profilePic']['tmp_name']));
    
        $curriculumVitae = addslashes(file_get_contents($_FILES['curriculumVitae']['tmp_name']));
        $validID = addslashes(file_get_contents($_FILES['validID']['tmp_name']));
        $nbi = addslashes(file_get_contents($_FILES['nbi']['tmp_name']));
        $medical = addslashes(file_get_contents($_FILES['medical']['tmp_name']));

        if (isset($_FILES['certifications']) && is_uploaded_file($_FILES['certifications']['tmp_name'])) {
            $certifications = addslashes(file_get_contents($_FILES['certifications']['tmp_name']));
        } else {
            $certifications = null;
        }

        $userId = $_SESSION['idUser'];
       
        $insertDocumentsQuery = "INSERT INTO worker_documents (curriculumVitae, validID, nbi, medical, certificate) 
                VALUES ('$curriculumVitae', '$validID', '$nbi', '$medical', '$certifications')";
        $result = mysqli_query($conn, $insertDocumentsQuery);

        if ($result) {
            $lastInsertedId = mysqli_insert_id($conn);
        } else {
        echo "Error: " . mysqli_error($conn);
        }

        $insertWorkerQuery = "INSERT INTO worker (workerType, workerStatus, yearsOfExperience, verifyStatus, height, idUser, profilePic, idWorkerDocuments) 
                                VALUES ('$workType', 'Unavailable', '$yearsOfExperience', 'Not Verified', '$height', '$userId', '$profilePic', $lastInsertedId)";
        $result = mysqli_query($conn, $insertWorkerQuery);
    
    
        if ($result) {
            header("Location: ../worker/application.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }

?>