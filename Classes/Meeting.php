<?php
class Meeting {
    private $conn;

    // Constructor to initialize the database connection
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Function to create a new meeting and return the inserted ID
    public function createMeeting($meetDate, $locationAddress, $message = null, $idContract) {
        $sql = "INSERT INTO meeting (meetDate, locationAddress, contract_idContract, message) 
                VALUES ('$meetDate', '$locationAddress', ";
        if ($idContract !== null) {
            $sql .= "$idContract, ";
        } else {
            return false; // idContract is required, so return false if it's null
        }
        if ($message !== null) {
            $sql .= "\"$message\")";
        } else {
            $sql .= "NULL)";
        }
    
        if ($this->conn->query($sql)) {
            return $this->conn->insert_id;
        } else {
            return false;
        }
    }
    

    // Function to get a meeting by its ID
    public function getMeetingById($idMeeting) {
        $sql = "SELECT * FROM meeting WHERE idMeeting = $idMeeting";
        $result = $this->conn->query($sql);
        return ($result->num_rows > 0) ? $result->fetch_assoc() : false;
    }

    // Function to get meetings based on conditions
    public function getMeetingByConditions($conditions = array()) {
        $sql = "SELECT * FROM meeting";
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

    // Function to update a meeting
    public function updateMeeting($idMeeting, $meetDate = null, $locationAddress = null, $message = null, $idContract = null) {
        $sql = "UPDATE meeting SET ";
        $updates = array();
        if ($meetDate !== null) {
            $updates[] = "meetDate = '$meetDate'";
        }
        if ($locationAddress !== null) {
            $updates[] = "locationAddress = '$locationAddress'";
        }
        if ($message !== null) {
            $updates[] = "message = \"$message\"";
        }
        if ($idContract !== null) {
            $updates[] = "idContract = $idContract";
        }
        $sql .= implode(", ", $updates);
        $sql .= " WHERE idMeeting = $idMeeting";

        return $this->conn->query($sql);
    }

    // Function to delete a meeting
    public function deleteMeeting($idMeeting) {
        $sql = "DELETE FROM meeting WHERE idMeeting = $idMeeting";
        return $this->conn->query($sql);
    }

    // Function to get all meetings
    public function getAllMeetings() {
        $sql = "SELECT * FROM meeting";
        $result = $this->conn->query($sql);
        return ($result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : false;
    }
}

?>
