<?php
class Contract {
    private $conn;

    // Constructor to initialize the database connection
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Function to create a new contract and return the inserted ID
    public function createContract($contractStatus, $deploymentLocation = null, $startDate = null, $endDate = null, $salaryAmt = null, $contractImg = null, $idWorker, $idEmployer) {
        $sql = "INSERT INTO contract (contractStatus, deploymentLocation, startDate, endDate, salaryAmt, contractImg, idWorker, idEmployer) 
                VALUES ('$contractStatus', ";
        if ($deploymentLocation !== null) {
            $sql .= "'$deploymentLocation', ";
        } else {
            $sql .= "NULL, ";
        }
        if ($startDate !== null) {
            $sql .= "'$startDate', ";
        } else {
            $sql .= "NULL, ";
        }
        if ($endDate !== null) {
            $sql .= "'$endDate', ";
        } else {
            $sql .= "NULL, ";
        }
        if ($salaryAmt !== null) {
            $sql .= "'$salaryAmt', ";
        } else {
            $sql .= "NULL, ";
        }
        if ($contractImg !== null) {
            $sql .= "'$contractImg', ";
        } else {
            $sql .= "NULL, ";
        }
        $sql .= "$idWorker, $idEmployer)";

        if ($this->conn->query($sql)) {
            return $this->conn->insert_id;
        } else {
            return false;
        }
    }

    // Function to get a contract by its ID
    public function getContractById($idContract) {
        $sql = "SELECT * FROM contract WHERE idContract = $idContract";
        $result = $this->conn->query($sql);
        return ($result->num_rows > 0) ? $result->fetch_assoc() : false;
    }

    // Function to get contracts based on conditions
    public function getContractByConditions($conditions = array()) {
        $sql = "SELECT * FROM contract";
        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $conditions_arr = array();
            foreach ($conditions as $key => $value) {
                $conditions_arr[] = "$key = '$value'";
            }
            $sql .= implode(" AND ", $conditions_arr);
        }
        $result = $this->conn->query($sql);
        return ($result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : false;
    }

    // Function to update a contract
    public function updateContract($idContract, $contractStatus = null, $deploymentLocation =null, $startDate = null, $endDate = null, $salaryAmt = null, $contractImg = null) {
        $sql = "UPDATE contract SET ";
        $updates = array();
        if ($contractStatus !== null) {
            $updates[] = "contractStatus = '$contractStatus'";
        }
        if ($deploymentLocation !== null) {
            $updates[] = "deploymentLocation = '$deploymentLocation'";
        }
        if ($startDate !== null) {
            $updates[] = "startDate = '$startDate'";
        }
        if ($endDate !== null) {
            $updates[] = "endDate = '$endDate'";
        }
        if ($salaryAmt !== null) {
            $updates[] = "salaryAmt = '$salaryAmt'";
        }
        if ($contractImg !== null) {
            $updates[] = "contractImg = '$contractImg'";
        }
        $sql .= implode(", ", $updates);
        $sql .= " WHERE idContract = $idContract";
        return $this->conn->query($sql);
    }

    // Function to delete a contract
    public function deleteContract($idContract) {
        $sql = "DELETE FROM contract WHERE idContract = $idContract";
        return $this->conn->query($sql);
    }

    // Function to get all contracts
    public function getAllContracts() {
        $sql = "SELECT * FROM contract";
        $result = $this->conn->query($sql);
        return ($result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : false;
    }
}


?>