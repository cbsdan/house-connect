<?php
    include_once('./connect.php');
    session_start();

    $idUser = $_POST['idUser'];
    $userType = $_POST['userType'];

    if ($userType == 'Worker') {
        $sql = "SELECT *, user.idUser FROM user LEFT JOIN worker ON user.idUser = worker.idUser LEFT JOIN worker_documents ON worker.idWorkerDocuments = worker_documents.idWorkerDocuments WHERE user.idUser = ". $idUser;
    } else if ($userType == 'Employer') {
        $sql = "SELECT *, user.idUser FROM user LEFT JOIN employer ON user.idUser = employer.idUser WHERE user.idUser = ". $idUser;
    }

    if (isset($sql)) {
        $result = $conn->query($sql);
        if ($result->num_rows == 1) {
            $data = array();
            while ($row = $result->fetch_assoc()) {
                // Encode profile picture to Base64
                $profilePicData = base64_encode($row['profilePic']); // Assuming 'profilePic' is the column name for the profile picture
                // Add Base64-encoded profile picture to the row data
                $row['profilePic'] = $profilePicData;
                $data[] = $row;
            }
            
            // Return data as JSON
            echo json_encode($data);
        } else {
            echo $idUser;
        }
    } else {
        echo "No Data Found";
    }
?>