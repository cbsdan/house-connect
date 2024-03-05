<?php
include_once('./connect.php'); 
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idContract = $_POST['idContract'];

    if (deleteContract($idContract) == true) {
        echo "<script>alert(Update Successfully!)</script>";
    }
}

// Redirect to the appropriate page
header('Location: ../admin/contract_manager.php');
exit();
?>
