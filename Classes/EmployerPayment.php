<?php
class EmployerPayment {
    private $conn;

    // Constructor to initialize the database connection
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Function to create a new employer payment and return the inserted ID
    public function createEmployerPayment($amount, $method, $imgReceipt, $paymentStatus, $idContract) {
        $sql = "INSERT INTO employer_payment (amount, method, imgReceipt, paymentStatus, idContract) 
                VALUES ($amount, '$method', '$imgReceipt', '$paymentStatus', $idContract)";
        
        if ($this->conn->query($sql)) {
            return $this->conn->insert_id;
        } else {
            return false;
        }
    }

    // Function to get an employer payment by its ID
    public function getEmployerPaymentById($idEmployerPayment) {
        $sql = "SELECT * FROM employer_payment WHERE idEmployerPayment = $idEmployerPayment";
        $result = $this->conn->query($sql);
        return ($result->num_rows > 0) ? $result->fetch_assoc() : false;
    }

    // Function to get employer payments based on conditions
    public function getEmployerPaymentByConditions($conditions = array()) {
        $sql = "SELECT * FROM employer_payment";
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

    // Function to update an employer payment
    public function updateEmployerPayment($idEmployerPayment, $amount = null, $method = null, $imgReceipt = null, $paymentStatus = null, $idContract = null) {
        $sql = "UPDATE employer_payment SET ";
        $updates = array();
        if ($amount !== null) {
            $updates[] = "amount = $amount";
        }
        if ($method !== null) {
            $updates[] = "method = '$method'";
        }
        if ($imgReceipt !== null) {
            $updates[] = "imgReceipt = '$imgReceipt'";
        }
        if ($paymentStatus !== null) {
            $updates[] = "paymentStatus = '$paymentStatus'";
        }
        if ($idContract !== null) {
            $updates[] = "idContract = $idContract";
        }
        $sql .= implode(", ", $updates);
        $sql .= " WHERE idEmployerPayment = $idEmployerPayment";
        return $this->conn->query($sql);
    }

    // Function to delete an employer payment
    public function deleteEmployerPayment($idEmployerPayment) {
        $sql = "DELETE FROM employer_payment WHERE idEmployerPayment = $idEmployerPayment";
        return $this->conn->query($sql);
    }

    // Function to get all employer payments
    public function getAllEmployerPayments() {
        $sql = "SELECT * FROM employer_payment";
        $result = $this->conn->query($sql);
        return ($result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : false;
    }
}

?>