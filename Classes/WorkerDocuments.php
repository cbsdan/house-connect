<?php

class WorkerDocuments
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function createWorkerDocument($curriculumVitae, $validID, $nbi = null, $medical = null, $certificate = null)
    {
        $sql = "INSERT INTO worker_documents (curriculumVitae, validID, nbi, medical, certificate) 
                VALUES ('$curriculumVitae', '$validID', ";
        if ($nbi !== null) {
            $sql .= "'$nbi', ";
        } else {
            $sql .= "NULL, ";
        }
        if ($medical !== null) {
            $sql .= "'$medical', ";
        } else {
            $sql .= "NULL, ";
        }
        if ($certificate !== null) {
            $sql .= "'$certificate')";
        } else {
            $sql .= "NULL)";
        }
    
        if ($this->conn->query($sql)) {
            return $this->conn->insert_id;
        } else {
            return false;
        }
    }    

    public function getWorkerDocumentById($idWorkerDocuments)
    {
        $sql = "SELECT * FROM worker_documents WHERE idWorkerDocuments = $idWorkerDocuments";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }

    public function updateWorkerDocument($idWorkerDocuments, $curriculumVitae = null, $validID = null, $nbi = null, $medical = null, $certificate = null)
    {
        $updates = [];
        if ($curriculumVitae !== null) {
            $updates[] = "curriculumVitae = '$curriculumVitae'";
        }
        if ($validID !== null) {
            $updates[] = "validID = '$validID'";
        }
        if ($nbi !== null) {
            $updates[] = "nbi = '$nbi'";
        }
        if ($medical !== null) {
            $updates[] = "medical = '$medical'";
        }
        if ($certificate !== null) {
            $updates[] = "certificate = '$certificate'";
        }

        if (empty($updates)) {
            return false; // No updates provided
        }

        $sql = "UPDATE worker_documents SET " . implode(", ", $updates) . " WHERE idWorkerDocuments = $idWorkerDocuments";

        return $this->conn->query($sql);
    }

    public function deleteWorkerDocument($idWorkerDocuments)
    {
        $sql = "DELETE FROM worker_documents WHERE idWorkerDocuments = $idWorkerDocuments";
        return $this->conn->query($sql);
    }
}

?>
