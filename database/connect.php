<?php
    //Check first if the user is logged in
    include_once('../functions/user_authenticate.php');

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "house_connect"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

function query($query) {
    global $conn; 
    $result = mysqli_query($conn, $query);
    if(!is_bool($result) && mysqli_num_rows($result) > 0)
    {
        $res = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $res[] = $row;

        }
        if (count($res) == 1) {
            $res = $res[0];
        }
        
        return $res;
    }

    return false;
}
function getImageType($blobData) {
    $signature = substr($blobData, 0, 4); // Get the first 4 bytes (magic number)

    // Check for JPEG or JPG
    if ($signature === "\xFF\xD8\xFF\xE0" || $signature === "\xFF\xD8\xFF\xE1") {
        return 'image/jpeg';
    }

    // Check for PNG
    if ($signature === "\x89\x50\x4E\x47") {
        return 'image/png';
    }

    return null; // Unknown image type
}

//FOR WORKER FUNCTIONS
// Function to fetch user information
function fetchUserInformation($conn) {
    $userQuery = "SELECT idUser, userType, email, fname, lname, sex, birthdate, address, contactNo FROM user";
    $userResult = $conn->query($userQuery);
    return $userResult->fetch_assoc();
}

// Function to fetch worker information
function fetchWorkerInformation($conn) {
    $workerQuery = "SELECT idUser, profilePic, height, yearsOfExperience FROM worker";
    $workerResult = $conn->query($workerQuery);
    return $workerResult->fetch_assoc();
}

function fetchAllUserInformation($idUser, $userType) {
    global $conn;
    if ($userType == 'Worker') {
        $sql = "SELECT *, user.idUser FROM user LEFT JOIN worker ON user.idUser = worker.idUser LEFT JOIN worker_documents ON worker.idWorkerDocuments = worker_documents.idWorkerDocuments WHERE user.idUser = ". $idUser;
    } else if ($userType == 'Employer') {
        $sql = "SELECT *, user.idUser FROM user LEFT JOIN employer ON user.idUser = employer.idUser WHERE user.idUser = ". $idUser;
    }
    if (isset($sql)) {
        $result = $conn->query($sql);
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            if (isset($user['profilePic'])) {
                $user['profilePic'] = getImageSrc($user['profilePic']);
            } else {
                $user['profilePic'] = '../img/user-icon.png';
            }

            //For employer
            if(isset($user['validId'])) {
                $user['validId'] = getImageSrc($user['validId']);
            }

            //For Worker
            if(isset($user['validID'])) {
                $user['validID'] = getImageSrc($user['validID']);
            }
            if(isset($user['curriculumVitae'])) {
                $user['curriculumVitae'] = getImageSrc($user['curriculumVitae']);
            }
            if(isset($user['medical'])) {
                $user['medical'] = getImageSrc($user['medical']);
            }
            if(isset($user['nbi'])) {
                $user['nbi'] = getImageSrc($user['nbi']);
            }
            if(isset($user['certificate'])) {
                $user['certificate'] = getImageSrc($user['certificate']);
            }

            return $user;
        }  else {
            return null;
        }
    } else {
        return null;
    }
}

// FOR BLOB IMAGE
function getImageSrc($profile) {
    $mimeType = getImageType($profile);

    if ($mimeType === 'image/jpeg' || $mimeType === 'image/png') {
        // Convert blob to base64
        $base64Image = base64_encode($profile);

        // Create a data URI for the image
        $dataUri = 'data:' . $mimeType . ';base64,' . $base64Image;
    } else {
        // Handle unknown image type
        $dataUri = null;
    }
    return $dataUri;
}

