<?php
    //Check first if the user is logged in
    include_once('../functions/user_authenticate.php');
    include_once('../database/connect.php');

    if ($_SESSION['userType'] == 'Employer') {
        header('Location: ../employer/account_profile.php');
        exit();
    }
    if ($_SESSION['userType'] == 'Admin') {
        header('Location: ../admin/dashboard.php');
        exit();
    }

    // Handle switch status action
    if(isset($_POST['switch-status-btn'])) { // changed button name to switch-status-btn
        $newStatus = ($_POST['switch-status'] === 'available') ? 'Unavailable' : 'Available';
        
        // Update worker status in the database
        $updateStatusQuery = "UPDATE worker SET workerStatus = '$newStatus' WHERE idUser = " . $_SESSION['idUser'];
        $conn->query($updateStatusQuery);
        
        // Redirect back to the same page after status update
        header('Location: '.$_SERVER['PHP_SELF']);
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
                
                <?php
                    $workerStatusQuery = "SELECT workerStatus FROM worker WHERE idUser = " . $_SESSION['idUser'];
                    $workerStatusResult = $conn->query($workerStatusQuery);
                    $workerStatus = $workerStatusResult->fetch_assoc()['workerStatus'];
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
                <div class='info <?php echo ($workerStatus != 'Pending' ? 'hidden' : ''); ?>'>
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
                <div class='info <?php echo ($workerStatus != 'Hired' ? 'hidden' : ''); ?>'>
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