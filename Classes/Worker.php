<?php

class WorkerCRUD {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Create a new worker record
    public function createWorker($idUser, $workerType, $yearsOfExperience = null, $height = null, $profilePic = null, $verifyStatus = null, $paypalEmail = null, $idWorkerDocuments = null) {
        $sql = "INSERT INTO worker (idUser, workerType, yearsOfExperience, height, profilePic, verifyStatus, paypalEmail, idWorkerDocuments) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isississ", $idUser, $workerType, $yearsOfExperience, $height, $profilePic, $verifyStatus, $paypalEmail, $idWorkerDocuments);
        if ($stmt->execute()) {
            // Return the ID of the newly inserted user
            return $stmt->insert_id;
        } else {
            return false;
        };
    }

    // Read worker information by idUser
    public function getWorkerByIdUser($idUser) {
        $sql = "SELECT * FROM worker WHERE idUser = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idUser);
        $stmt->execute();
        $result = $stmt->get_result();
            
        // Initialize an array to store all employers
        $workers = array();
        
        // Fetch each row and add it to the array
        while ($row = $result->fetch_assoc()) {
            $workers[] = $row;
        }
    
        return $workers;
    }

    // Update worker information
    public function updateWorker($idUser, $workerType, $yearsOfExperience = null, $height = null, $profilePic = null, $verifyStatus = null, $paypalEmail = null, $idWorkerDocuments = null) {
        $sql = "UPDATE worker SET workerType = ?, yearsOfExperience = ?, height = ?, profilePic = ?, verifyStatus = ?, paypalEmail = ?, idWorkerDocuments = ? WHERE idUser = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sississi", $workerType, $yearsOfExperience, $height, $profilePic, $verifyStatus, $paypalEmail, $idWorkerDocuments, $idUser);
        return $stmt->execute();
    }

    // Delete worker record by idUser
    public function deleteWorker($idUser) {
        $sql = "DELETE FROM worker WHERE idUser = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idUser);
        return $stmt->execute();
    }
}

?>
