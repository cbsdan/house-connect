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
function fetchWorkerInformation($conn = null) {
    global $conn;
    $workerQuery = "SELECT u.fname, u.lname, w.idUser, w.workerType, w.idWorker, w.profilePic, w.height, w.yearsOfExperience 
                    FROM worker w
                    LEFT JOIN user u ON u.idUser = w.idUser
                    WHERE w.workerStatus = 'Available'";
    $workerResult = $conn->query($workerQuery);
    
    // Initialize an array to store all rows
    $rows = array();
    
    // Fetch each row and add it to the array
    while ($row = $workerResult->fetch_assoc()) {
        $rows[] = $row;
    }
    
    // Return the array containing all rows
    return $rows;
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
        
        $sql = "SELECT ws.amount as workerSalaryAmount, ws.modified_at
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

    function getWorkerSalaryAndPaymentDetails($idUser, $idContract = null) {
        global $conn;
        
        $sql = "SELECT ws.paypalEmail as workerPaypalEmail,
                       ws.amount as workerSalaryAmount,
                       ep.amount as employerPaymentAmount,
                       c.endDate, c.idContract, c.startDate,
                       ws.status as workerSalaryStatus
                FROM worker w
                INNER JOIN contract c ON c.idWorker = w.idWorker
                INNER JOIN employer_payment ep ON ep.idContract = c.idContract
                INNER JOIN worker_salary ws ON ws.idEmployerPayment = ep.idEmployerPayment
                WHERE w.idUser = $idUser";

        if ($idContract != null) {
            $sql .= " AND c.idContract = $idContract";
        }
        
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

    function fetchEmployerInformation($conn = null) {
        global $conn;
        $employerQuery = "SELECT u.fname, u.lname, e.idUser, e.idEmployer, e.profilePic
                        FROM employer e
                        LEFT JOIN user u ON u.idUser = e.idUser";
        $employerResult = $conn->query($employerQuery);
        
        // Initialize an array to store all rows
        $rows = array();
        
        // Fetch each row and add it to the array
        while ($row = $employerResult->fetch_assoc()) {
            $rows[] = $row;
        }
        
        // Return the array containing all rows
        return $rows;
    }

    function insertNewContract($idWorker, $idEmployer, $contractStatus = null, $startDate = null, $endDate = null, $salaryAmt = null, $contractImg = null) {
        global $conn; // Assuming $conn is your database connection object
    
        // Get current datetime
        $currentDateTime = date("Y-m-d H:i:s");
    
        if (checkEmployerExists($idEmployer) == false) {
            return false;
        }
    
        // Escape variables to avoid SQL injection
        $idWorker = mysqli_real_escape_string($conn, $idWorker);
        $idEmployer = mysqli_real_escape_string($conn, $idEmployer);
        $contractStatus = mysqli_real_escape_string($conn, $contractStatus);
        $startDate = mysqli_real_escape_string($conn, $startDate);
        $endDate = mysqli_real_escape_string($conn, $endDate);
        $salaryAmt = mysqli_real_escape_string($conn, $salaryAmt);
        $contractImg = mysqli_real_escape_string($conn, $contractImg);
    
        // Prepare SQL statement to insert a new contract
        $sql = "INSERT INTO contract (idWorker, idEmployer, contractStatus, startDate, endDate, salaryAmt, contractImg, date_created) 
                VALUES ('$idWorker', '$idEmployer', '$contractStatus', '$startDate', '$endDate', '$salaryAmt', '$contractImg', '$currentDateTime')";
    
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

    // Add this function to your connect.php or another appropriate file
    function getYearsOfExperienceByContractId($contractId) {
        global $conn;

        $query = "SELECT w.yearsOfExperience
                FROM contract c
                JOIN worker w ON c.idWorker = w.idWorker
                WHERE c.idContract = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $contractId);
        $stmt->execute();
        $stmt->bind_result($yearsOfExperience);
        $stmt->fetch();
        $stmt->close();

        return isset($yearsOfExperience) ? $yearsOfExperience : 0;
    }

        // Function to get profile picture by contract ID
    function getProfilePicByContractId($contractId) {
        global $conn;

        $query = "SELECT w.profilePic
                FROM contract c
                JOIN worker w ON c.idWorker = w.idWorker
                WHERE c.idContract = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $contractId);
        $stmt->execute();
        $stmt->bind_result($profilePic);
        $stmt->fetch();
        $stmt->close();

        return $profilePic !== null ? $profilePic : null;
    }

        // Add this function to your connect.php or another appropriate file
    function getCurriculumVitaeByContractId($contractId) {
        global $conn;

        $query = "SELECT wd.curriculumVitae
                FROM contract c
                JOIN worker w ON c.idWorker = w.idWorker
                JOIN worker_documents wd ON w.idWorkerDocuments = wd.idWorkerDocuments
                WHERE c.idContract = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $contractId);
        $stmt->execute();
        $stmt->bind_result($curriculumVitae);
        $stmt->fetch();
        $stmt->close();

        return isset($curriculumVitae) ? $curriculumVitae : '';
    }

    // Add this function to your connect.php or another appropriate file
    function calculateAgeByContractId($contractId) {
        global $conn;

        $query = "SELECT TIMESTAMPDIFF(YEAR, u.birthdate, CURDATE()) AS age
                FROM contract c
                JOIN worker w ON c.idWorker = w.idWorker
                JOIN user u ON w.idUser = u.idUser
                WHERE c.idContract = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $contractId);
        $stmt->execute();
        $stmt->bind_result($age);
        $stmt->fetch();
        $stmt->close();

        return isset($age) ? $age : 0;
    }

    function getAllEmployerPayments($idEmployerPayment = null, $idContract = null, $filter = null) {
        global $conn; // Assuming $conn is your database connection object
    
        // Prepare the base SQL query
        $sql = "SELECT 
                    ep.idEmployerPayment, ep.amount as employerPaymentAmount, ep.method as employerPaymentMethod, ep.imgReceipt, ep.paymentStatus as employerPaymentStatus, ep.submitted_at,
                    ws.idWorkerSalary, ws.paypalEmail, ws.amount as workerSalaryAmount, ws.status as workerSalaryStatus, ws.modified_at,
                    c.idContract, c.contractStatus, c.startDate, c.endDate, c.salaryAmt, c.contractImg, c.date_created,
                    m.idMeeting, m.meetDate, m.platform, m.link, m.employerMessage,
                    uw.idUser as workerIdUser, uw.fname as workerFname, uw.lname as workerLname, uw.sex as workerSex, uw.birthdate as workerBirthdate, uw.address as workerAddress, uw.contactNo as workerContactNo, uw.email as workerEmail,
                    w.idWorker, w.workerType, w.profilePic as workerProfilePic, w.verifyStatus as workerVerifyStatus, w.workerStatus, w.paypalEmail as workerPaypalEmail, w.idWorkerDocuments, w.yearsOfExperience, w.height,
                    wd.curriculumVitae,
                    e.idEmployer, e.profilePic as employerProfilePic, e.validId, e.verifyStatus as employerVerifyStatus,
                    ue.idUser as employerIdUser, ue.fname as employerFname, ue.lname as employerLname, ue.birthdate as employerBirthdate, ue.sex as employerSex, ue.email as employerEmail
                FROM employer_payment ep
                LEFT JOIN worker_salary ws ON ws.idEmployerPayment = ep.idEmployerPayment 
                LEFT JOIN contract c ON c.idContract = ep.idContract
                LEFT JOIN meeting m ON m.contract_idContract = c.idContract
                LEFT JOIN worker w ON c.idWorker = w.idWorker
                LEFT JOIN worker_documents wd ON wd.idWorkerDocuments = w.idWorkerDocuments 
                LEFT JOIN user uw ON uw.idUser = w.idUser
                LEFT JOIN employer e ON c.idEmployer = e.idEmployer
                LEFT JOIN user ue ON e.idUser = ue.idUser";
     
        // If idContract is provided, add WHERE clause to filter by idContract
        if ($idEmployerPayment !== null && $idContract == null) {
            $sql .= " WHERE ep.idEmployerPayment = $idEmployerPayment";
            if ($filter != null) {
                $sql .= " AND ep.paymentStatus = '$filter'";
            }
        }
        if ($filter != null && $idEmployerPayment == null) {
            $sql .= " WHERE ep.paymentStatus = '$filter'";
        }
        if ($idContract !== null && $idEmployerPayment == null) {
            $sql .= " WHERE ep.idContract = $idContract";
        }
        
        // Create a prepared statement
        $stmt = $conn->prepare($sql);

        // Execute the prepared statement
        $stmt->execute();
    
        // Get the result
        $result = $stmt->get_result();
    
        // Check if there are any rows returned
        if ($result->num_rows > 0) {
            // Fetch all rows and return as an array
            $employerPayments = $result->fetch_all(MYSQLI_ASSOC);
            return $employerPayments;
        } else {
            // Return null if no rows found
            return null;
        }
    }
    
    function deleteEmployerPayment($idEmployerPayment) {
        global $conn; // Assuming $conn is your database connection object
    
        // Prepare SQL statement to delete employer_payment
        $sql = "DELETE FROM employer_payment WHERE idEmployerPayment = ?";
    
        // Create a prepared statement
        $stmt = $conn->prepare($sql);
    
        // Bind idEmployerPayment parameter
        $stmt->bind_param("i", $idEmployerPayment);
    
        // Execute the prepared statement
        if ($stmt->execute()) {
            return true; // Return true if deletion is successful
        } else {
            return false; // Return false if deletion fails
        }
    }

    function updateEmployerPayment($idEmployerPayment, $employerPaymentAmount = null, $employerPaymentStatus = null, $imgReceipt = null, $submitted_at = null) {
        global $conn; // Assuming $conn is your database connection object

        // Construct the SQL query for updating employer_payment
        $sql = "UPDATE employer_payment SET ";
        $updates = array();
        
        // Build SQL query dynamically based on provided parameters
        if ($employerPaymentAmount !== null) {
            $employerPaymentAmount = mysqli_real_escape_string($conn, $employerPaymentAmount);
            $updates[] = "amount = '$employerPaymentAmount'";
        }
        if ($employerPaymentStatus !== null) {
            $employerPaymentStatus = mysqli_real_escape_string($conn, $employerPaymentStatus);
            $updates[] = "paymentStatus = '$employerPaymentStatus'";
        }
        if ($imgReceipt !== null) {
            $updates[] = "imgReceipt = '$imgReceipt'";
        }
        if ($submitted_at !== null) {
            $submitted_at = mysqli_real_escape_string($conn, $submitted_at);
            $updates[] = "submitted_at = '$submitted_at'";
        }
        
        // Join the updates into a single string
        $sql .= implode(", ", $updates);
        
        // Add WHERE clause for specific idEmployerPayment
        $sql .= " WHERE idEmployerPayment = $idEmployerPayment";
        
        // Execute the query
        if ($conn->query($sql) === TRUE) {
            return true; // Return true if update is successful
        } else {
            return false; // Return false if update fails
        }
        
    }
    
    function insertEmployerPayment($idContract, $amount, $method, $imgReceipt, $paymentStatus = 'Pending') {
        global $conn; // Assuming $conn is your database connection object

        // Prepare values for insertion
        $amount = mysqli_real_escape_string($conn, $amount); // Assuming $amount is a variable containing the amount
        $method = mysqli_real_escape_string($conn, $method); // Assuming $method is a variable containing the method
        $paymentStatus = mysqli_real_escape_string($conn, $paymentStatus); // Assuming $paymentStatus is a variable containing the payment status
        $idContract = mysqli_real_escape_string($conn, $idContract); // Assuming $idContract is a variable containing the contract ID

        // Construct the SQL query
        $sql = "INSERT INTO employer_payment (amount, method, imgReceipt, paymentStatus, idContract) 
                VALUES ('$amount', '$method', '$imgReceipt', '$paymentStatus', '$idContract')";

        // Execute the query
        if (mysqli_query($conn, $sql)) {
            // Return the idEmployerPayment of the inserted row
            return mysqli_insert_id($conn);
        } else {
            return false; // Return false if execution fails
        }
    }

    function insertWorkerSalary($paypalEmail, $amount, $idEmployerPayment, $status = 'Pending') {
        global $conn; // Assuming $conn is your database connection object
    
        // Prepare SQL statement to insert into worker_salary
        $sql = "INSERT INTO worker_salary (paypalEmail, amount, status, idEmployerPayment) VALUES (?, ?, ?, ?)";
    
        // Create a prepared statement
        $stmt = $conn->prepare($sql);
    
        // Bind parameters
        if ($stmt) {
            $stmt->bind_param("sdsi", $paypalEmail, $amount, $status, $idEmployerPayment);
    
            // Execute the prepared statement
            if ($stmt->execute()) {
                return true; // Return true if insertion is successful
            } else {
                return false; // Return false if execution fails
            }
        } else {
            return false; // Return false if prepared statement creation fails
        }
    }
    
    function updateWorkerSalary($idEmployerPayment, $amount = null, $status = null, $paypalEmail = null) {
        global $conn; // Assuming $conn is your database connection object
    
        // Prepare SQL statement to update worker_salary
        $sql = "UPDATE worker_salary SET ";
        $updates = array();
    
        // Build SQL query dynamically based on provided parameters
        if ($amount !== null) {
            $updates[] = "amount = ?";
        }
        if ($status !== null) {
            $updates[] = "status = ?";
        }
        if ($paypalEmail !== null) {
            $updates[] = "paypalEmail = ?";
        }
    
        // Join the updates into a single string
        $sql .= implode(", ", $updates);
    
        // Add WHERE clause for specific idWorkerSalary
        $sql .= " WHERE idEmployerPayment = ?";
    
        // Create a prepared statement
        $stmt = $conn->prepare($sql);
    
        // Bind parameters
        if ($stmt) {
            $paramTypes = ""; // Parameter types string
            $paramValues = array(); // Array to store parameter values
    
            // Bind parameter values and types dynamically
            if ($amount !== null) {
                $paramTypes .= "d"; // Assuming amount is a double
                $paramValues[] = $amount;
            }
            if ($status !== null) {
                $paramTypes .= "s"; // Assuming status is a string
                $paramValues[] = $status;
            }
            if ($paypalEmail !== null) {
                $paramTypes .= "s"; // Assuming paypalEmail is a string
                $paramValues[] = $paypalEmail;
            }
    
            // Bind idWorkerSalary parameter
            $paramTypes .= "i"; // Assuming idWorkerSalary is an integer
            $paramValues[] = $idEmployerPayment;
    
            // Bind parameters
            $stmt->bind_param($paramTypes, ...$paramValues);
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

    function updateWorkerSalaryPayment($idWorkerSalary, $paypalEmail = null, $workerSalaryAmount = null, $workerSalaryStatus = null) {
        global $conn; // Assuming $conn is your database connection object
    
        // Prepare SQL statement to update worker_salary
        $sql = "UPDATE worker_salary SET ";
        $updates = array();
    
        // Build SQL query dynamically based on provided parameters
        if ($paypalEmail !== null) {
            $updates[] = "paypalEmail = ?";
        }
        if ($workerSalaryAmount !== null) {
            $updates[] = "amount = ?";
        }
        if ($workerSalaryStatus !== null) {
            $updates[] = "status = ?";
        }
    
        // Join the updates into a single string
        $sql .= implode(", ", $updates);
    
        // Add WHERE clause for specific idWorkerSalary
        $sql .= " WHERE idWorkerSalary = ?";
    
        // Create a prepared statement
        $stmt = $conn->prepare($sql);
    
        // Bind parameters
        if ($stmt) {
            $paramTypes = ""; // Parameter types string
            $paramValues = array(); // Array to store parameter values
    
            // Bind parameter values and types dynamically
            if ($paypalEmail !== null) {
                $paramTypes .= "s"; // Assuming paypalEmail is a string
                $paramValues[] = $paypalEmail;
            }
            if ($workerSalaryAmount !== null) {
                $paramTypes .= "d"; // Assuming workerSalaryAmount is a double
                $paramValues[] = $workerSalaryAmount;
            }
            if ($workerSalaryStatus !== null) {
                $paramTypes .= "s"; // Assuming workerSalaryStatus is a string
                $paramValues[] = $workerSalaryStatus;
            }
    
            // Bind idWorkerSalary parameter
            $paramTypes .= "i"; // Assuming idWorkerSalary is an integer
            $paramValues[] = $idWorkerSalary;
    
            // Bind parameters
            $stmt->bind_param($paramTypes, ...$paramValues);
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
    
    function checkEmployerExists($idEmployer) {
        global $conn; // Assuming $conn is your database connection object
    
        // Prepare SQL statement to check if idEmployer exists in the employer table
        $sql = "SELECT COUNT(*) AS count FROM employer WHERE idEmployer = ?";
    
        // Create a prepared statement
        $stmt = $conn->prepare($sql);
    
        // Bind parameter
        $stmt->bind_param("i", $idEmployer);
    
        // Execute the prepared statement
        $stmt->execute();
    
        // Bind result variables
        $stmt->bind_result($count);
    
        // Fetch result
        $stmt->fetch();
    
        // Close statement
        $stmt->close();
    
        // Return true if count is greater than 0, indicating idEmployer exists, otherwise return false
        return $count > 0;
    }

    
?>


