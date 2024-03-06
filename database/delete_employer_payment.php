<?php
include_once('./connect.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (deleteEmployerPayment($_POST['idEmployerPayment']) == true) {
        echo "<script>alert(Update Successfully!)</script>";
    }
}

// Redirect to the appropriate page
header('Location: ../admin/payment.php');
exit();
?>
