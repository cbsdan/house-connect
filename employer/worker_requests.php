<?php
    //Check first if the user is logged in
    include_once('../database/connect.php');

    if ($_SESSION['userType'] == 'Worker') {
        header('Location: ../worker/application.php');
        exit();
    }
    if ($_SESSION['userType'] == 'Admin') {
        header('Location: ../admin/dashboard.php');
        exit();
    }

    $userData = fetchEmployerData($_SESSION['idUser']);
    if ($userData['verifyStatus'] == 'Not Verified') {
        header('Location: ./account_profile.php');
        exit();
    }
    
    $employer = $employerObj -> getEmployerByConditions(["idUser" => $_SESSION['idUser']]);
    // var_dump($employer);
    // exit();
    if ($employer != false) {
        $employer = $employer[0];
        $employerRequests = $employerRequestsObj -> getEmployerRequestByConditions(['employer_idEmployer' => $employer['idEmployer']]);
    }

    if (isset($_POST['cancel-request'])) {
        $employerRequestsObj -> updateEmployerRequest($_POST['idEmployerPreference'], ["status" => 'Canceled']);
        header('Location: ./worker_requests.php');
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

    <link rel="stylesheet" href="../styles/employer_styles/default.css">
    <link rel="stylesheet" href="../styles/employer_styles/find_a_worker.css">

    <!-- JavaScript -->
    <script src='../scripts/worker.js' defer></script>

    <style>
        main.employer .content .title {
            justify-content: start;
        } 

    </style>
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
                        <a href='./request_a_worker.php' class='c-light'>REQUEST A WORKER</a>
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
                <img class='user-profile' src='../img/documents-icon.png' placeholder='profile'>
                <h3>My Requests</h3>   
            </div>
            <div class="info flex-wrap">
                <div class='no-record-label flex-1 <?php echo ($employerRequests != false ? 'hidden' : '');?>'>
                    <p>There are no found records!</p>
                </div>
                <table <?php echo ($employerRequests == false ? 'hidden' : '');?>>
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Worker Type</th>
                            <th>Preferred Worker</th>
                            <th>Additional Message</th>
                            <th>Status</th>
                            <th>Date Requested</th>
                            <th>Contract ID</th>
                            <th>Cancel</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if ($employerRequests != false) {
                            foreach($employerRequests as $employerRequest) {
                                if (isset($employerRequest['contract_idContract'])) {
                                    $contract = $contractObj -> getContractById($employerRequest['contract_idContract']);
                                    $workerDetails = $workerObj -> getWorkerById($contract['idWorker']);
                                } else {
                                    $contract = null;
                                }
                                echo "<tr>
                                    <td class='t-align-center'>" . $employerRequest['idEmployerPreference'] . "</td>
                                    <td>" . $employerRequest['workerType'] . "</td>
                                    <td>" . ($employerRequest['age'] != '' ? '<p>Preferred Age: ' . $employerRequest['age'] . '</p>' : '<p>No preferred Age</p>') .
                                            ($employerRequest['sex'] != '' ? '<p>Preferred Sex: ' . $employerRequest['sex'] . '</p>' : '<p>No preferred Sex</p>') .
                                            ($employerRequest['height'] != '' ? '<p>Preferred Height: ' . $employerRequest['height'] . '</p>' : '<p>No preferred Height</p>') .
                                            ($employerRequest['yrsOfExperience'] != '' ? '<p>Preferred Years of Experience: ' . $employerRequest['yrsOfExperience'] . '</p>' : '<p>No preferred Years of Experience</p>') . "</td>
                                    <td>" . ($employerRequest['additionalMessage'] != '' ? $employerRequest['additionalMessage'] : 'No additional message requests') . "</td>
                                    <td>" . $employerRequest['status'] . "</td>
                                    <td>" . $employerRequest['date_requested'] . "</td>
                                    <td class='t-align-center'>
                                        <form action='./contract_info.php' method='POST' class='" . ($employerRequest['status'] == 'Pending' ? 'hidden' : '') . "'>
                                            <input type='hidden' name='idContract' value='" . ($contract['idContract'] ?? '') . "'>
                                            <input type='hidden' name='workerIdUser' value='" . ($workerDetails['idUser'] ?? '') . "'>
                                            <button class='c-blue t-underline' name='submit' value='1'>" . ($contract['idContract'] ?? '') . "</button>
                                        </form>
                                        <p class='" . ($employerRequest['status'] != 'Pending' ? 'hidden' : '') . "'>N/A</p>
                                    </td>
                                    <td class='t-align-center'>
                                        <form action='' method='POST' class='" . ($employerRequest['status'] != 'Pending' ? 'hidden' : '') . "'>
                                            <input type='hidden' name='idEmployerPreference' value='" . $employerRequest['idEmployerPreference'] . "'>
                                            <button class='red-white-btn' name='cancel-request' value='1'>Cancel</button>
                                        </form>
                                        <p class='" . ($employerRequest['status'] != 'Pending' ? '' : 'hidden') . "'>N/A</p>
                                    </td>
                                </tr>";

                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>