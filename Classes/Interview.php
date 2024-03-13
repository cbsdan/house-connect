<?php

class Interview {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Function to create a new interview and return the inserted ID
    public function createInterview($interviewDate, $interviewLocation, $message = null, $worker_idWorker) {
        $sql = "INSERT INTO interview (interviewDate, interviewLocation, message, worker_idWorker) 
                VALUES ('$interviewDate', \"$interviewLocation\", ";
        if ($message !== null) {
            $sql .= "\"$message\", ";
        } else {
            $sql .= "NULL, ";
        }
        $sql .= "$worker_idWorker)";

        if ($this->conn->query($sql)) {
            return $this->conn->insert_id;
        } else {
            return false;
        }
    }

    // Function to get an interview by its ID
    public function getInterviewById($idInterview) {
        $sql = "SELECT * FROM interview WHERE idInterview = $idInterview";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    // Function to get interviews by conditions
    public function getInterviewByConditions($conditions = array()) {
        $sql = "SELECT * FROM interview";
        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $conditions_arr = array();
            foreach ($conditions as $key => $value) {
                $conditions_arr[] = "$key = '$value'";
            }
            $sql .= implode(" AND ", $conditions_arr);
        }
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            $interviews = array();
            while ($row = $result->fetch_assoc()) {
                $interviews[] = $row;
            }
            return $interviews;
        } else {
            return false;
        }
    }

    // Function to update an interview
    public function updateInterview($idInterview, $updates = array()) {
        $sql = "UPDATE interview SET ";
        $updates_arr = array();
        foreach ($updates as $key => $value) {
            $updates_arr[] = "$key = \"$value\"";
        }
        $sql .= implode(", ", $updates_arr);
        $sql .= " WHERE idInterview = $idInterview";

        return $this->conn->query($sql);
    }

    // Function to delete an interview
    public function deleteInterview($idInterview) {
        $sql = "DELETE FROM interview WHERE idInterview = $idInterview";
        return $this->conn->query($sql);
    }

    // Function to get all interviews
    public function getAllInterviews() {
        $sql = "SELECT * FROM interview";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            $interviews = array();
            while ($row = $result->fetch_assoc()) {
                $interviews[] = $row;
            }
            return $interviews;
        } else {
            return false;
        }
    }
}
?>
