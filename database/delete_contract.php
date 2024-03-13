<?php
include_once('./connect.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idContract = $_POST['idContract'];

    $contract = getContractList($idContract);
    $contract = $contract[0];
    $employerRequest = $employerRequestsObj -> getEmployerRequestByConditions(["contract_idContract" => $contract['idContract']]);

    if ($employerRequest != false) {
        $employerRequest = $employerRequest[0];
        $employerRequestsObj -> updateEmployerRequest($employerRequest['idEmployerPreference'], ["contract_idContract" => NULL, "status" => 'Pending']);
        $workerObj -> updateWorker($contract['idWorker'], null, "Available", null, null, null, null, null, null, null);
    }

    $contractObj -> deleteContract($idContract);

    updateWorkerStatus($contract['idWorker'], 'Available');
}

// Redirect to the appropriate page
header('Location: ../admin/contract_manager.php');
exit();
?>
