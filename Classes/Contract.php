<?php

class Contract {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function createContract($idWorker, $idEmployer, $contractStatus, $startDate = null, $endDate = null, $salaryAmt = null, $contractImg = null) {
        $sql = "INSERT INTO contract (idWorker, idEmployer, contractStatus, startDate, endDate, salaryAmt, contractImg) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iissdsb", $idWorker, $idEmployer, $contractStatus, $startDate, $endDate, $salaryAmt, $contractImg);
        if ($stmt->execute()) {
            // Return the ID of the newly inserted user
            return $stmt->insert_id;
        } else {
            return false;
        };
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

    public function updateContract($idContract, $startDate = null, $endDate = null, $salaryAmt = null, $contractImg = null) {
        $sql = "UPDATE contract SET ";
        $params = array();
        if ($startDate !== null) $params[] = "startDate = ?";
        if ($endDate !== null) $params[] = "endDate = ?";
        if ($salaryAmt !== null) $params[] = "salaryAmt = ?";
        if ($contractImg !== null) $params[] = "contractImg = ?";
        $sql .= implode(", ", $params) . " WHERE idContract = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            if ($startDate !== null) $stmt->bind_param("s", $startDate);
            if ($endDate !== null) $stmt->bind_param("s", $endDate);
            if ($salaryAmt !== null) $stmt->bind_param("d", $salaryAmt);
            if ($contractImg !== null) $stmt->bind_param("s", $contractImg);
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
