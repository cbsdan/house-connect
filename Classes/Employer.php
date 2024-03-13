<?php
class Employer {
    private $conn;

    // Constructor to initialize the database connection
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Function to create a new employer and return the inserted ID
    public function createEmployer($profilePic = null, $validId = null, $verifyStatus = 'Not Verified', $idUser) {
        $sql = "INSERT INTO employer (profilePic, validId, verifyStatus, idUser) 
                VALUES (";
        if ($profilePic !== null) {
            $sql .= "'$profilePic', ";
        } else {
            $sql .= "NULL, ";
        }
        if ($validId !== null) {
            $sql .= "'$validId', ";
        } else {
            $sql .= "NULL, ";
        }
        $sql .= "'$verifyStatus', $idUser)";

        if ($this->conn->query($sql)) {
            return $this->conn->insert_id;
        } else {
            return false;
        }
    }

    // Function to get an employer by their ID
    public function getEmployerById($idEmployer) {
        $sql = "SELECT * FROM employer WHERE idEmployer = $idEmployer";
        $result = $this->conn->query($sql);
        return ($result->num_rows > 0) ? $result->fetch_assoc() : false;
    }

    // Function to get employers based on conditions
    public function getEmployerByConditions($conditions = array()) {
        $sql = "SELECT * FROM employer";
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

    // Function to update an employer
    public function updateEmployer($idEmployer, $profilePic = null, $validId = null, $verifyStatus = null) {
        $sql = "UPDATE employer SET ";
        $updates = array();
        if ($profilePic !== null) {
            $updates[] = "profilePic = '$profilePic'";
        }
        if ($validId !== null) {
            $updates[] = "validId = '$validId'";
        }
        if ($verifyStatus !== null) {
            $updates[] = "verifyStatus = '$verifyStatus'";
        }
        $sql .= implode(", ", $updates);
        $sql .= " WHERE idEmployer = $idEmployer";
        return $this->conn->query($sql);
    }

    // Function to delete an employer
    public function deleteEmployer($idEmployer) {
        $sql = "DELETE FROM employer WHERE idEmployer = $idEmployer";
        return $this->conn->query($sql);
    }

    // Function to get all employers
    public function getAllEmployers() {
        $sql = "SELECT * FROM employer";
        $result = $this->conn->query($sql);
        return ($result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : false;
    }
}

?>