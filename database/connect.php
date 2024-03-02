<?php
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
?>
