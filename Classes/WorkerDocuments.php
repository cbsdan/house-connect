<?php
    class WorkerDocuments {
        private $conn;
    
        // Constructor to initialize database connection
        public function __construct($conn) {
            $this->conn = $conn;
        }
    
        // Insert worker documents
        public function insertWorkerDocuments($idWorkerDocuments, $curriculumVitae, $validId, $nbi, $medical, $certificate = null) {
            $sql = "INSERT INTO worker_documents (idWorkerDocuments, curriculumVitae, validId, nbi, medical, certificate) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("isssss", $idWorkerDocuments, $curriculumVitae, $validId, $nbi, $medical, $certificate);
            if ($stmt->execute()) {
                // Return the ID of the newly inserted user
                return $stmt->insert_id;
            } else {
                return false;
            };
        }
    
        // Retrieve worker documents by ID
        public function getWorkerDocumentsByID($idWorkerDocuments) {
            $sql = "SELECT * FROM worker_documents WHERE idWorkerDocuments = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $idWorkerDocuments);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }

        // Retrieve All worker documents
        public function getWorkerDocuments() {
            $sql = "SELECT * FROM worker_documents";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            
            // Initialize an array to store all employers
            $workerDocuments = array();
            
            // Fetch each row and add it to the array
            while ($row = $result->fetch_assoc()) {
                $workerDocuments[] = $row;
            }
        
            return $workerDocuments;
        }
    
        // Update worker documents
        public function updateWorkerDocuments($idWorkerDocuments, $curriculumVitae, $validId, $nbi, $medical, $certificate = null) {
            $sql = "UPDATE worker_documents SET curriculumVitae = ?, validId = ?, nbi = ?, medical = ?, certificate = ? WHERE idWorkerDocuments = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sssssi", $curriculumVitae, $validId, $nbi, $medical, $certificate, $idWorkerDocuments);
            return $stmt->execute();
        }
    
        // Delete worker documents
        public function deleteWorkerDocuments($idWorkerDocuments) {
            $sql = "DELETE FROM worker_documents WHERE idWorkerDocuments = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $idWorkerDocuments);
            return $stmt->execute();
        }
    }
    
?>