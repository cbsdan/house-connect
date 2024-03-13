<?php

class WorkerSalary {
    private $conn;

    // Constructor to initialize the database connection
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Function to create a new worker salary and return the inserted ID
    public function createWorkerSalary($paypalEmail, $taxAmount, $amount, $netPay, $status, $idEmployerPayment) {
        $sql = "INSERT INTO worker_salary (paypalEmail, tax_amount, amount, net_pay, status, idEmployerPayment) 
                VALUES ('$paypalEmail', $taxAmount, $amount, $netPay, '$status', $idEmployerPayment)";
        
        if ($this->conn->query($sql)) {
            return $this->conn->insert_id;
        } else {
            return false;
        }
    }

    // Function to get a worker salary by its ID
    public function getWorkerSalaryById($idWorkerSalary) {
        $sql = "SELECT * FROM worker_salary WHERE idWorkerSalary = $idWorkerSalary";
        $result = $this->conn->query($sql);
        return ($result->num_rows > 0) ? $result->fetch_assoc() : false;
    }

    // Function to get worker salaries based on conditions
    public function getWorkerSalaryByConditions($conditions = array()) {
        $sql = "SELECT * FROM worker_salary";
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

    // Function to update a worker salary
    public function updateWorkerSalary($idWorkerSalary, $paypalEmail = null, $taxAmount = null, $amount = null, $netPay = null, $status = null, $idEmployerPayment = null) {
        $sql = "UPDATE worker_salary SET ";
        $updates = array();
        if ($paypalEmail !== null) {
            $updates[] = "paypalEmail = '$paypalEmail'";
        }
        if ($taxAmount !== null) {
            $updates[] = "tax_amount = $taxAmount";
        }
        if ($amount !== null) {
            $updates[] = "amount = $amount";
        }
        if ($netPay !== null) {
            $updates[] = "net_pay = $netPay";
        }
        if ($status !== null) {
            $updates[] = "status = '$status'";
        }
        if ($idEmployerPayment !== null) {
            $updates[] = "idEmployerPayment = $idEmployerPayment";
        }
        $sql .= implode(", ", $updates);
        $sql .= " WHERE idWorkerSalary = $idWorkerSalary";
        return $this->conn->query($sql);
    }

    // Function to delete a worker salary
    public function deleteWorkerSalary($idWorkerSalary) {
        $sql = "DELETE FROM worker_salary WHERE idWorkerSalary = $idWorkerSalary";
        return $this->conn->query($sql);
    }

    // Function to get all worker salaries
    public function getAllWorkerSalaries() {
        $sql = "SELECT * FROM worker_salary";
        $result = $this->conn->query($sql);
        return ($result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : false;
    }
}


?>
