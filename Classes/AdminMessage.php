<?php
    class AdminMessage {
        private $conn;
    
        // Constructor to initialize the database connection
        public function __construct($db) {
            $this->conn = $db;
        }
    
        // Create a new admin message
        public function createAdminMessage($idUser, $subject, $message, $isRead) {
            $sql = "INSERT INTO admin_message (idUser, subject, message, isRead) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("isss", $idUser, $subject, $message, $isRead);
            if ($stmt->execute()) {
                // Return the ID of the newly inserted user
                return $stmt->insert_id;
            } else {
                return false;
            };
        }
    
        // Read all admin messages
        public function readAllAdminMessages() {
            $sql = "SELECT * FROM admin_message";
            $result = $this->conn->query($sql);
            return $result;
        }
    
        // Read a specific admin message by ID
        public function readAdminMessageById($idMessage) {
            $sql = "SELECT * FROM admin_message WHERE idMessage = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $idMessage);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
    
        // Update an admin message
        public function updateAdminMessage($idMessage, $subject, $message, $isRead) {
            $sql = "UPDATE admin_message SET subject = ?, message = ?, isRead = ? WHERE idMessage = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sssi", $subject, $message, $isRead, $idMessage);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }
    
        // Delete an admin message
        public function deleteAdminMessage($idMessage) {
            $sql = "DELETE FROM admin_message WHERE idMessage = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $idMessage);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }
    }
    
?>