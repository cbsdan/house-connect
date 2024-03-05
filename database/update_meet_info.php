<?php
include_once('./connect.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idMeeting = $_POST['idMeeting'];
    $meetDate = $_POST['meetDate'];
    $platform = $_POST['platform'];
    $link = $_POST['link'];
    $employerMessage = $_POST['employerMessage'];

    $result = updateMeeting($idMeeting, $meetDate, $platform, $link, $employerMessage);
    if ($result == true) {
        echo "<script>alert(Update Successfully!)</script>";
    }
}

// Redirect to the appropriate page
header('Location: ../admin/contract_manager.php');
exit();
?>
