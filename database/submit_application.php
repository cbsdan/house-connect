<?php
    include_once('./connect.php'); 
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
        $workType = $_POST['workType'];
        $yearsOfExperience = $_POST['yearsOfExperience'];
        $height = $_POST['height'];
        $profilePic = $_FILES['profilePic']['name'];
    
        $curriculumVitae = $_FILES['curriculumVitae']['name'];
        $validID = $_FILES['validID']['name'];
        $nbi = $_FILES['nbi']['name'];
        $medical = $_FILES['medical']['name'];

        $userId = $_SESSION['idUser'];
       
        $insertDocumentsQuery = "INSERT INTO worker_documents (curriculumVitae, validID, nbi, medical, certificate) 
                VALUES ('$curriculumVitae', '$validID', '$nbi', '$medical', '$certifications')";
        $result = mysqli_query($conn, $insertDocumentsQuery);

        if ($result) {
            $lastInsertedId = mysqli_insert_id($conn);
        } else {
        echo "Error: " . mysqli_error($conn);
        }

        $insertWorkerQuery = "INSERT INTO worker (workerType, yearsOfExperience, verifyStatus, height, idUser, profilePic, idWorkerDocuments) 
                                VALUES ('$workType', '$yearsOfExperience', 'Not Verified', '$height', '$userId', '$profilePic', $lastInsertedId)";
        $result = mysqli_query($conn, $insertWorkerQuery);
    
    
        if ($result) {
            header("Location: ../worker/application.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }

?>