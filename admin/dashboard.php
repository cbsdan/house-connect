<?php
    //Check first if the user is logged in
    include_once('../functions/user_authenticate.php');

    if ($_SESSION['userType'] == 'Worker') {
        header('Location: ../worker/application.php');
        exit();
    }
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

    <link rel="stylesheet" href="../styles/admin_styles/dashboard.css">
    <link rel="stylesheet" href="../styles/admin_styles/default.css">

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
                    <nav>
                        <a href='./dashboard.php' class='c-light fw-bold'>DASHBOARD</a>
                        <a href='./contract_manager.php' class='c-light'>CONTRACT MANAGER</a>
                        <a href='./payment.php' class='c-light'>PAYMENT</a>
                        <a href='./user_accounts.php' class='c-light'>USER ACCOUNTS</a>
                    </nav>
                    <nav>
                        <a href='../logout.php' class='c-light'>LOG OUT</a>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <main class='admin'>
        <div class='container application'>
            <div class='content'>
                <div class='title'>
                    <img class='user-profile' src='../img/dashboard-icon.png' placeholder='dashboard-icon'>
                    <h3>Dashboard</h3>  
                </div>
                <div class='info'>
                    <div class="details">
                        <h3 class="title">Summary Report of Revenue</h3>
                        <div class="data">
                            <p class="label">Daily</p>
                            <p class="value">P10,000</p>
                        </div>
                        <div class="data">
                            <p class="label">Weekly</p>
                            <p class="value">P10,000</p>
                        </div>
                        <div class="data">
                            <p class="label">Monthly</p>
                            <p class="value">P10,000</p>
                        </div>
                        <div class="data">
                            <p class="label">Yearly</p>
                            <p class="value">P10,000</p>
                        </div>
                    </div>
                    <div class="details">
                        <h3 class="title">Workers</h3>
                        <div class="data">
                            <p class="label">Total</p>
                            <p class="value">20</p>
                        </div>
                        <div class="data">
                            <p class="label">Nanny</p>
                            <p class="value">1</p>
                        </div>
                        <div class="data">
                            <p class="label">Cook</p>
                            <p class="value">5</p>
                        </div>
                        <div class="data">
                            <p class="label">Maid</p>
                            <p class="value">3</p>
                        </div>
                        <div class="data">
                            <p class="label">Gardener</p>
                            <p class="value">5</p>
                        </div>
                        <div class="data">
                            <p class="label">Driver</p>
                            <p class="value">6</p>
                        </div>
                    </div>
                    <div class="details">
                        <h3 class="title">Contracts</h3>
                        <div class="data">
                            <p class="label">Total</p>
                            <p class="value">55</p>
                        </div>
                        <div class="data">
                            <p class="label">Active</p>
                            <p class="value">15</p>
                        </div>
                        <div class="data">
                            <p class="label">Pending</p>
                            <p class="value">10</p>
                        </div>
                        <div class="data">
                            <p class="label">Completed</p>
                            <p class="value">20</p>
                        </div>
                        <div class="data">
                            <p class="label">Canceled</p>
                            <p class="value">10</p>
                        </div>
                    </div>
                    <div class="details">
                        <h3 class="title">Users</h3>
                        <div class="data">
                            <p class="label">Total</p>
                            <p class="value">200</p>
                        </div>
                        <div class="data">
                            <p class="label">Verified Workers</p>
                            <p class="value">50</p>
                        </div>
                        <div class="data">
                            <p class="label">Non-verified Users</p>
                            <p class="value">50</p>
                        </div>
                        <div class="data">
                            <p class="label">Verified Employer</p>
                            <p class="value">50</p>
                        </div>
                        <div class="data">
                            <p class="label">Non-verified Employer</p>
                            <p class="value">50</p>
                        </div>
                    </div>
                    <!-- <div class="details">
                        <h3 class="title">Salary Payment</h3>
                        <div class="data">
                            <p class="label">Daily</p>
                            <p class="value">P10,000</p>
                        </div>
                        <div class="data">
                            <p class="label">Weekly</p>
                            <p class="value">P10,000</p>
                        </div>
                        <div class="data">
                            <p class="label">Monthly</p>
                            <p class="value">P10,000</p>
                        </div>
                        <div class="data">
                            <p class="label">Yearly</p>
                            <p class="value">P10,000</p>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </main>
</body>
</html>