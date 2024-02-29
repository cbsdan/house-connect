<?php
include_once('../database/connect.php');
include_once('../functions/user_authenticate.php');

// Check if the user is logged in
if (!isset($_SESSION['idUser'])) {
    header('Location: ../login.php'); // Redirect to login if not logged in
    exit();
}

$userData = fetchUserData($_SESSION['idUser']);

if ($_SESSION['userType'] == 'Worker') {
    header('Location: ../worker/application.php');
    exit();
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
                    <div class='left'>
                        <img class='user-profile' src='../img/user-icon.png' placeholder='profile'>
                        <h3>Account Profile</h3>
                    </div>
                    <div class='right'>
                        <img class='edit-profile' src='../img/edit-icon.png' placeholder onclick="redirectToEditProfile()">
                        <button class='save-changes hidden' onclick="toggleEditMode()">Save Changes</button>
                    </div>
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
                        <p class='text-box'><?php echo $userData['verification_status']; ?></p>
                        <!-- Omitted Valid ID for security reasons -->
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
