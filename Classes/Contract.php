<?php

class Contract {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function createContract($idWorker, $idEmployer, $contractStatus, $startDate = null, $endDate = null, $salaryAmt = null, $contractImg = null) {
        $sql = "INSERT INTO contract (idWorker, idEmployer, contractStatus, startDate, endDate, salaryAmt, contractImg) VALUES ($idWorker, $idEmployer, '$contractStatus', '$startDate', '$endDate', $salaryAmt, '$contractImg')";
        $result = $this->conn->query($sql);
        if ($result === true) {
            // Get the idContract of the inserted contract
            $idContract = $this->conn->insert_id;
            return $idContract; // Return the idContract if insertion is successful
        } else {
            return false; // Return false if insertion fails
        }
        
    }

    public function readContractByIdContract($idContract) {
        $sql = "SELECT * FROM contract WHERE idContract = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idContract);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    public function readContracts() {
        $sql = "SELECT * FROM contract";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
            
        // Initialize an array to store all employers
        $contracts = array();
        
        // Fetch each row and add it to the array
        while ($row = $result->fetch_assoc()) {
            $contracts[] = $row;
        }
    
        return $contracts;
    }

    public function updateContract($idContract, $contractStatus=null, $startDate = null, $endDate = null, $salaryAmt = null, $contractImg = null) {
        $sql = "UPDATE contract SET ";

        if ($startDate !== null) {
            $sql .= "startDate = '" . mysqli_real_escape_string($this->conn, $startDate) . "', ";
        }
        if ($endDate !== null) {
            $sql .= "endDate = '" . mysqli_real_escape_string($this->conn, $endDate) . "', ";
        }
        if ($salaryAmt !== null) {
            $sql .= "salaryAmt = " . (float)$salaryAmt . ", ";
        }
        if ($contractStatus !== null) {
            $sql .= "contractStatus = '" . $contractStatus . "', ";
        }
        if ($contractImg !== null) {
            $sql .= "contractImg = '" . $contractImg . "', ";
        }
        $sql = rtrim($sql, ", "); // Remove the trailing comma
        $sql .= " WHERE idContract = ?";
        
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $idContract);
            return $stmt->execute();
        }
        return false;        
    }

    public function deleteContract($idContract) {
        $sql = "DELETE FROM contract WHERE idContract = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idContract);
        return $stmt->execute();
    }
}
