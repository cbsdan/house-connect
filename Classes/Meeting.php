<?php

class Meeting {
    private $conn;

    // Constructor to initialize database connection
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Create a new meeting
    public function createMeeting($contractId, $meetDate, $platform, $link, $employerMessage) {
        $sql = "INSERT INTO meeting (contract_idContract, meetDate, platform, link, employerMessage) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("issss", $contractId, $meetDate, $platform, $link, $employerMessage);
        if ($stmt->execute()) {
            // Return the ID of the newly inserted user
            return $stmt->insert_id;
        } else {
            return false;
        };
    }

    // Read meeting details by idMeeting
    public function getMeetingById($idMeeting) {
        $sql = "SELECT * FROM meeting WHERE idMeeting = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idMeeting);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    // Read all meeting details 
    public function getMeetings() {
        $sql = "SELECT * FROM meeting";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
            
        // Initialize an array to store all employers
        $meetings = array();
        
        // Fetch each row and add it to the array
        while ($row = $result->fetch_assoc()) {
            $meetings[] = $row;
        }
    
        return $meetings;
    }

    // Update meeting details
    public function updateMeeting($idMeeting, $meetDate, $platform, $link, $employerMessage) {
        $sql = "UPDATE meeting SET meetDate = ?, platform = ?, link = ?, employerMessage = ? WHERE idMeeting = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssi", $meetDate, $platform, $link, $employerMessage, $idMeeting);
        return $stmt->execute();
    }

    // Delete a meeting
    public function deleteMeeting($idMeeting) {
        $sql = "DELETE FROM meeting WHERE idMeeting = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idMeeting);
        return $stmt->execute();
    }
}

?>
