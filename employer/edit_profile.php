<?php
include_once('../database/connect.php');
include_once('../functions/user_authenticate.php');

if ($_SESSION['userType'] == 'Worker') {
    header('Location: ../worker/application.php');
    exit();
}

if ($_SESSION['userType'] == 'Admin') {
    header('Location: ../admin/dashboard.php');
    exit();
}

// Fetch user data based on the provided user ID in the URL
if (isset($_GET['id'])) {
    $editUserId = $_GET['id'];
    $editUserData = fetchEmployerData($editUserId);

    if (!$editUserData) {
        // Redirect if the user data is not found
        header('Location: account_profile.php');
        exit();
    }
} else {
    // Redirect if no user ID is provided in the URL
    header('Location: account_profile.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Extract the data from the form
    $birthdate = $_POST['birthdate'];
    $address = $_POST['address'];
    $contactNo = $_POST['contactNo'];

    if (isset($_FILES['profilePic']) && $_FILES["profilePic"]["error"] == 0) {
        $profilePic = addslashes(file_get_contents($_FILES['profilePic']['tmp_name']));
    } else {
        $profilePic = '';
    }

    if (isset($_FILES['validId']) && $_FILES["validId"]["error"] == 0) {
        $validId = addslashes(file_get_contents($_FILES['validId']['tmp_name']));
    } else {
        $validId = '';
    }

    // Update the database with the new data
    updateProfileData($editUserId, $birthdate, $address, $contactNo, $profilePic, $validId);

    //Redirect back to the profile page or any other desired page
    header('Location: account_profile.php');
    exit();
}

//get user profile image source
if (isset($editUserData['profilePic']) && !($editUserData['profilePic'] == '')) {
    $profilePic = getImageSrc($editUserData['profilePic']);
} else {
    $profilePic = '../img/user-icon.png';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House Connect - Edit Profile</title>
    <link rel="icon" href="../img/favicon.ico" type="image/x-icon">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="../styles/variables.css">
    <link rel="stylesheet" href="../styles/includes.css">
    <link rel="stylesheet" href="../styles/media_query.css">
    <link rel="stylesheet" href="../styles/default.css">

    <link rel="stylesheet" href="../styles/employer_styles/edit_profile.css">
    <link rel="stylesheet" href="../styles/employer_styles/default.css">
</head>

<body>
<header class='logged-in'>
        <div class='content logged-in'>
            <div class='top'>
                <a href="../index.php" class='logo-container'>
                    <img src="../img/logo.png" id='logo'>
                    <h4>HOUSE CONNECT</h4>
                </a>
            </div>
            <div class='bottom' id='nav-worker'>
                <div class='navigation-container'>
                    <nav>
                        <a href='./find_a_worker.php' class='c-light'>FIND A WORKER</a>
                        <a href='./manage_worker.php' class='c-light'>MANAGE WORKER</a>
                        <a href='./account_profile.php' class='c-light fw-bold'>ACCOUNT PROFILE</a>
                    </nav>
                    <nav>
                        <a href='../logout.php' class='c-light'>LOG OUT</a>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <main class='employer'>
        <div class='container application'>
            <div class='content'>
                <div class='title'>
                    <div class='left m-b-2'>
                        <div class='image-preview'><img class='user-profile' src='<?php echo $profilePic?>' placeholder='profile'></div>
                        <h3>Account Profile</h3>
                    </div>
                </div>
                <div class='info view-only'>
                    <form class='info editable' action='' method='POST' enctype="multipart/form-data">
                        <div class='left'>
                            <label class='label'>Email Address</label>
                            <input class='text-box' name='email' readonly value="<?php echo $editUserData['email']; ?>">
                            <label class='label'>First Name</label>
                            <input class='text-box' name='fname' readonly value="<?php echo $editUserData['fname']; ?>">
                            <label class='label'>Last Name</label>
                            <input class='text-box' name='lname' readonly value="<?php echo $editUserData['lname']; ?>">
                            <label class='label'>Sex</label>
                            <input class='text-box' name='sex' readonly value="<?php echo $editUserData['sex']; ?>">
                            <label class='label'>Birthdate (Editable)</label>
                            <input class='text-box' name='birthdate' type='date' value="<?php echo $editUserData['birthdate']; ?>">
                        </div>
                        <div class='right'>
                            <label class='label'>Verification Status</label>
                            <input class='text-box' name='verification_status' readonly value="<?php echo $editUserData['verifyStatus']; ?>">
                            <label class='label'>Address (Editable)</label>
                            <input class='text-box' name='address' placeholder="[Address]" value="<?php echo $editUserData['address']; ?>">
                            <label class='label'>Contact Number (Editable)</label>
                            <input class='text-box' name='contactNo' placeholder="[Contact Number]" value="<?php echo $editUserData['contactNo']; ?>">
                            <label for="imageUpload" class='label'>Choose an image to upload</label>
                            <input type="file" class='text-box' id="imageUpload" name="profilePic" accept="image/jpeg, image/png, image/jpg">
                            <label for="imageUpload" class='label'>Upload a valid ID</label>
                            <input type="file" class='text-box' id="idUpload" name="validId" accept="image/jpeg, image/png, image/jpg">
                            <button type='submit' class='orange-white-btn m-t-auto'>Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
