<?php
// Check first if the user is logged in
include_once('../functions/user_authenticate.php');
include_once('../database/connect.php');
require_once('../Classes/Contract.php');
require_once('../Classes/Meeting.php');

if ($_SESSION['userType'] == 'Worker') {
    header('Location: ../worker/application.php');
    exit();
}
if ($_SESSION['userType'] == 'Employer') {
    header('Location: ../employer/account_profile.php');
    exit();
};
$workerResult = fetchWorkerInformation();
$employeResult = fetchEmployerInformation();

if (isset($_POST['idWorker']) && isset($_POST['idEmployer'])) {
    if (isset($_POST['idWorker'])) {
        $idWorker = $_POST['idWorker'];
    } 
    if (isset($_POST['idEmployer'])) {
        $idEmployer = $_POST['idEmployer'];
    } 
    if (isset($_POST['contractStatus'])) {
        $contractStatus = $_POST['contractStatus'];
    } else {
        $contractStatus = null;
    }
    if (isset($_POST['startDate'])) {
        $startDate = $_POST['startDate'];
    } else {
        $startDate = null;
    }
    if (isset($_POST['endDate'])) {
        $endDate = $_POST['endDate'];
    } else {
        $endDate = null;
    }
    if (isset($_POST['salaryAmt'])) {
        $salaryAmt = $_POST['salaryAmt'];
    } else {
        $salaryAmt = null;
    }
    //Meet Info
    if (isset($_POST['meetDate'])) {
        $meetDate = $_POST['meetDate'];
    } else {
        $meetDate = null;
    }
    if (isset($_POST['platform'])) {
        $platform = $_POST['platform'];
    } else {
        $platform = null;
    }
    if (isset($_POST['link'])) {
        $link = $_POST['link'];
    } else {
        $link = null;
    }
    if (isset($_POST['employerMessage'])) {
        $employerMessage = $_POST['employerMessage'];
    } else {
        $employerMessage = null;
    }

    if (isset($_FILES['contractImg']) && is_uploaded_file($_FILES['contractImg']['tmp_name'])) {
        $contractImg = addslashes(file_get_contents($_FILES['contractImg']['tmp_name']));
    } else {
        $contractImg = null;
    }

    $contractObj = new Contract($conn);
    $meetingObj = new Meeting($conn);

    $idContract = $contractObj->createContract($idWorker, $idEmployer, $contractStatus, $startDate, $endDate, $salaryAmt, $contractImg);
    
    if ($idContract != false) {
        updateWorkerStatus($idWorker, $contractStatus);
        $meetingObj->createMeeting($idContract, $meetDate, $platform, $link, $employerMessage);
        header('Location: ./contract_manager.php');
        exit();
    } else {
        header('Location: ./contract_manager.php');
        echo "<script>alert('Failed to insert a new contract')</script>";
    }
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

    <link rel="stylesheet" href="../styles/admin_Styles/create-new-contract.css">
    <link rel="stylesheet" href="../styles/admin_styles/default.css">

    <!-- JavaScript -->
    <script src='../scripts/worker.js' defer></script>

</head>
<body>
    <header class='logged-in'>
        <div class="content">
            <button class='orange-white-btn'><a href='./contract_manager.php' class='c-light'>Back to Contract Manager</a></button>
        </div>
    </header>

    <main class='admin'>
        <div class='container application'>
            <div class='content'>
                <div class='title'>
                    <img class='' src='../img/documents-icon.png' placeholder='documents-icon'>
                    <h3>Create a new Contract</h3>  
                </div>
                <form class="info" action='' method='POST' enctype="multipart/form-data"> 
                    <div class="left">
                        <label class="label">Select a Worker (Required)</label>
                        <select class='idWorker' name='idWorker' required>
                            <?php
                                foreach($workerResult as $worker) {
                                    echo "<option value='".$worker['idWorker']."'>".$worker['idWorker'] . " - " . $worker['fname'] . " " . $worker['lname'] . " (" . $worker['workerType'] . ")</option>";
                                };
                            ?>
                        </select>
                        
                        <label class="label">Employer ID (Required)</label>
                        <select class='idEmployer' name='idEmployer' required>
                            <?php
                                foreach($employeResult as $employer) {
                                    echo "<option value='".$employer['idEmployer']."'>".$employer['idEmployer'] . " - " . $employer['fname'] . " " . $employer['lname'] . "</option>";
                                };
                            ?>
                        </select>
                        
                        <label class="label">Contract Status (Required)</label>
                        <select name='contractStatus' required>
                            <option value='Pending'>Pending</option>
                            <option value='Hired'>Hired</option>
                        </select>
                    </div>    
                    <div class="right">
                        <label class="label">Start Date (Optional)</label>
                        <input class="text-box" name='startDate' type='date'>
                        
                        <label class="label">End Date (Optional)</label>
                        <input class="text-box" name='endDate' type='date'>
    
                        <label class="label">Salary Amount ₱ (Optional)</label>
                        <input class="text-box" type='number' name='salaryAmt' min=15000>
                        
                        <label class="label">Contract Image (Optional)</label>
                        <input class='text-box m-b-2' type="file" name="contractImg" accept="image/png, image/jpeg, image/jpg">
                    </div>
                    
                    
                    <div class='title'>
                        <img class='' src='../img/documents-icon.png' placeholder='documents-icon'>
                        <h3>Input meeting information</h3>  
                    </div>

                    <label class="label">Meet Date (Required)</label>
                    <input class="text-box" type='date' name='meetDate' required>

                    <label class="label">Platform (Required)</label>
                    <input class="text-box" type='text' name='platform' placeholder="Enter platform">
                    
                    <label class="label">Link (Required)</label>
                    <input class="text-box" type='text' name='link' placeholder="Enter Link" >
                    
                    <label class="label">Employer Message (Required)</label>
                    <textarea class="text-box" name='employerMessage' placeholder="type here"></textarea>

                    <div class='m-l-auto buttons'>
                        <button class="orange-white-btn" type="submit" name="submit">Create new contract</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>