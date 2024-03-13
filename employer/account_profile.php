<?php
include_once('../database/connect.php');

// Check if the user is logged in
if ($_SESSION['userType'] == 'Admin') {
    header('Location: ../admin/dashboard.php');
    exit();
}

if ($_SESSION['userType'] == 'Worker') {
    header('Location: ../worker/application.php');
    exit();
}

$userData = fetchEmployerData($_SESSION['idUser']);

if ($userData['profilePic']) {
    $profilePic = getImageSrc($userData['profilePic']);
} else {
    $profilePic = '../img/user-icon.png';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House Connect</title>
    <link rel="icon" href="../img/favicon.ico" type="image/x-icon">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="../styles/variables.css">
    <link rel="stylesheet" href="../styles/includes.css">
    <link rel="stylesheet" href="../styles/media_query.css">
    <link rel="stylesheet" href="../styles/default.css">

    <link rel="stylesheet" href="../styles/employer_styles/account_profile.css">
    <link rel="stylesheet" href="../styles/employer_styles/default.css">

    <!-- JavaScript -->
    <script src='../scripts/worker.js' defer></script>
    <script>
        function redirectToEditProfile() {
            window.location.href = 'edit_profile.php?id=<?php echo $_SESSION['idUser']; ?>';
        }
    </script>
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
                        <a href='./request_a_worker.php' class='c-light fw-bold'>REQUEST A WORKER</a>
                        <a href='./worker_requests.php' class='c-light fw-bold'>WORKER REQUESTS</a>
                        <a href='./manage_worker.php' class='c-light'>MANAGE WORKER</a>
                        <a href='./account_profile.php' class='c-light'>ACCOUNT PROFILE</a>
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
                    <div class='left'>
                        <div class='image-preview'><img class='user-profile' src='<?php echo $profilePic?>' placeholder='profile'></div>
                        <h3>Account Profile <span class = 'c-blue'>(User ID: <?php echo $userData['idUser'];?>)</span></h3>
                    </div>
                    <div class='right'>
                        <img class='edit-profile' src='../img/edit-icon.png' placeholder onclick="redirectToEditProfile()">
                        <button class='save-changes hidden' onclick="toggleEditMode()">Save Changes</button>
                    </div>
                </div>
                <div class='not-verified-label <?php echo (($userData['verifyStatus'] == 'Not Verified') && !(isset($userData['validId'])) ? '' : 'hidden' );?>'>
                    <p class='fs-medium m-b-2 t-align-center f-italic c-main-color'>You are not verified, To use the other tab, please upload one valid id and wait for admin approval!</p>
                </div>
                <div class='wait-for-approval-label <?php echo (($userData['verifyStatus'] == 'Not Verified') && (isset($userData['validId'])) ? '' : 'hidden' );?>'>
                    <p class='fs-medium m-b-2 t-align-center f-italic c-main-color'>Please wait for an approval of your valid id from administrator.</p>
                </div>
                <div class='info view-only'>
                    <div class='left'>
                        <!-- Display fetched user information -->
                        <p class='label'>Email Address</p>
                        <p class='text-box'><?php echo $userData['email']; ?></p>
                        <p class='label'>First Name</p>
                        <p class='text-box'><?php echo $userData['fname']; ?></p>
                        <p class='label'>Last Name</p>
                        <p class='text-box'><?php echo $userData['lname']; ?></p>
                        <p class='label'>Sex</p>
                        <p class='text-box'><?php echo $userData['sex']; ?></p>
                        <p class='label'>Birthdate</p>
                        <p class='text-box'><?php echo $userData['birthdate']; ?></p>
                    </div>
                    <div class='right'>
                        <!-- Display other fetched user information -->
                        <p class='label'>Verification Status</p>
                        <p class='text-box'><?php echo $userData['verifyStatus']; ?></p>
                        <p class='label'>Address</p>
                        <p class='text-box'><?php echo $userData['address']; ?></p>
                        <p class='label'>Contact Number</p>
                        <p class='text-box'><?php echo $userData['contactNo']; ?></p>
                        <p class='label'>User Type</p>
                        <p class='text-box'><?php echo $userData['userType']; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
