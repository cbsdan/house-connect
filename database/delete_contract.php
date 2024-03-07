<?php
include_once('./connect.php'); 
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idContract = $_POST['idContract'];

    $contract = getContractList($idContract);
    $contract = $contract[0];
    if (deleteContract($idContract) == true) {
        echo "<script>alert(Update Successfully!)</script>";
    }

    updateWorkerStatus($contract['idWorker'], 'Available');
}

// Redirect to the appropriate page
header('Location: ../admin/contract_manager.php');
exit();
?>
