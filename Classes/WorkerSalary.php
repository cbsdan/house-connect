<?php

class WorkerSalary
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function insertWorkerSalary($paypalEmail, $amount, $idEmployerPayment)
    {
        $sql = "INSERT INTO worker_salary (paypalEmail, amount, idEmployerPayment) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sdi", $paypalEmail, $amount, $idEmployerPayment);
        if ($stmt->execute()) {
            // Return the ID of the newly inserted user
            return $stmt->insert_id;
        } else {
            return false;
        };
    }

    public function getWorkerSalaryById($idWorkerSalary)
    {
        $sql = "SELECT * FROM worker_salary WHERE idWorkerSalary = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idWorkerSalary);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getWorkerSalaries()
    {
        $sql = "SELECT * FROM worker_salary WHERE idWorkerSalary = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
            
        // Initialize an array to store all employers
        $workerSalaries = array();
        
        // Fetch each row and add it to the array
        while ($row = $result->fetch_assoc()) {
            $workerSalaries[] = $row;
        }
    
        return $workerSalaries;
    }

    public function updateWorkerSalary($idWorkerSalary, $paypalEmail = null, $amount = null)
    {
        $updates = [];
        $types = "";
        $params = [];
        if ($paypalEmail !== null) {
            $updates[] = "paypalEmail = ?";
            $types .= "s";
            $params[] = $paypalEmail;
        }
        if ($amount !== null) {
            $updates[] = "amount = ?";
            $types .= "d";
            $params[] = $amount;
        }
        $params[] = $idWorkerSalary;

        $sql = "UPDATE worker_salary SET " . implode(", ", $updates) . " WHERE idWorkerSalary = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types . "i", ...$params);
        return $stmt->execute();
    }

    public function deleteWorkerSalary($idWorkerSalary)
    {
        $sql = "DELETE FROM worker_salary WHERE idWorkerSalary = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idWorkerSalary);
        return $stmt->execute();
    }
}

?>
