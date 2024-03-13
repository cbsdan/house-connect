<?php
include_once('./connect.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $idContract = $_POST['idContract'];

    if ($_POST['contractStatus'] != '' && $_POST['contractStatus'] != null) {
        $contractStatus = $_POST['contractStatus'];
    } else {
        $contractStatus = null;
    }

    if ($_POST['startDate'] != '' && $_POST['startDate'] != null) {
        $startDate = $_POST['startDate'];
    } else {
        $startDate = null;
    }

    if ($_POST['endDate'] != '' && $_POST['endDate'] != null) {
        $endDate = $_POST['endDate'];
    } else {
        $endDate = null;
    }

    if ($_POST['salaryAmt'] != '' && $_POST['salaryAmt'] != null) {
        $salaryAmt = $_POST['salaryAmt'];
    } else {
        $salaryAmt = null;
    }
    if ($_POST['deploymentLocation'] != '' && $_POST['deploymentLocation'] != null) {
        $deploymentLocation = $_POST['deploymentLocation'];
    } else {
        $deploymentLocation = null;
    }
    
    if ($_POST['idWorker'] != '' && $_POST['idWorker'] != null) {
        $idWorker = $_POST['idWorker'];
    } else {
        $idWorker = null;
    }

    if (isset($_FILES['contractImg']) && is_uploaded_file($_FILES['contractImg']['tmp_name'])) {
        $contractImg = addslashes(file_get_contents($_FILES['contractImg']['tmp_name']));
    } else {
        $contractImg = null;
    }
    
    updateContract($idContract, $contractStatus, $deploymentLocation, $startDate, $endDate, $salaryAmt, $contractImg);
    if ($contractStatus == 'Completed' || $contractStatus == 'Canceled') {
        $workerStatus = 'Available';
    } else {
        $workerStatus = $contractStatus;
    }
    updateWorkerStatus($idWorker, $workerStatus);
}

// Redirect to the appropriate page
header('Location: ../admin/contract_manager.php');
exit();
?>
