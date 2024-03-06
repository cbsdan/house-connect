<?php
include_once('./connect.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['idWorkerSalary']) && $_POST['idWorkerSalary'] != '') {
        $idWorkerSalary = $_POST['idWorkerSalary'];
    } else {
        $idWorkerSalary = null;
    }
    if (isset($_POST['paypalEmail']) && $_POST['paypalEmail'] != '') {
        $paypalEmail = $_POST['paypalEmail'];
    } else {
        $paypalEmail = null;
    }
    if (isset($_POST['workerSalaryAmount']) && $_POST['workerSalaryAmount'] != '') {
        $workerSalaryAmount = $_POST['workerSalaryAmount'];
    } else {
        $workerSalaryAmount = null;
        
    }
    if (isset($_POST['workerSalaryStatus']) && $_POST['workerSalaryStatus'] != '') {
        $workerSalaryStatus = $_POST['workerSalaryStatus'];
    } else {
        $workerSalaryStatus = null;

    }

    updateWorkerSalaryPayment($idWorkerSalary, $paypalEmail, $workerSalaryAmount, $workerSalaryStatus);
}

// Redirect to the appropriate page
header('Location: ../admin/payment.php');
exit();
?>
