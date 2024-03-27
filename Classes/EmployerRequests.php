<?php
    class EmployerRequest {
        private $conn;
    
        // Constructor to initialize the database connection
        public function __construct($conn) {
            $this->conn = $conn;
        }
    
        public function createEmployerRequest($workerType, $status, $dateRequested, $employerIdEmployer, $contractIdContract = null, $age = null, $sex = null, $height = null, $yrsOfExperience = null, $additionalMessage = null) {
            $sql = "INSERT INTO employer_request (workerType, age, sex, height, yrsOfExperience, additionalMessage, status, date_requested, employer_idEmployer";
            if ($contractIdContract !== null) {
                $sql .= ", contract_idContract";
            }
            $sql .= ") VALUES ('$workerType', ";
            if ($age !== null) {
                $sql .= "'$age', ";
            } else {
                $sql .= "NULL, ";
            }
            if ($sex !== null) {
                $sql .= "'$sex', ";
            } else {
                $sql .= "NULL, ";
            }
            if ($height !== null) {
                $sql .= "$height, ";
            } else {
                $sql .= "NULL, ";
            }
            if ($yrsOfExperience !== null) {
                $sql .= "$yrsOfExperience, ";
            } else {
                $sql .= "NULL, ";
            }
            if ($additionalMessage !== null) {
                $sql .= "'$additionalMessage', ";
            } else {
                $sql .= "NULL, ";
            }
            $sql .= "'$status', '$dateRequested', $employerIdEmployer";
            if ($contractIdContract !== null) {
                $sql .= ", $contractIdContract";
            }
            $sql .= ")";
            
            if ($this->conn->query($sql)) {
                return $this->conn->insert_id;
            } else {
                return false;
            }
        }
        
        
             
    
        // Function to get an employer request by its ID
        public function getEmployerRequestById($idEmployerPreference) {
            $sql = "SELECT * FROM employer_request WHERE idEmployerPreference = $idEmployerPreference";
            $result = $this->conn->query($sql);
            return ($result->num_rows > 0) ? $result->fetch_assoc() : false;
        }
    
        // Function to get employer requests based on conditions
        public function getEmployerRequestByConditions($conditions = array()) {
            $sql = "SELECT * FROM employer_request";
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
    
        // Function to update an employer request
        public function updateEmployerRequest($idEmployerPreference, $updates = array()) {
            $sql = "UPDATE employer_request SET ";
            $updates_arr = array();
            foreach ($updates as $key => $value) {
                // Check if the value is null and handle accordingly
                if ($value === null) {
                    $updates_arr[] = "$key = NULL";
                } else {
                    // If not null, update with the provided value
                    $updates_arr[] = "$key = '$value'";
                }
            }
            $sql .= implode(", ", $updates_arr);
            $sql .= " WHERE idEmployerPreference = $idEmployerPreference";
            
            // Execute the query
            return $this->conn->query($sql);
        }


    
        // Function to delete an employer request
        public function deleteEmployerRequest($idEmployerPreference) {
            $sql = "DELETE FROM employer_request WHERE idEmployerPreference = $idEmployerPreference";
            return $this->conn->query($sql);
        }
    
        // Function to get all employer requests
        public function getAllEmployerRequests() {
            $sql = "SELECT * FROM employer_request";
            $result = $this->conn->query($sql);
            return ($result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : false;
        }
    }
    
?>