<?php
include_once('./connect.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['idEmployerPayment']) && $_POST['idEmployerPayment'] != '') {
        $idEmployerPayment = $_POST['idEmployerPayment'];
    } else {
        $idEmployerPayment = null;
    }
    if (isset($_POST['submitted_at']) && $_POST['submitted_at'] != '') {
        $submitted_at = $_POST['submitted_at'];
    } else {
        $submitted_at = null;
    }
    if (isset($_POST['employerPaymentAmount']) && $_POST['employerPaymentAmount'] != '') {
        $employerPaymentAmount = $_POST['employerPaymentAmount'];
    } else {
        $employerPaymentAmount = null;
        
    }
    if (isset($_POST['employerPaymentStatus']) && $_POST['employerPaymentStatus'] != '') {
        $employerPaymentStatus = $_POST['employerPaymentStatus'];
    } else {
        $employerPaymentStatus = null;

    }

    if(isset($_FILES['imgReceipt']) && is_uploaded_file($_FILES['imgReceipt']['tmp_name'])) {
        $imgReceipt = addslashes(file_get_contents($_FILES['imgReceipt']['tmp_name']));
    } else {
        $imgReceipt = null;
    }

    updateEmployerPayment($idEmployerPayment, $employerPaymentAmount, $employerPaymentStatus, $imgReceipt, $submitted_at);
    updateWorkerSalary($idEmployerPayment, $employerPaymentAmount * 0.9, $employerPaymentStatus);
}

// Redirect to the appropriate page
header('Location: ../admin/payment.php');
exit();
?>
