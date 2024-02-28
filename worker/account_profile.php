<?php
    //Check first if the user is logged in
    include_once('../functions/user_authenticate.php');
    
    if ($_SESSION['userType'] == 'Employer') {
        header('Location: ../employer/account_profile.php');
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

    <link rel="stylesheet" href="../styles/worker_styles/account_profile.css">
    <link rel="stylesheet" href="../styles/worker_styles/default.css">

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
                        <a href='./current_employer.php' class='c-light'>CURRENT EMPLOYER</a>
                        <a href='./work_history.php' class='c-light'>WORK HISTORY</a>
                        <a href='./salary_payment.php' class='c-light'>SALARY PAYMENT</a>
                        <a href='./account_profile.php' class='c-light fw-bold'>ACCOUNT PROFILE</a>
                    </nav>
                    <nav>
                        <a href='../logout.php' class='c-light'>LOG OUT</a>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <main class='worker'>
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
                        <p class='label'>Height</p>
                        <p class='text-box'>[Height]</p>
                    </div>
                    <div class='right'>
                    <p class='label'>Birthdate</p>
                        <p class='text-box'>[Birthdate]</p>
                        <p class='label'>Address</p>
                        <p class='text-box'>[Address]</p>
                        <p class='label'>Contact Number</p>
                        <p class='text-box'>[Contact Number]</p>
                        <p class='label'>Years of Experience</p>
                        <p class='text-box'>[Years of Experience]</p>
                        <p class='label'>User Type</p>
                        <p class='text-box'>[User Type]</p>
                    </div>
                </div>

                <form class='info editable hidden' action='' method='POST'>
                    <div class='left'>
                        <label class='label'>Email Address</label>
                        <input class='text-box' readonly placeholder="[email address]">
                        <label class='label'>First Name</label>
                        <input class='text-box' placeholder="[First Name]">
                        <label class='label'>Last Name</label>
                        <input class='text-box' placeholder="[Last Name]">
                        <label class='label'>Sex</label>
                        <input class='text-box' placeholder="[Sex]">
                        <label class='label'>Height</label>
                        <input class='text-box' placeholder="[Height]">
                    </div>
                    <div class='right'>
                        <label class='label'>Birthdate</label>
                        <input class='text-box' placeholder="[Birthdate]">
                        <label class='label'>Address</label>
                        <input class='text-box' placeholder="[Address]">
                        <label class='label'>Contact Number</label>
                        <input class='text-box' placeholder="[Contact Number]">
                        <label class='label'>Years of Experience</label>
                        <input class='text-box' placeholder="[Years of Experience]">
                        <label class='label'>User Type</label>
                        <input class='text-box' placeholder="[User Type]">
                    </div>
                </form>
            </div>
        </div>
    </main>


</body>
</html>