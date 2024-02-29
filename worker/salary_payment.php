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
                        <a href='./salary_payment.php' class='c-light fw-bold'>SALARY PAYMENT</a>
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
                    <img src='../img/wallet-icon.png'>
                    <h3>Salary Payment</h3>   
                </div>
                <div class='info'>
                    <table class=''>
                        <thead>
                            <tr>
                                <th>Paypal Account</th>
                                <th>Status</th>
                                <th>Amt. Paid by Employer</th>
                                <th>End of Contract</th>
                                <th>Salary Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>example_account@gmail.com</td>
                                <td>Successful</td>
                                <td>P20,000</td>
                                <td>Jan 01, 2024</td>
                                <td>P18,000</td>
                            </tr>
                            <tr>
                                <td>example_account@gmail.com</td>
                                <td>Successful</td>
                                <td>P20,000</td>
                                <td>Jan 01, 2024</td>
                                <td>P18,000</td>
                            </tr>
                            <tr>
                                <td>example_account@gmail.com</td>
                                <td>Successful</td>
                                <td>P20,000</td>
                                <td>Jan 01, 2024</td>
                                <td>P18,000</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- If there is no previous work -->
                    <div class='no-record-label hidden'>
                        <p>You have no previous salary payment in the system!</p>
                    </div>
                </div>
            </div>
        </div>
    </main>


</body>
</html>