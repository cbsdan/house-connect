<?php
    //Check first if the user is logged in
    include_once('../functions/user_authenticate.php');

    if ($_SESSION['userType'] == 'Worker') {
        header('Location: ../worker/application.php');
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

    <link rel="stylesheet" href="../styles/employer_styles/manage_worker.css">
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
                        <a href='./manage_worker.php' class='c-light fw-bold'>MANAGE WORKER</a>
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
                    <img class='user-profile' src='../img/worker-group-icon.png' placeholder='profile'>
                    <h3>Manage Worker</h3>   
                </div>
                <div class='info'>  
                    <label class='label fw-bold fs-extra-large'>List of Workers</label>
                    <select name='status' class='select-worker-type'>
                        <option value='all'>All</option>
                        <option value='hired'>Hired</option>
                        <option value='pending'>Pending</option>
                        <option value='canceled'>Canceled</option>
                        <option value='completed'>Completed</option>
                    </select>
                    <div class='table-container'>
                        <table class=''>
                            <thead>
                                <tr>
                                    <th>Contract ID</th>
                                    <th>Status</th>
                                    <th>Worker Profile</th>
                                    <th>Worker Name</th>
                                    <th>Worker Type</th>
                                    <th>Date Created</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>001</td>
                                    <td>Hired</td>
                                    <td class='t-align-center'><img src='../img/user-icon.png' alt='profile'></td>
                                    <td>Juan Dela Cruz</td>
                                    <td>Driver</td>
                                    <td>Jan. 01, 2024</td>
                                    <td class='t-align-center'><a class='open-detail-preview c-yellow details'>[Details]</a></td>
                                </tr>
                                <tr>
                                    <td>002</td>
                                    <td>Cancelled</td>
                                    <td class='t-align-center'><img src='../img/user-icon.png' alt='profile'></td>
                                    <td>Juan Dela Cruz</td>
                                    <td>Driver</td>
                                    <td>Jan. 01, 2024</td>
                                    <td class='t-align-center'><a class='open-detail-preview c-yellow details'>[Details]</a></td>
                                </tr>
                                <tr>
                                    <td>003</td>
                                    <td>Completed</td>
                                    <td class='t-align-center'><img src='../img/user-icon.png' alt='profile'></td>
                                    <td>Juan Dela Cruz</td>
                                    <td>Driver</td>
                                    <td>Jan. 01, 2024</td>
                                    <td class='t-align-center'><a class='open-detail-preview c-yellow details'>[Details]</a></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class='no-record-label hidden'>
                            <p>There are no found record!</p>
                        </div>
                    </div>
                    <div class='details-preview hidden'>
                        <div class="preview">
                            <div class="contract-info detail">
                                <div class='title'>
                                    <h3>Contract Info</h3>
                                </div>
                                <div class='info'>
                                    <div class="left">
                                        <div class='data'>
                                            <h4 class="label">Contract ID</h4>
                                            <p class="value">001</p>
                                        </div>
                                        <div class='data'>
                                            <h4 class="label">Status</h4>
                                            <p class="value">Hired</p>
                                        </div>
                                        <div class='data'>
                                            <h4 class="label">Date Created</h4>
                                            <p class="value">January 01, 2024</p>
                                        </div>
                                    </div>
                                    <div class="right">
                                        <div class='data'>
                                            <h4 class="label">Start Date</h4>
                                            <p class="value">January 01, 2024</p>
                                        </div>
                                        <div class='data'>
                                            <h4 class="label">End Date</h4>
                                            <p class="value">January 01, 2025</p>
                                        </div>
                                        <div class='data'>
                                            <h4 class="label">Salary Amount</h4>
                                            <p class="value">P20,000</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="meet-info detail">
                                <div class='title'>
                                    <h3>Interview Info</h3>
                                </div>
                                <div class="info">
                                    <div class="left">
                                        <div class='data'>
                                            <h4 class="label">Interview Data</h4>
                                            <p class="value">001</p>
                                        </div>
                                        <div class='data'>
                                            <h4 class="label">Platform</h4>
                                            <p class="value">Google Meet</p>
                                        </div>
                                    </div>
                                    <div class="right">
                                        <div class='data'>
                                            <h4 class="label">Link</h4>
                                            <p class="value">url-link</p>
                                        </div>
                                        <div class='data'>
                                            <h4 class="label">Employer Message</h4>
                                            <p class="value">Hello!</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="worker-info detail">
                                <div class='title'>
                                    <h3>Worker Info</h3>
                                </div>
                                <div class="info">
                                    <div class="left">
                                        <div class='data'>
                                            <h4 class="label">Profile</h4>
                                            <p class="value"><img src='../img/user-icon.png'></p>
                                        </div>
                                        <div class='data'>
                                            <h4 class="label">Name</h4>
                                            <p class="value">Juan Dela Cruz</p>
                                        </div>
                                        <div class='data'>
                                            <h4 class="label">Age</h4>
                                            <p class="value">25</p>
                                        </div>
                                    </div>
                                    <div class="right">
                                        <div class='data'>
                                            <h4 class="label">Worker Type</h4>
                                            <p class="value">Driver</p>
                                        </div>
                                        <div class='data'>
                                            <h4 class="label">Years of Experience</h4>
                                            <p class="value">1</p>
                                        </div>
                                        <div class='data'>
                                            <h4 class="label">Curriculum Vitae</h4>
                                            <p class="value">
                                                <div class='image-preview'>
                                                    <img src='../img/document-sample.jpg' alt='contract-img'>
                                                </div>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class='pay-worker-btn'><a href='./salary_payment.php' class='c-light'>Pay Worker Salary</a></button>

                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </main>


</body>
</html>