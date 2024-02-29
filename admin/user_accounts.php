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

    <link rel="stylesheet" href="../styles/admin_styles/user_accounts.css">
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
                        <a href='./dashboard.php' class='c-light'>DASHBOARD</a>
                        <a href='./contract_manager.php' class='c-light'>CONTRACT MANAGER</a>
                        <a href='./payment.php' class='c-light'>PAYMENT</a>
                        <a href='./user_accounts.php' class='c-light fw-bold'>USER ACCOUNTS</a>
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
                    <div class="left">
                        <img class='user-profile' src='../img/groups-people.png' placeholder='groups-icon'>
                        <h3>User Accounts</h3>  
                    </div>
                    <div class="right">
                        <p class='nav display-users fw-bold'>
                            Display Users
                        </p>
                        <p class='nav verify-users'>
                            Verify Users
                        </p>
                    </div>
                </div>
                <div class='info'>
                    <div class='table-result user-accounts'>
                        <table>
                            <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>Profile</th>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Sex</th>
                                    <th>Email</th>
                                    <th>User Type</th>
                                    <th>View</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>001</td>
                                    <td><img class='t-align-center' src='../img/user-icon.png' alt='profile'></td>
                                    <td>Juan Dela Cruz</td>
                                    <td>25</td>
                                    <td>Male</td>
                                    <td>example_account@gmail.com</td>
                                    <td class='t-align-center'>Worker</td>
                                    <td class='t-align-center c-yellow'>[View]</td>
                                </tr>
                                <tr>
                                    <td>002</td>
                                    <td><img class='t-align-center' src='../img/user-icon.png' alt='profile'></td>
                                    <td>Juan Dela Cruz</td>
                                    <td>25</td>
                                    <td>Male</td>
                                    <td>example_account@gmail.com</td>
                                    <td class='t-align-center'>Employer</td>
                                    <td class='t-align-center c-yellow'>[View]</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class='table-result verify-users hidden'>
                        <table>
                            <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>Profile</th>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Sex</th>
                                    <th>Email</th>
                                    <th>User Type</th>
                                    <th>View</th>
                                    <th>Approve</th>
                                    <th>Decline</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>001</td>
                                    <td><img class='t-align-center' src='../img/user-icon.png' alt='profile'></td>
                                    <td>Juan Dela Cruz</td>
                                    <td>25</td>
                                    <td>Male</td>
                                    <td>example_account@gmail.com</td>
                                    <td class='t-align-center'>Worker</td>
                                    <td class='t-align-center c-yellow'>[View]</td>
                                    <td class='t-align-center'><button class='green-white-btn'>Approve</button></td>
                                    <td class='t-align-center'><button class='red-white-btn'>Decline</button></td>
                                </tr>
                                <tr>
                                    <td>002</td>
                                    <td><img class='t-align-center' src='../img/user-icon.png' alt='profile'></td>
                                    <td>Juan Dela Cruz</td>
                                    <td>25</td>
                                    <td>Male</td>
                                    <td>example_account@gmail.com</td>
                                    <td class='t-align-center'>Employer</td>
                                    <td class='t-align-center c-yellow'>[View]</td>
                                    <td class='t-align-center'><button class='green-white-btn'>Approve</button></td>
                                    <td class='t-align-center'><button class='red-white-btn'>Decline</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class='no-record-label hidden'>
                        <p>There are no found record!</p>
                    </div>

                    <div class='detail-preview'>
                        <div class="preview">
                            <div class="detail">
                                <div class="title">
                                    <h3>Personal Information</h3>
                                </div>
                                <div class="info">
                                    <div class="left">
                                        <div class="data">
                                            <h4 class="label">User ID</h4>
                                            <p class="text-box">001</p>
                                        </div>
                                        <div class="data">
                                            <h4 class="label">Profile</h4>
                                            <p class="text-box t-align-center"><img src='../img/user-icon.png'></p>
                                        </div>
                                        <div class="data">
                                            <h4 class="label">Email</h4>
                                            <input class="text-box" type='email' name='email' value='example_account@gmail.com'>
                                        </div>
                                        <div class="data">
                                            <h4 class="label">Password</h4>
                                            <input class="text-box" name='password' value='example_account@gmail.com'>
                                        </div>
                                        <div class="data">
                                            <h4 class="label">First Name</h4>
                                            <input class="text-box" type='text' name='fname' value='Daniel'>
                                        </div>
                                        <div class="data">
                                            <h4 class="label">Last Name</h4>
                                            <input class="text-box" type='text' name='lname' value='Cabasa'>
                                        </div>
                                        <div class="data">
                                            <h4 class="label">Sex</h4>
                                            <select class="text-box" type='text' name='sex' value='Male'>
                                                <option value="Male">Male</option>
                                                <option value="Male">Female</option>
                                            </select>
                                        </div>
                                        <div class="data">
                                            <h4 class="label">Birthdate</h4>
                                            <input class="text-box" type='date' name='birthdate'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>