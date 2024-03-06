<?php
include_once('./connect.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $idContract = $_POST['idContract'];
    $idWorker = $_POST['idWorker'];

    if ($_POST['contractStatus'] != '' && $_POST['contractStatus'] != null) {
        $contractStatus = $_POST['contractStatus'];
    } else {
        $contractStatus = null;
    }

    updateContract($idContract, $contractStatus);
    if ($contractStatus == 'Completed' || $contractStatus == 'Canceled') {
        $workerStatus = 'Available';
    } else {
        $workerStatus = $contractStatus;
    }
    updateWorkerStatus($idWorker, $workerStatus);
}

// Redirect to the appropriate page
header('Location: ../employer/contract_info.php');
exit();
?>
