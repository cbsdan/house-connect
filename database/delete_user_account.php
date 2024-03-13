<?php
include_once('./connect.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $userId = intval($_POST['idUser']); // Assuming idUser is an integer

    // Prepare and execute the delete statement
    $stmt = $conn->prepare("DELETE FROM user WHERE idUser = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();
}

// Redirect to the appropriate page
header('Location: ../admin/user_accounts.php');
exit();
?>
