<?php
    //Check first if the user is logged in
    include_once('../database/connect.php');

    if ($_SESSION['userType'] == 'Employer') {
        header('Location: ../employer/account_profile.php');
        exit();
    }
    if ($_SESSION['userType'] == 'Admin') {
        header('Location: ../admin/dashboard.php');
        exit();
    }
    
    $idUser = $_SESSION['idUser'];
    $worker = $workerObj -> getWorkersByConditions(null, null, null, null, null, null, null, null, $idUser);
    $worker = $worker[0];

    $contracts = $contractObj -> getContractByConditions(["contractStatus" => 'Pending', "idWorker" => $worker['idWorker']]);
    
    if (isset($contracts) && is_array($contracts)) {
        $contract = $contracts[0];
        $meetDetails = $meetingObj -> getMeetingByConditions(["contract_idContract" => $contract['idContract']]);
        $meetDetails = $meetDetails[0];

        $employer = $employerObj -> getEmployerById($contract['idEmployer']);
        $employerDetails = $userObj->getUserById($employer['idUser']);
    }

    // Handle switch status action
    if(isset($_POST['switch-status-btn'])) { // changed button name to switch-status-btn
        $newStatus = ($_POST['switch-status'] === 'available') ? 'Unavailable' : 'Available';
        
        // Update worker status in the database
        $workerObj -> updateWorker($worker['idWorker'], null, $newStatus, null, null, null, null, null, null, null);
        
        // Redirect back to the same page after status update
        header('Location: '.$_SERVER['PHP_SELF']);
        exit();
    }

    $contractPending = getLatestContractInfo($_SESSION['idUser']);
    $contract = getLatestContractByUserID($_SESSION['idUser']);

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
                
                <?php
                    $workerStatus = $worker['workerStatus'];
                ?>
                
                <!-- Available -->
                <div class='info <?php echo ($workerStatus != 'Unavailable' && $workerStatus != 'Available'  ? 'hidden' : ''); ?>'>
                    <div class='left'>
                        <p class='label'>Status</p>
                        <p class='text-box <?php echo(($workerStatus === 'Available') ? 'c-green' : 'c-red')?>'><?php echo $workerStatus; ?></p>
                        <form class='switch-status-form flex-column' action="" method='POST'>
                            <div>
                                <input id='switch-status' type='checkbox' name='switch-status' value='<?php echo(($workerStatus === 'Available') ? 'available' : 'unavailable');?>' >
                                <label for='switch-status'>Switch to <?php echo (($workerStatus === 'Available') ? 'Unavailable' : 'Available') ?></label>
                            </div>
                            <button class='orange-white-btn' type='submit' name='switch-status-btn'>Update Status</button>
                        </form>
                    </div>
                    <div class='right'>
                        <p class='f-italic c-red fs-medium'>
                            <?php echo ($workerStatus === 'Available' ? '(Please wait for an employer to hire you)' : '(Currently unavailable)')?>
                        </p>
                    </div>
                </div>

                <!-- Pending -->
                <div class='info align-start <?php echo ($workerStatus != 'Pending' ? 'hidden' : ''); ?>'>
     
                    <div class='left'>
                        <p class='label'>Status</p>
                        <p class='text-box c-yellow'>Pending</p>
                        <p class='label'>Date</p>
                        <p class='text-box'><?php echo $meetDetails['meetDate']?></p>
                        <p class='label'>Location</p>
                        <p class='text-box'><?php echo $meetDetails['locationAddress']?></p>
                        <p class='label <?php echo (isset($meetDetails['message']) ? '' : 'hidden')?>'>Message </p>
                        <p class='text-box <?php echo (isset($meetDetails['message']) ? '' : 'hidden')?>'><?php echo $meetDetails['message']?></p>
                    </div>
                    <div class='right'>
                        <p class='c-green f-italic'>(Please go to this location for the confirmation of contract from employer)</p>
                    </div>
                </div>

                <!-- Hired -->
                <div class='info <?php echo ($workerStatus != 'Hired' ? 'hidden' : ''); ?>'>
                    <div class='left'>
                        <p class='label'>Status</p>
                        <p class='text-box c-red'>Hired</p>
                        <p class='label'>Employer</p>
                        <p class='text-box'><?php echo $employerDetails['fname'] . " " . $employerDetails['lname']  ; ?></p>
                        <p class='label'>Salary Amount</p>
                        <p class='text-box'><?php echo $contract['salaryAmt']; ?></p>
                        <p class='label'>Date of Payment</p>
                        <p class='text-box'>Every month of day <?php echo getDayFromDate($contract['startDate'])?></p>
                        <p class='label'>Starting Date</p>
                        <p class='text-box'><?php echo $contract['startDate']; ?></p>
                        <p class='label'>End of Contract</p>
                        <p class='text-box'><?php echo $contract['endDate']; ?></p>
                    </div>
                    <div class='right flex-row flex-center'>
                        <div class='contract-container image-preview flex-center flex-column <?php echo (isset($employer['profilePic']) ? '' : 'hidden')?>'>
                            <img src='<?php echo getImageSrc($employer['profilePic']) ?>' alt='profile-img'>
                            <p class='f-italic fs-small t-align-center'>Employer Profile</p>
                        </div>
                        <div class='contract-container image-preview flex-center flex-column'>
                            <img src='<?php echo getImageSrc($contract['contractImg']) ?>' alt='contract-img'>
                            <p class='f-italic fs-small t-align-center'>Contract image</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>


</body>
</html>