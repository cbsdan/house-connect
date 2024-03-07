<?php
    class EmployerPayment {
        private $conn;

        // Constructor to initialize the database connection
        public function __construct($db_conn) {
            $this->conn = $db_conn;
        }

        // Function to create a new employer payment record
        public function createEmployerPayment($idContract, $amount, $method, $imgReceipt, $paymentStatus = 'Pending') {
            $sql = "INSERT INTO employer_payment (idContract, amount, method, imgReceipt, paymentStatus, submitted_at) VALUES (?, ?, ?, ?, ?, NOW())";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("idsbs", $idContract, $amount, $method, $imgReceipt, $paymentStatus);
            if ($stmt->execute()) {
                // Return the ID of the newly inserted user
                return $stmt->insert_id;
            } else {
                return false;
            };
        }

        // Function to read an employer payment record
        public function readEmployerPaymentByIdEmployer($idEmployerPayment) {
            $sql = "SELECT * FROM employer_payment WHERE idEmployerPayment = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $idEmployerPayment);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
        // Function to read all employer payment 
        public function readEmployerPayments($idEmployerPayment) {
            $sql = "SELECT * FROM employer_payment";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            
            // Initialize an array to store all employers
            $employerPayments = array();
            
            // Fetch each row and add it to the array
            while ($row = $result->fetch_assoc()) {
                $employerPayments[] = $row;
            }
        
            return $employerPayments;
        }

        // Function to update an employer payment record
        public function updateEmployerPayment($idEmployerPayment, $amount = null, $method = null, $imgReceipt = null, $paymentStatus = null) {
            $sql = "UPDATE employer_payment SET ";
            $updates = array();
            if ($amount !== null) {
                $updates[] = "amount = ?";
            }
            if ($method !== null) {
                $updates[] = "method = ?";
            }
            if ($imgReceipt !== null) {
                $updates[] = "imgReceipt = ?";
            }
            if ($paymentStatus !== null) {
                $updates[] = "paymentStatus = ?";
            }
            $sql .= implode(", ", $updates);
            $sql .= " WHERE idEmployerPayment = ?";
            $stmt = $this->conn->prepare($sql);
            if ($stmt) {
                $paramTypes = "";
                $paramValues = array();
                if ($amount !== null) {
                    $paramTypes .= "d";
                    $paramValues[] = $amount;
                }
                if ($method !== null) {
                    $paramTypes .= "s";
                    $paramValues[] = $method;
                }
                if ($imgReceipt !== null) {
                    $paramTypes .= "s";
                    $paramValues[] = $imgReceipt;
                }
                if ($paymentStatus !== null) {
                    $paramTypes .= "s";
                    $paramValues[] = $paymentStatus;
                }
                $paramTypes .= "i";
                $paramValues[] = $idEmployerPayment;
                $stmt->bind_param($paramTypes, ...$paramValues);
            } else {
                return false;
            }
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }

        // Function to delete an employer payment record
        public function deleteEmployerPayment($idEmployerPayment) {
            $sql = "DELETE FROM employer_payment WHERE idEmployerPayment = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $idEmployerPayment);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }
    }
?>