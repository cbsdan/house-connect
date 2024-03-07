<?php
include_once('./connect.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employerIdUser = $_POST['employerIdUser'];
    $workerIdUser = $_POST['workerIdUser'];
    $idEmployer = getEmployerOrWorkerID($employerIdUser);
    $idWorker = getEmployerOrWorkerID($workerIdUser);

    //Schedule Information
    $platform = $_POST['platform'];
    $link = $_POST['link'];
    $schedule = $_POST['schedule'];
    $message = $_POST['message'];

    $idContract = insertNewContract($idWorker, $idEmployer, 'Pending');
    $idMeeting = insertNewMeeting($idContract, $platform, $link, $schedule, $message);

    updateWorkerStatus($idWorker, 'Pending');
    
}

// Redirect to the appropriate page
header('Location: ../employer/manage_worker.php');
exit();

?>
