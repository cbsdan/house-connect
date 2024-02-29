<?php
    //Check first if the user is logged in
    include_once('../functions/user_authenticate.php');

    if ($_SESSION['userType'] == 'Employer') {
        header('Location: ../employer/account_profile.php');
        exit();
    }
    if ($_SESSION['userType'] == 'Admin') {
        header('Location: ../admin/dashboard.php');
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

    <link rel="stylesheet" href="../styles/worker_styles/current_employer.css">
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
                        <a href='./current_employer.php' class='c-light fw-bold'>CURRENT EMPLOYER</a>
                        <a href='./work_history.php' class='c-light'>WORK HISTORY</a>
                        <a href='./salary_payment.php' class='c-light'>SALARY PAYMENT</a>
                        <a href='./account_profile.php' class='c-light'>ACCOUNT PROFILE</a>
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
                    <img src='../img/groups-people.png'>
                    <h3>Current Employer</h3>   
                </div>
                
                <!-- Available -->
                <div class='info'>
                    <div class='left'>
                        <p class='label'>Status</p>
                        <p class='text-box c-green'>Available</p>
                        <form action="" method='POST'>
                            <input id='switch-status' type="checkbox" name='switch-status' value='available'>
                            <label for='switch-status'>Switch to 'Unavailable'</label>
                        </form>
                    </div>
                    <div class='right'>
                        <p class='f-italic c-red fs-medium'>(Please wait for an employer to hire you)</p>
                    </div>
                </div>

                <!-- Unavailable -->
                <div class='info hidden'>
                    <div class='left'>
                        <p class='label'>Status</p>
                        <p class='text-box c-red'>Unavailable</p>
                        <form action="" method='POST'>
                            <input id='switch-status' type="checkbox" name='switch-status' value='unavailable'>
                            <label for='switch-status'>Switch to 'Available'</label>
                        </form>
                    </div>
                    <div class='right'>
                    </div>
                </div>

                <!-- Pending -->
                <div class='info hidden'>
                    <div class='left'>
                        <p class='label'>Status</p>
                        <p class='text-box c-yellow'>Pending</p>
                        <p class='label'>Meet Platform</p>
                        <p class='text-box'>[meet platform]</p>
                        <p class='label'>Meet Link</p>
                        <p class='text-box'>[Meet Link]</p>
                        <p class='label'>Meet Date and Time</p>
                        <p class='text-box'>[Meet Date and Time]</p>
                        <p class='label'>Employer</p>
                        <p class='text-box'>[Employer]</p>
                    </div>
                    <div class='right'>
                    </div>
                </div>

                <!-- Hired -->
                <div class='info hidden'>
                    <div class='left'>
                        <p class='label'>Status</p>
                        <p class='text-box c-red'>Hired</p>
                        <p class='label'>Salary Amount</p>
                        <p class='text-box'>[salary amount]</p>
                        <p class='label'>Date of Payment</p>
                        <p class='text-box'>[Date of Payment]</p>
                        <p class='label'>Starting Date</p>
                        <p class='text-box'>[Starting Date]</p>
                        <p class='label'>End of Contract</p>
                        <p class='text-box'>[End of Contract]</p>
                    </div>
                    <div class='right'>
                        <div class='contract-container image-preview'>
                            <img src='../img/document-sample.jpg' alt='contract-img'>
                            <P class='f-italic fs-small t-align-center'>Contract image</P>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>


</body>
</html>