// Function to fetch employer data based on user ID
function fetchEmployerData($idUser) {
    global $conn;

    // Perform a SQL query to fetch user data
    $sql = "SELECT *, user.idUser FROM user LEFT JOIN employer ON employer.idUser = user.idUser WHERE user.idUser = $idUser ";
    $result = $conn->query($sql);

    // Check if the query was successful
    if ($result && $result->num_rows > 0) {
        // Fetch user data as an associative array
        $userData = $result->fetch_assoc();
        return $userData;
    } else {
        // Handle the case where no user data is found
        return false;
    }
}
function updateProfileData($idUser, $birthdate, $address, $contactNo, $profilePic, $validId) {
    global $conn;

    // Implement database update logic here using prepared statements to prevent SQL injection
    $stmt = $conn->prepare("UPDATE user SET birthdate=?, address=?, contactNo=? WHERE idUser=?");
    $stmt->bind_param("sssi", $birthdate, $address, $contactNo, $idUser);
    $stmt->execute();
    $stmt->close();

    if ($profilePic != '' && $validId == '') {
        $sql = "UPDATE employer SET profilePic = '$profilePic' WHERE idUser = $idUser";
    } else if ($profilePic == '' && $validId != '') {
        $sql = "UPDATE employer SET validId = '$validId' WHERE idUser = $idUser";
    } else if ($profilePic != '' && $validId != ''){
        $sql = "UPDATE employer SET profilePic = '$profilePic', validId = '$validId' WHERE idUser = $idUser";
    }

    if ($sql) {
        $conn->query($sql);
    }
}

function getWorkerDocuments($idUser) {

}

