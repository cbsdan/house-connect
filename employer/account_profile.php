<?php
    //Check first if the user is logged in
    include_once('../functions/user_authenticate.php');

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
                    <nav >
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
                        <img class='edit-profile' src='../img/edit-icon.png' placeholder>
                        <button class='save-changes hidden'>Save Changes</button>
                    </div>
                </div>
                <div class='info view-only'>
                    <div class='left'>
                        <p class='label'>Email Address</p>
                        <p class='text-box'>[email address]</p>
                        <p class='label'>First Name</p>
                        <p class='text-box'>[First Name]</p>
                        <p class='label'>Last Name</p>
                        <p class='text-box'>[Last Name]</p>
                        <p class='label'>Sex</p>
                        <p class='text-box'>[Sex]</p>
                        <p class='label'>Birthdate</p>
                        <p class='text-box'>[Birthdate]</p>
                    </div>
                    <div class='right'>
                        <p class='label'>Verification Status</p>
                        <p class='text-box'>[Verification Status]</p>
                        <p class='label'>Valid ID</p>
                        <p class='text-box'>[Click Edit to upload or Click to view]</p>
                        <p class='label'>Address</p>
                        <p class='text-box'>[Address]</p>
                        <p class='label'>Contact Number</p>
                        <p class='text-box'>[Contact Number]</p>
                        <p class='label'>User Type</p>
                        <p class='text-box'>[User Type]</p>
                    </div>
                </div>

                <form class='info editable hidden' action='' method='POST' enctype="multipart/form-data">
                    <div class='left'>
                        <label class='label'>Email Address</label>
                        <input class='text-box' readonly placeholder="[email address]">
                        <label class='label'>First Name</label>
                        <input class='text-box' placeholder="[First Name]">
                        <label class='label'>Last Name</label>
                        <input class='text-box' placeholder="[Last Name]">
                        <label class='label'>Sex</label>
                        <input class='text-box' placeholder="[Sex]">
                        <label class='label'>Birthdate</label>
                        <input class='text-box' type='date'>
                    </div>
                    <div class='right'>
                        <label class='label'>Verification Status</label>
                        <input class='text-box' placeholder="Not Verified" read-only>
                        <label class='label'>Upload a Valid ID</label>
                        <input class='text-box' type="file" id="file" name="valid id" accept="image/png, image/jpeg, image/jpg">
                        <label class='label'>Address</label>
                        <input class='text-box' placeholder="[Address]">
                        <label class='label'>Contact Number</label>
                        <input class='text-box' placeholder="[Contact Number]">
                        <label class='label'>User Type</label>
                        <input class='text-box' placeholder="[User Type]">
                    </div>
                </form>
            </div>
        </div>
    </main>


</body>
</html>