<?php
    //Check first if the user is logged in
    include_once('../functions/user_authenticate.php');
    include_once('../database/connect.php');

    if ($_SESSION['userType'] == 'Worker') {
        header('Location: ../worker/application.php');
        exit();
    }
    if ($_SESSION['userType'] == 'Admin') {
        header('Location: ../admin/dashboard.php');
        exit();
    }

    $myIdUser = $_SESSION['idUser'];
    $myIdEmployer = getEmployerOrWorkerID($myIdUser);

    $contracts = getContractListByEmployerID($myIdEmployer);

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
                        <table class='<?php echo (!isset($contracts) ? 'hidden' : '');?>'>
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
                                <?php 
                                    if (isset($contracts)) {
                                        foreach($contracts as $contract) {
                                            echo "
                                                <tr>
                                                <td class='t-align-center'>" . $contract['idContract'] . "</td>
                                                    <td>" . $contract['contractStatus'] . "</td>
                                                    <td class='t-align-center'><img src='../img/user-icon.png' alt='profile'></td>
                                                    <td>" . $contract['workerFname'] . " " . $contract['workerLname'] . "</td>
                                                    <td class='t-align-center'>" . $contract['workerType'] . "</td>
                                                    <td>" . $contract['date_created'] . "</td>
                                                    <td class='t-align-center'>
                                                        <form action='./contract_info.php' method='POST' class='open-detail-preview '>
                                                            <input type='hidden' name='idContract' value='" . $contract['idContract'] . "'>
                                                            <button type='submit' class='c-yellow details'>[Details]</button>
                                                        </form>
                                                    </td>  
                                                </tr>
                                            ";
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                        <div class='no-record-label <?php echo (isset($contracts) ? 'hidden' : '');?>'>
                            <p>There are no found record!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>


</body>
</html>