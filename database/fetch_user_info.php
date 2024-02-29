<?php
    include_once('./connect.php');
    session_start();

    $idUser = $_POST['idUser'];
    if ($_SESSION['userType'] == 'Worker') {
        $sql = "SELECT * FROM user INNER JOIN worker ON user.idUser = worker.idUser LEFT JOIN worker_documents ON worker.idWorkerDocuments = worker_documents.idWorkerDocuments WHERE user.idUser = ". $idUser;
    } else {
        $sql = "SELECT * FROM user INNER JOIN employer ON user.idUser = employer.idUser WHERE user.idUser = ". $idUser;
    }

    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        // Return data as JSON
        echo json_encode($data);
    } else {
        echo "0 results";
    }
?>