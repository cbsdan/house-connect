<?php
    //Check first if the user is logged in
    include_once('../database/connect.php');

    if ($_SESSION['userType'] == 'Worker') {
        header('Location: ../worker/application.php');
        exit();
    }
    if ($_SESSION['userType'] == 'Employer') {
        header('Location: ../employer/account_profile.php');
        exit();
    }

    
    $employerRequests = $employerRequestsObj -> getAllEmployerRequests();

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

    <link rel="stylesheet" href="../styles/admin_styles/default.css">
    <link rel="stylesheet" href="../styles/admin_styles/employer_requests.css">

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
                        <a href='./employer_requests.php' class='c-light fw-bold'>EMPLOYER REQUESTS</a>
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
                    <img src='../img/documents-icon.png' >
                    <h3 class='w-100 fs-large'>Employer Requests</h3>
                </div>
                <div class="info flex-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Request ID</th>
                                <th>Employer Name</th>
                                <th>Worker Type</th>
                                <th>Preferred Worker</th>
                                <th>Additional Message</th>
                                <th>Status</th>
                                <th>Date Requested</th>
                                <th>Contract ID</th>
                                <th>Deploy Worker</th>
                                <th>Cancel</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if ($employerRequests != false) {
                                foreach($employerRequests as $employerRequest) {
                                    $employer = $employerObj -> getEmployerById($employerRequest['employer_idEmployer']);
                                    $employerDetails = $userObj -> getUserById($employer['idUser']);

                                    echo "<tr>
                                            <td class='t-align-center'>". $employerRequest['idEmployerPreference'] ."</td>
                                            <td>". $employerDetails['fname'] . " " . $employerDetails['lname']. "</td>
                                            <td>". $employerRequest['workerType'] ."</td>
                                            <td>". ($employerRequest['age'] != '' ? '<p>Preferred Age: ' . $employerRequest['age'] . '</p>': '<p>No preferred Age</p>').
                                                    ($employerRequest['sex'] != '' ? '<p>Preferred Sex: ' . $employerRequest['sex'] . '</p>': '<p>No preferred Sex</p>').
                                                    ($employerRequest['height'] != '' ? '<p>Preferred Height: ' . $employerRequest['height'] . '</p>': '<p>No preferred Height</p>').
                                                    ($employerRequest['yrsOfExperience'] != '' ? '<p>Preferred Years of Experience: ' . $employerRequest['yrsOfExperience'] . '</p>': '<p>No preferred Years of Experience</p>')."</td>
                                            <td>". ($employerRequest['additionalMessage'] != '' ? $employerRequest['additionalMessage'] : 'No additional message requests') ."</td>
                                            <td>". $employerRequest['status'] ."</td>
                                            <td>". $employerRequest['date_requested'] ."</td>
                                            <td class='t-align-center'>". (isset($employerRequest['contract_idContract']) ? $employerRequest['contract_idContract'] : 'N/A') ."</td>
                                            <td class='t-align-center'>
                                                <form action='./deploy_worker.php' method='POST' class='". ($employerRequest['status'] == 'Pending' ? '' : 'hidden') . "'>
                                                    <input type='hidden' name='idEmployerPreference' value='".$employerRequest['idEmployerPreference']."'>
                                                    <button class='green-white-btn' name='respond-request' value='1'>Deploy Worker</button>
                                                </form>
                                                <form action='./contract_info.php' method='POST' class='". ($employerRequest['status'] == 'Successful' ? '' : 'hidden') . "'>
                                                    <input type='hidden' name='idContract' value='".$employerRequest['contract_idContract']."'>
                                                    <button class='orange-white-btn' name='view-contract' value='1'>View Contract</button>
                                                </form>
                                                <p class='".($employerRequest['status'] == 'Canceled' ? '' : 'hidden')."'>N/A</p>
                                            </td>
                                            <td class='t-align-center'>
                                                <form action='' method='POST' class='". ($employerRequest['status'] != 'Pending' ? 'hidden' : '') . "'>
                                                    <input type='hidden' name='idEmployerPreference' value='".$employerRequest['idEmployerPreference']."'>
                                                    <button class='red-white-btn' name='cancel-request' value='1'>Cancel</button>
                                                </form>
                                                <p class='".($employerRequest['status'] == 'Pending' ? 'hidden' : '')."'>N/A</p>
                                            </td>
                                        </tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>