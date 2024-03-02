<?php
include_once('./connect.php'); 
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $userId = intval($_POST['idUser']); // Assuming idUser is an integer

    // Disable foreign key checks
    $conn->query("SET FOREIGN_KEY_CHECKS = 0");

    // Prepare and execute the delete statement
    $stmt = $conn->prepare("DELETE FROM user WHERE idUser = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();

    // Re-enable foreign key checks
    $conn->query("SET FOREIGN_KEY_CHECKS = 1");
}

// Redirect to the appropriate page
header('Location: ../admin/user_accounts.php');
exit();
?>
