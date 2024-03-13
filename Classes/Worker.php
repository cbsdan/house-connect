<?php

class Worker {
    private $conn;

    // Constructor with database connection
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Create a new worker
    public function createWorker($workerType, $workerStatus, $verifyStatus, $qualification_status, $yearsOfExperience, $height, $paypalEmail = null, $idWorkerDocuments = null, $idUser) {
        $sql = "INSERT INTO worker (workerType, workerStatus, verifyStatus, qualification_status, yearsOfExperience, height, paypalEmail, idWorkerDocuments, idUser) 
                VALUES ('$workerType', '$workerStatus', '$verifyStatus', '$qualification_status', $yearsOfExperience, $height, ";
        if ($paypalEmail !== null) {
            $sql .= "'$paypalEmail', ";
        } else {
            $sql .= "NULL, ";
        }
        if ($idWorkerDocuments !== null) {
            $sql .= "$idWorkerDocuments, ";
        } else {
            $sql .= "NULL, ";
        }
        $sql .= "$idUser)";
        
        if ($this->conn->query($sql)) {
            return $this->conn->insert_id;
        } else {
            return false;
        }
    }

    // Read a worker with optional parameters
    public function getWorkerById($idWorker) {
        $sql = "SELECT * FROM worker WHERE idWorker = $idWorker";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }

    // Get workers based on conditions
    public function getWorkersByConditions($workerType = null, $workerStatus = null, $verifyStatus = null, $qualification_status =null, $yearsOfExperience = null, $height = null, $paypalEmail = null, $idWorkerDocuments = null, $idUser = null) {
        $sql = "SELECT * FROM worker WHERE 1=1";

        if ($workerType !== null) {
            $sql .= " AND workerType = '$workerType'";
        }
        if ($workerStatus !== null) {
            $sql .= " AND workerStatus = '$workerStatus'";
        }
        if ($verifyStatus !== null) {
            $sql .= " AND verifyStatus = '$verifyStatus'";
        }
        if ($qualification_status !== null) {
            $sql .= " AND qualification_status = '$qualification_status'";
        }
        if ($yearsOfExperience !== null) {
            $sql .= " AND yearsOfExperience = $yearsOfExperience";
        }
        if ($height !== null) {
            $sql .= " AND height = $height";
        }
        if ($paypalEmail !== null) {
            $sql .= " AND paypalEmail = '$paypalEmail'";
        }
        if ($idWorkerDocuments !== null) {
            $sql .= " AND idWorkerDocuments = $idWorkerDocuments";
        }
        if ($idUser !== null) {
            $sql .= " AND idUser = $idUser";
        }

        $result = $this->conn->query($sql);
        $workers = [];
        while ($row = $result->fetch_assoc()) {
            $workers[] = $row;
        }
        return $workers;
    }

    // Update a worker
    public function updateWorker($idWorker, $workerType = null, $workerStatus = null, $verifyStatus = null, $qualification_status = null, $yearsOfExperience = null, $height = null, $paypalEmail = null, $idWorkerDocuments = null, $idUser = null) {
        $sql = "UPDATE worker SET ";
        if ($workerType !== null) {
            $sql .= "workerType = '$workerType', ";
        }
        if ($workerStatus !== null) {
            $sql .= "workerStatus = '$workerStatus', ";
        }
        if ($verifyStatus !== null) {
            $sql .= "verifyStatus = '$verifyStatus', ";
        }
        if ($qualification_status !== null) {
            $sql .= "qualification_status = '$qualification_status', ";
        }
        if ($yearsOfExperience !== null) {
            $sql .= "yearsOfExperience = $yearsOfExperience, ";
        }
        if ($height !== null) {
            $sql .= "height = $height, ";
        }
        if ($paypalEmail !== null) {
            $sql .= "paypalEmail = '$paypalEmail', ";
        }
        if ($idWorkerDocuments !== null) {
            $sql .= "idWorkerDocuments = $idWorkerDocuments, ";
        }
        if ($idUser !== null) {
            $sql .= "idUser = $idUser, ";
        }
        // Remove the trailing comma and space
        $sql = rtrim($sql, ", ");

        $sql .= " WHERE idWorker = $idWorker";

        $result = $this->conn->query($sql);

        return $result;
    }

    // Delete a worker by id
    public function deleteWorker($idWorker) {
        $sql = "DELETE FROM worker WHERE idWorker = $idWorker";
        $result = $this->conn->query($sql);
        return $result;
    }

    // Get all workers
    public function getAllWorkers() {
        $sql = "SELECT * FROM worker";
        $result = $this->conn->query($sql);
        $workers = array();
        while ($row = $result->fetch_assoc()) {
            $workers[] = $row;
        }
        return $workers;
    }
}
?>
