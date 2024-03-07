<?php

class Rating {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new rating
    public function createRating($idContract, $rate = null, $comment = null) {
        $sql = "INSERT INTO rating (idContract, rate, comment) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ids", $idContract, $rate, $comment);
        if ($stmt->execute()) {
            // Return the ID of the newly inserted user
            return $stmt->insert_id;
        } else {
            return false;
        };
    }

    // Read a rating by idRating
    public function readRatingByIdRating($idRating) {
        $sql = "SELECT * FROM rating WHERE idRating = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idRating);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    // Read all ratings
    public function readRatings() {
        $sql = "SELECT * FROM rating";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
            
        // Initialize an array to store all employers
        $ratings = array();
        
        // Fetch each row and add it to the array
        while ($row = $result->fetch_assoc()) {
            $ratings[] = $row;
        }
    
        return $ratings;
    }
    // Update a rating
    public function updateRating($idRating, $rate = null, $comment = null) {
        $setClause = "";
        $params = array();
        if ($rate !== null) {
            $setClause .= "rate = ?, ";
            $params[] = $rate;
        }
        if ($comment !== null) {
            $setClause .= "comment = ?, ";
            $params[] = $comment;
        }
        // Remove trailing comma and space
        $setClause = rtrim($setClause, ", ");

        $sql = "UPDATE rating SET $setClause WHERE idRating = ?";
        $stmt = $this->conn->prepare($sql);
        // Bind parameters dynamically
        $paramTypes = str_repeat("s", count($params)) . "i"; // Assuming idRating is an integer
        $params[] = $idRating; // Append idRating to the end of the parameters array
        $stmt->bind_param($paramTypes, ...$params); // Unpack parameters array for binding
        return $stmt->execute();
    }


    // Delete a rating by idRating
    public function deleteRating($idRating) {
        $sql = "DELETE FROM rating WHERE idRating = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idRating);
        return $stmt->execute();
    }
}

?>
