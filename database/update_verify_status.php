<?php 
    include_once('./connect.php'); 
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $userId = $_GET['idUser'];
        $userType = $_GET['userType'];

        if (isset($_GET['approve']) && $_GET['approve'] == 'approve') {
            if ($userType == 'Worker') {
                $sql = "UPDATE worker SET verifyStatus = 'Verified' WHERE idUser = $userId";
            } elseif ($userType == 'Employer') {
                $sql = "UPDATE employer SET verifyStatus = 'Verified' WHERE idUser = $userId";
            }
        } elseif (isset($_GET['decline']) && $_GET['decline'] == 'decline') {
            if ($userType == 'Worker') {
                $sql = "DELETE FROM worker WHERE idUser = $userId";
            } elseif ($userType == 'Employer') {
                $sql = "UPDATE employer SET validId = NULL WHERE idUser = $userId";
            }
        }

        if (isset($sql)) {
            $conn->query($sql);
        }
    }
    header('Location: ../admin/user_accounts.php');
    exit();
?>