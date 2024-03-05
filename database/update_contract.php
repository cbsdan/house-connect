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

    if (isset($_FILES['contractImg']) && is_uploaded_file($_FILES['contractImg']['tmp_name'])) {
        $contractImg = addslashes(file_get_contents($_FILES['contractImg']['tmp_name']));
    } else {
        $contractImg = null;
    }
    
    updateContract($idContract, $contractStatus, $startDate, $endDate, $salaryAmt, $contractImg);
}

// Redirect to the appropriate page
header('Location: ../admin/contract_manager.php');
exit();
?>