function getLatestContractInfo($idUser = null, $idContract = null, $contractStatus = null) {
    global $conn;
    if (isset($ididContractUser)) {
        $sql = "SELECT u.idUser, u.fname, u.lname, u.email, u.password, u.sex, u.birthdate, u.address,u.contactNo,
                       w.idWorker, w.workerType, w.workerStatus, w.profilePic as workerProfilePic, w.yearsOfExperience, w.height, w.paypalEmail, w.idWorkerDocuments,
                       c.contractStatus, c.startDate, c.endDate, c.salaryAmt, c.contractImg, c.date_created,
                       e.idEmployer, e.profilePic as employerProfilePic, e.verifyStatus,
                       m.idMeeting, m.meetDate, m.platform, m.link, m.employerMessage 
                FROM user u 
                LEFT JOIN worker w ON u.idUser = w.idWorker 
                LEFT JOIN contract c ON c.idWorker = w.idWorker 
                LEFT JOIN employer e ON e.idEmployer = c.idEmployer 
                LEFT JOIN meeting m ON m.contract_idContract = c.idContract 
                WHERE (u.idUser = $idUser OR c.idContract = $idContract) AND c.contractStatus = '$contractStatus'
                ORDER BY c.date_created 
                DESC LIMIT 1;";
    }
    
    if (isset($sql)) {
        $result = $conn->query($sql);
        if ($result->num_rows == 1) {
            $contract = $result->fetch_assoc();

            if (isset($contract['workerProfilePic'])) {
                $contract['workerProfilePic'] = getImageSrc($contract['profilePic']);
            } else {
                $contract['workerProfilePic'] = '../img/user-icon.png';
            }

            if (isset($contract['employerProfilePic'])) {
                $contract['employerProfilePic'] = getImageSrc($contract['profilePic']);
            } else {
                $contract['employerProfilePic'] = '../img/user-icon.png';
            }
            
            if (isset($contract['contractImg'])) {
                $contract['contractImg'] = getImageSrc($contract['profilePic']);
            } else {
                $contract['contractImg'] = '../img/user-icon.png';
            }

        }
        return $contract;
    } else {
        return null;
    }
}


    function getWorkerSalaryInformation($idContract) {
        global $conn;
        
        $sql = "SELECT ws.amount as workerSalaryAmount
                FROM worker_salary ws
                INNER JOIN employer_payment ep ON ws.idEmployerPayment = ep.idEmployerPayment
                INNER JOIN contract c ON ep.idContract = c.idContract
                WHERE c.idContract = $idContract";

        // Execute the query
        $result = $conn->query($sql);

        // Check if the query executed successfully
        if ($result === false) {
            return null;
        } else {
            // Fetch the result
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row; // Return the worker's salary
            } else {
                return null;
            }
        }
    }

    function getSalaryAndPaymentDetails($idContract) {
        global $conn;
        
        $sql = "SELECT ws.paypalEmail as workerPaypalEmail,
                       ws.amount as workerSalaryAmount,
                       ep.amount as employerPaymentAmount,
                       c.endDate,
                       ws.status as workerSalaryStatus
                FROM worker_salary ws
                INNER JOIN employer_payment ep ON ws.idEmployerPayment = ep.idEmployerPayment
                INNER JOIN contract c ON ep.idContract = c.idContract
                WHERE c.idContract = $idContract";
    
        // Execute the query
        $result = $conn->query($sql);
    
        // Check if the query executed successfully
        if ($result === false) {
            return null;
        } else {
            // Fetch the result
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row; // Return the worker's salary, employer payment amount, contract end date, and worker salary status
            } else {
                return null;
            }
        }
    }
    
    function getLatestContractByUserID($idUser) {
        global $conn;
        
        $sql = "SELECT c.*
                FROM contract c
                INNER JOIN worker w ON c.idWorker = w.idWorker
                WHERE w.idUser = $idUser
                ORDER BY c.endDate DESC
                LIMIT 1";
    
        // Execute the query
        $result = $conn->query($sql);
    
        // Check if the query executed successfully
        if ($result === false) {
            return null;
        } else {
            // Fetch the result
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if (isset($row['contractImg'])) {
                    $row['contractImg'] = getImageSrc($row['contractImg']);
                } else {
                    $row['contractImg'] = '../img/documents-icon.png';
                }
                return $row; // Return the latest contract of the worker
            } else {
                return null;
            }
        }
    }

    function getWorkerSalaryAndPaymentDetails($idUser) {
        global $conn;
        
        $sql = "SELECT ws.paypalEmail as workerPaypalEmail,
                       ws.amount as workerSalaryAmount,
                       ep.amount as employerPaymentAmount,
                       c.endDate,
                       ws.status as workerSalaryStatus
                FROM worker w
                INNER JOIN contract c ON c.idWorker = w.idWorker
                INNER JOIN employer_payment ep ON ep.idContract = c.idContract
                INNER JOIN worker_salary ws ON ws.idEmployerPayment = ep.idEmployerPayment
                WHERE w.idUser = $idUser";
    
        // Execute the query
        $result = $conn->query($sql);
    
        // Check if the query executed successfully
        if ($result === false) {
            return null;
        } else {
            // Fetch the result
            $data = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return $data; 
            } else {
                return null;
            }
        }
    }
    function getMeetingDetailsByIdContract($idContract) {
        global $conn;
        
        $sql = "SELECT meeting.idMeeting, meeting.meetDate, meeting.platform, meeting.link, meeting.employerMessage, user.fname, user.lname
                FROM meeting
                JOIN contract ON meeting.contract_idContract = contract.idContract
                JOIN employer ON contract.idEmployer = employer.idEmployer
                JOIN user ON employer.idUser = user.idUser
                WHERE contract.idContract = " . $idContract;
    
        // Execute the query
        $result = $conn->query($sql);
    
        // Check if the query executed successfully
        if ($result === false) {
            return null;
        } else {
            // Fetch the result
            $data = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data = $row;
                }
                return $data; // Return all the data
            } else {
                return null;
            }
        }
    }
    function getEmployerInformationByContractID($idContract) {
        global $conn;
        
        $sql = "SELECT 
                    ue.idUser as employerIdUser, ue.fname as employerFname, ue.lname as employerLname, ue.sex as employerSex, ue.birthdate as employerBirthdate, 
                    ue.address as employerAddress, ue.contactNo as employerContactNo, e.idEmployer, e.profilePic as employerProfilePic, e.validId, e.verifyStatus as employerVerifyStatus
                FROM user ue
                INNER JOIN employer e ON ue.idUser = e.idUser
                INNER JOIN contract c ON c.idEmployer = e.idEmployer
                WHERE c.idContract = " . $idContract; 
    
        // Execute the query
        $result = $conn->query($sql);
    
        // Check if the query executed successfully
        if ($result === false) {
            return null;
        } else {
            // Fetch the result
            $data = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    if (isset($row['employerProfilePic'])) {
                        $row['employerProfilePic'] = getImageSrc($row['employerProfilePic']);
                    } 
                    $data = $row;
                }
                return $data; // Return all the data
            } else {
                return null;
            }
        }
    }
    
    function fetchAllWorkerInformation($idUser) {
        global $conn;
    
        // Start building the SQL query
        $sql = "SELECT uw.*, w.*, wd.* FROM user uw
        JOIN worker w ON w.idUser = uw.idUser
        LEFT JOIN worker_documents wd ON wd.idWorkerDocuments = w.idWorkerDocuments
        WHERE uw.idUser = '$idUser'";
    
        // Execute the query
        $result = $conn->query($sql);
    
        // Check if the query executed successfully
        if ($result === false || $result->num_rows == 0) {
            return null;
        } else {
            // Fetch the result
            $row = $result->fetch_assoc();
    
            // Manipulate the row
            if (isset($row['profilePic'])) {
                $row['profilePic'] = getImageSrc($row['profilePic']);
            } else {
                $row['profilePic'] = '../img/user-icon.png';
            }
            if (isset($row['curriculumVitae'])) {
                $row['curriculumVitae'] = getImageSrc($row['curriculumVitae']);
            } else {
                $row['curriculumVitae'] = '../img/documents-icon.png';
            }
            if (isset($row['validID'])) {
                $row['validID'] = getImageSrc($row['validID']);
            } else {
                $row['validID'] = '../img/documents-icon.png';
            }
            if (isset($row['nbi'])) {
                $row['nbi'] = getImageSrc($row['nbi']);
            } else {
                $row['nbi'] = '../img/documents-icon.png';
            }
            if (isset($row['medical'])) {
                $row['medical'] = getImageSrc($row['medical']);
            } else {
                $row['medical'] = '../img/documents-icon.png';
            }
            if (isset($row['certificate'])) {
                $row['certificate'] = getImageSrc($row['certificate']);
            } 
    
            return $row;
        }
    }
    

    function searchCandidateWorkersIdUser($workerType, $sex = null, $age = null, $height = null, $yearsOfExperience = null) {
        global $conn; // Assuming you have a database connection
    
        // Start building the SQL query
        $sql = "SELECT uw.idUser FROM user uw
                JOIN worker w ON w.idUser = uw.idUser
                LEFT JOIN worker_documents wd ON wd.idWorkerDocuments = w.idWorkerDocuments
                WHERE w.workerType = '$workerType'";
    
        // Add conditional clauses for optional parameters
        if ($sex !== null) {
            $sql .= " AND uw.sex = '$sex'";
        }
        if ($age !== null) {
            // Calculate birthdate based on provided age
            $birthdate = date('Y-m-d', strtotime("-$age years"));
            $sql .= " AND uw.birthdate <= '$birthdate'";
        }
        if ($height !== null) {
            $sql .= " AND w.height >= '$height'";
        }
        if ($yearsOfExperience !== null) {
            $sql .= " AND w.yearsOfExperience >= '$yearsOfExperience'";
        }
        $sql .= " AND w.workerStatus = 'Available'";

        // Execute the query
        $result = $conn->query($sql);
    
        // Check if the query executed successfully
        if ($result === false || $result->num_rows == 0) {
            return null;
        } else {
            // Fetch the result
            $candidates = [];
            while ($row = $result->fetch_assoc()) {
                $candidates[] = $row;
            }
            return $candidates;
        }
    }

    function getEmployerOrWorkerID($idUser) {
        global $conn; // Assuming $conn is your database connection object
    
        // Query to fetch idEmployer or idWorker based on userType
        $sql = "SELECT 
                    CASE 
                        WHEN userType = 'Worker' THEN w.idWorker
                        WHEN userType = 'Employer' THEN e.idEmployer
                        ELSE NULL
                    END AS id
                FROM user u
                LEFT JOIN worker w ON u.idUser = w.idUser
                LEFT JOIN employer e ON u.idUser = e.idUser
                WHERE u.idUser = $idUser";
    
        // Execute the query
        $result = $conn->query($sql);
    
        // Check if the query executed successfully
        if ($result === false) {
            return null;
        } else {
            // Fetch the result
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row['id']; // Return idEmployer or idWorker based on userType
            } else {
                return null; // Return null if no user found
            }
        }
    }

    function insertNewContract($idWorker, $idEmployer) {
        global $conn; // Assuming $conn is your database connection object
    
        // Get current datetime
        $currentDateTime = date("Y-m-d H:i:s");
    
        // Prepare SQL statement to insert a new contract
        $sql = "INSERT INTO contract (idWorker, idEmployer, contractStatus, startDate, endDate, salaryAmt, contractImg, date_created) 
                VALUES ($idWorker, $idEmployer, 'Pending', NULL, NULL, NULL, NULL, '$currentDateTime')";
    
        // Execute the query
        if ($conn->query($sql) === true) {
            // Get the idContract of the inserted contract
            $idContract = $conn->insert_id;
            return $idContract; // Return the idContract if insertion is successful
        } else {
            return false; // Return false if insertion fails
        }
    }
    
    function insertNewMeeting($idContract, $platform, $link, $schedule, $message = null) {
        global $conn; // Assuming $conn is your database connection object
    
        // Prepare SQL statement to insert a new meeting
        $sql = "INSERT INTO meeting (contract_idContract, platform, link, meetDate, employerMessage) 
                VALUES ($idContract, '$platform', '$link', '$schedule', ?)";
    
        // Create a prepared statement
        $stmt = $conn->prepare($sql);
        
        // Bind parameters
        if ($stmt) {
            if ($message !== null) {
                $stmt->bind_param("s", $message);
            } else {
                $stmt->bind_param("s", $message);
            }
        } else {
            return false; // Return false if prepared statement creation fails
        }
    
        // Execute the prepared statement
        if ($stmt->execute()) {
            // Get the idMeeting of the inserted meeting
            $idMeeting = $stmt->insert_id;
            return $idMeeting; // Return the idMeeting if insertion is successful
        } else {
            return false; // Return false if insertion fails
        }
    }
    

    function updateWorkerStatus($idWorker, $status) {
        global $conn; // Assuming $conn is your database connection object
    
        // Prepare SQL statement to update workerStatus
        $sql = "UPDATE worker SET workerStatus = ? WHERE idWorker = ?";
    
        // Create a prepared statement
        $stmt = $conn->prepare($sql);
        
        // Bind parameters
        if ($stmt) {
            $stmt->bind_param("si", $status, $idWorker);
        } else {
            return false; // Return false if prepared statement creation fails
        }
    
        // Execute the prepared statement
        if ($stmt->execute()) {
            return true; // Return true if update is successful
        } else {
            return false; // Return false if update fails
        }
    }

    function getContractListByEmployerID($idEmployer) {
        global $conn; // Assuming $conn is your database connection object
    
        // Prepare SQL statement to fetch contract lists
        $sql = "SELECT 
                    c.idContract, c.contractStatus, c.startDate, c.endDate, c.salaryAmt, c.contractImg, c.date_created,
                    uw.idUser as workerIdUser, uw.fname as workerFname, uw.lname as workerLname, uw.sex as workerSex, uw.birthdate as workerBirthdate, uw.address as workerAddress, uw.contactNo as workerContactNo,
                    w.idWorker, w.workerType, w.profilePic as workerProfilePic, w.verifyStatus as workerVerifyStatus, w.paypalEmail as workerPaypalEmail, w.idWorkerDocuments, 
                    e.idEmployer, e.profilePic as employerProfilePic, e.validId, e.verifyStatus as employerVerifyStatus
                FROM contract c
                INNER JOIN worker w ON c.idWorker = w.idWorker
                INNER JOIN user uw ON uw.idUser = w.idUser
                INNER JOIN employer e ON c.idEmployer = e.idEmployer
                WHERE e.idEmployer = $idEmployer";
    
        // Execute the query
        $result = $conn->query($sql);
    
        // Check if the query executed successfully
        if ($result === false) {
            return null;
        } else {
            // Fetch the result
            $contracts = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $contracts[] = $row;
                }
                return $contracts; // Return the contract lists
            } else {
                return null; // Return null if no contracts found for the given employer
            }
        }
    }
    function getContractList($idContract = null) {
        global $conn; // Assuming $conn is your database connection object
    
        // Prepare SQL statement to fetch contract lists
        $sql = "SELECT 
                    c.idContract, c.contractStatus, c.startDate, c.endDate, c.salaryAmt, c.contractImg, c.date_created,
                    m.idMeeting, m.meetDate, m.platform, m.link, m.employerMessage,
                    uw.idUser as workerIdUser, uw.fname as workerFname, uw.lname as workerLname, uw.sex as workerSex, uw.birthdate as workerBirthdate, uw.address as workerAddress, uw.contactNo as workerContactNo, uw.email as workerEmail,
                    w.idWorker, w.workerType, w.profilePic as workerProfilePic, w.verifyStatus as workerVerifyStatus, w.paypalEmail as workerPaypalEmail, w.idWorkerDocuments, w.yearsOfExperience, w.height,
                    wd.curriculumVitae,
                    e.idEmployer, e.profilePic as employerProfilePic, e.validId, e.verifyStatus as employerVerifyStatus,
                    ue.idUser as employerIdUser, ue.fname as employerFname, ue.lname as employerLname, ue.birthdate as employerBirthdate, ue.sex as employerSex, ue.email as employerEmail
                FROM contract c
                LEFT JOIN meeting m ON m.contract_idContract = c.idContract
                LEFT JOIN worker w ON c.idWorker = w.idWorker
                LEFT JOIN worker_documents wd ON wd.idWorkerDocuments = w.idWorkerDocuments 
                LEFT JOIN user uw ON uw.idUser = w.idUser
                LEFT JOIN employer e ON c.idEmployer = e.idEmployer
                LEFT JOIN user ue ON e.idUser = ue.idUser";

        if ($idContract != null) {
            $sql .= " WHERE c.idContract = '$idContract'"; 
        }

        // Execute the query
        $result = $conn->query($sql);
    
        // Check if the query executed successfully
        if ($result === false) {
            return null;
        } else {
            // Fetch the result
            $contracts = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $contracts[] = $row;
                }
                return $contracts; // Return the contract lists
            } else {
                return null; // Return null if no contracts found for the given employer
            }
        }
    }
    
    function calculateAge($dob) {
        $dobObject = new DateTime($dob);
        $now = new DateTime();
        $age = $now->diff($dobObject);
        return $age->y;
    }

    function updateContract($idContract, $contractStatus = null, $startDate = null, $endDate = null, $salaryAmt = null, $contractImg = null) {
        global $conn; // Assuming $conn is your database connection object
    
        // Prepare SQL statement to update contract
        $sql = "UPDATE contract SET ";
        $updates = array();
    
        // Build SQL query dynamically based on provided parameters
        if ($contractStatus !== null) {
            $updates[] = "contractStatus = '$contractStatus'";
        }
        if ($startDate !== null) {
            $updates[] = "startDate = '$startDate'";
        }
        if ($endDate !== null) {
            $updates[] = "endDate = '$endDate'";
        }
        if ($salaryAmt !== null) {
            $updates[] = "salaryAmt = '$salaryAmt'";
        }
        if ($contractImg !== null) {
            $updates[] = "contractImg = '$contractImg'";
        }
        
        // Join the updates into a single string
        $sql .= implode(", ", $updates);
    
        // Add WHERE clause for specific idContract
        $sql .= " WHERE idContract = $idContract";

        // Execute the query
        if ($conn->query($sql) === TRUE) {
            return true; // Return true if update is successful
        } else {
            return false; // Return false if update fails
        }
    }

    function updateMeeting($idMeeting, $meetDate = null, $platform = null, $link = null, $employerMessage = null) {
        global $conn; // Assuming $conn is your database connection object
    
        // Prepare SQL statement to update meeting
        $sql = "UPDATE meeting SET ";
        $updates = array();
    
        // Build SQL query dynamically based on provided parameters
        if ($meetDate !== null) {
            $updates[] = "meetDate = '$meetDate'";
        }
        if ($platform !== null) {
            $updates[] = "platform = '$platform'";
        }
        if ($link !== null) {
            $updates[] = "link = '$link'";
        }
        if ($employerMessage !== null) {
            $updates[] = "employerMessage = ?";
        }
    
        // Join the updates into a single string
        $sql .= implode(", ", $updates);
    
        // Add WHERE clause for specific idMeeting
        $sql .= " WHERE idMeeting = $idMeeting";
    
        // Create a prepared statement
        $stmt = $conn->prepare($sql);
        
        // Bind parameter if employerMessage is provided
        if ($stmt && $employerMessage !== null) {
            $stmt->bind_param("s", $employerMessage);
        } else if ($stmt && $employerMessage === null) {
            return false;
        } else {
            return false; // Return false if prepared statement creation fails
        }
    
        // Execute the prepared statement
        if ($stmt->execute()) {
            return true; // Return true if update is successful
        } else {
            return false; // Return false if update fails
        }
    }
    
    function deleteContract($idContract) {
        global $conn; // Assuming $conn is your database connection object
    
        // Prepare SQL statement to delete contract
        $sql = "DELETE FROM contract WHERE idContract = ?";
    
        // Create a prepared statement
        $stmt = $conn->prepare($sql);
    
        // Bind idContract parameter
        $stmt->bind_param("i", $idContract);
    
        // Execute the prepared statement
        if ($stmt->execute()) {
            return true; // Return true if deletion is successful
        } else {
            return false; // Return false if deletion fails
        }
    }
    
?>


