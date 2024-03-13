<?php
    include_once('./connect.php'); 
    require_once('../Classes/Worker.php');
    require_once('../Classes/WorkerDocuments.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $workerObj = new Worker($conn);
        $workerDocumentsObj = new WorkerDocuments($conn);
        $userId = $_SESSION['idUser'];
        
        if (isset($_POST['step2done'])) {

            $workType = $_POST['workType'];
            $yearsOfExperience = $_POST['yearsOfExperience'];
            $height = $_POST['height'];

            $profilePic = getFileContents($_FILES['profilePic']['tmp_name']);
            $curriculumVitae = getFileContents($_FILES['curriculumVitae']['tmp_name']);
            $validID = getFileContents($_FILES['validID']['tmp_name']);

            if (isset($_FILES['certifications']) && is_uploaded_file($_FILES['certifications']['tmp_name'])) {
                $certifications = addslashes(file_get_contents($_FILES['certifications']['tmp_name']));
            } else {
                $certifications = null;
            }
    

            $idWorkerDocuments = $workerDocumentsObj -> createWorkerDocument($curriculumVitae, $validID, null, null, $certifications);

            $idWorker = $workerObj -> createWorker($workType, 'Unavailable', 'Not Verified', 'Pending', $yearsOfExperience, $height, null, $idWorkerDocuments, $userId);
        }

        if (isset($_POST['step4done'])) {
            $nbi = getFileContents($_FILES['nbi']['tmp_name']);
            $medical = getFileContents($_FILES['medical']['tmp_name']);
            $paypalEmail = $_POST['paypalEmail'];

            $workers = $workerObj -> getWorkersByConditions(null, null, null, null, null, null, null, null, $userId);

            if (isset($workers)) {
                $worker = $workers[0];

                $idWorker = $worker['idWorker'];
                $idWorkerDocuments = $worker['idWorkerDocuments'];

                $workerObj -> updateWorker($idWorker, null, null, null, null, null, null, $paypalEmail, null, null);
                $workerDocumentsObj -> updateWorkerDocument($idWorkerDocuments, null, null, $nbi, $medical, null);
            }

        }

        header("Location: ../worker/application.php");
        exit();
    }


?>