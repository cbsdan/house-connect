<?php
    class Employer {
        private $conn;
    
        public function __construct($conn) {
            $this->conn = $conn;
        }
    
        public function createEmployer($idUser, $verifyStatus, $profilePic = null, $validId = null) {
            $sql = "INSERT INTO employer (idUser, verifyStatus, profilePic, validId) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("issi", $idUser, $verifyStatus, $profilePic, $validId);
            if ($stmt->execute()) {
                // Return the ID of the newly inserted user
                return $stmt->insert_id;
            } else {
                return false;
            };
        }
    
        public function getEmployerByIdEmployer ($idEmployer) {
            $sql = "SELECT * FROM employer WHERE idEmployer = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $idEmployer);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }
        public function getEmployers() {
            $sql = "SELECT * FROM employer";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            
            // Initialize an array to store all employers
            $employers = array();
            
            // Fetch each row and add it to the array
            while ($row = $result->fetch_assoc()) {
                $employers[] = $row;
            }
        
            return $employers;
        }        
    
        public function updateEmployer($idEmployer, $verifyStatus, $profilePic = null, $validId = null) {
            $sql = "UPDATE employer SET verifyStatus = ?, profilePic = ?, validId = ? 
                    WHERE idEmployer = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("issi", $verifyStatus, $profilePic, $validId, $idEmployer);
            return $stmt->execute();
        }
    
        public function deleteEmployer($idEmployer) {
            $sql = "DELETE FROM employer WHERE idEmployer = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $idEmployer);
            return $stmt->execute();
        }
    }
?>