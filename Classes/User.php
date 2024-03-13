<?php

class User {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Function to create a new user
    public function createUser($fname, $lname, $sex, $birthdate, $email, $password, $userType, $address = null, $contactNo = null) {
        $sql = "INSERT INTO user (fname, lname, sex, birthdate, address, contactNo, email, password, userType) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssssssss", $fname, $lname, $sex, $birthdate, $address, $contactNo, $email, $password, $userType);
        if ($stmt->execute()) {
            // Return the ID of the newly inserted user
            return $stmt->insert_id;
        } else {
            return false;
        }
    }    

    // Function to retrieve user by idUser
    public function getUserById($idUser) {
        $sql = "SELECT * FROM user WHERE idUser = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idUser);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getUserByConditions($conditions = array()) {
        $sql = "SELECT * FROM user";
        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $conditions_arr = array();
            foreach ($conditions as $key => $value) {
                $conditions_arr[] = "$key = '$value'";
            }
            $sql .= implode(" AND ", $conditions_arr);
        }
        $result = $this->conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $users = array();
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
            return $users;
        } else {
            return false;
        }
    }

    
    // Function to retrieve user by idUser
    public function getUsers() {
        $sql = "SELECT * FROM user";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
            
        // Initialize an array to store all employers
        $users = array();
        
        // Fetch each row and add it to the array
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    
        return $users;
    }

    // Function to update user details
    public function updateUser($idUser, $fname, $lname, $sex, $birthdate, $email, $userType, $address = null, $contactNo = null) {
        $sql = "UPDATE user SET fname = ?, lname = ?, sex = ?, birthdate = ?, address = ?, contactNo = ?, email = ?, userType = ? WHERE idUser = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssssssi", $fname, $lname, $sex, $birthdate, $address, $contactNo, $email, $userType, $idUser);
        return $stmt->execute();
    }

    // Function to delete user by idUser
    public function deleteUser($idUser) {
        $sql = "DELETE FROM user WHERE idUser = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idUser);
        return $stmt->execute();
    }
}
?>
