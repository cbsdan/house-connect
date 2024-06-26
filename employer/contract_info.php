<?php
    // Check first if the user is logged in
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

    if ($_SERVER["REQUEST_METHOD"] == "POST" || isset($_SESSION['idContract'])) {
        if (isset($_POST['idContract'])) {
            $idContract = $_POST['idContract'];
            $workerIdUser = $_POST['workerIdUser'];
        } else {
            $idContract = $_SESSION['idContract'];
            $workerIdUser = $_SESSION['workerIdUser'];
            $_SESSION['idContract'] = null;
            $_SESSION['workerIdUser'] = null;
        }
        // Fetch meeting details using the function
        $meetingDetails = getMeetingDetailsByIdContract($idContract);

        //Contract Info
        $contractInfo = getContractList($idContract);
        if (isset($contractInfo[0])) {
            $contractInfo = $contractInfo[0];
        }
    } else {
        header('Location: ./manage_worker.php');
        exit();
    }
    $idEmployer = getEmployerOrWorkerID($_SESSION['idUser']);
    // Fetch user information
    $userInfo = fetchUserInformation($conn, $_SESSION['idUser']);

    // Fetch all user information
    $allUserInfo = fetchAllUserInformation($workerIdUser, 'Worker');
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

    <style>
        .information {
            align-items: start;
        }
    </style>
</head>
<body>
    <header class='logged-in'>
        <div class="content">
            <button class='orange-white-btn'><a href='./manage_worker.php' class='c-light'>Back to Manage Worker</a></button>
        </div>
    </header>

    <main class='employer'>
        <div class='container application'>
            <div class='content'>
                <div class='info contract'>  
                    <div class="contract-info">
                    <?php
                    // Check if the contract status is 'Pending'
                    if ($contractInfo['contractStatus'] != 'Pending') {
                        // Display the contract information
                    ?>
                        <div class='title'>
                            <h3 class='t-align-center w-100'>Contract Info</h3>
                        </div>
                        <div class='information w-100 flex-1 flex-row align-start'>
                            <div class="left">
                                <div class='data'>
                                    <h4 class="label">Contract ID</h4>
                                    <p class="value"><?php echo $contractInfo['idContract']?></p>
                                </div>
                                <div class='data'>
                                    <h4 class="label">Status</h4>
                                    <p class="value"><?php echo $contractInfo['contractStatus']?></p>
                                </div>
                                <div class='data'>
                                    <h4 class="label">Date Created</h4>
                                    <p class="value"><?php echo $contractInfo['date_created']?></p>
                                </div>
                                <div class='data'>
                                    <h4 class="label">Contract Img</h4>
                                    <div class="image-preview"><img src='<?php echo (isset($contractInfo['contractImg']) ? getImageSrc($contractInfo['contractImg']) : '')?>'></div>
                                </div>
                            </div>
                            <div class="right">
                                <div class='data'>
                                    <h4 class="label">Worker Deployment Location</h4>
                                    <p class="value"><?php echo $contractInfo['deploymentLocation']?></p>
                                </div>
                                <div class='data'>
                                    <h4 class="label">Start Date</h4>
                                    <p class="value"><?php echo $contractInfo['startDate']?></p>
                                </div>
                                <div class='data'>
                                    <h4 class="label">End Date</h4>
                                    <p class="value"><?php echo $contractInfo['endDate']?></p>
                                </div>
                                <div class='data'>
                                    <h4 class="label">Salary Amount</h4>
                                    <p class="value"><?php echo $contractInfo['salaryAmt']?></p>
                                </div>
                                <div class='data'>
                                    <h4 class="label">Salary Payment Date</h4>
                                    <p class='value'>Every month of day <?php echo getDayFromDate($contractInfo['startDate'])?></p>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <div class="meet-info detail">
                    <div class='title'>
                        <h3 class='t-align-center w-100'>Confirmation of Contract</h3>
                    </div>
                    <div class="information w-100 flex-1">
                        <div class="left">
                            <div class='data'>
                                <h4 class="label">Confirmation Date</h4>
                                <p class="value"><?php echo isset($meetingDetails['meetDate']) ? $meetingDetails['meetDate'] : ''; ?></p>
                            </div>
                            <div class='data'>
                                <h4 class="label">Confirmation Location</h4>
                                <p class="value"><?php echo isset($meetingDetails['locationAddress']) ? $meetingDetails['locationAddress'] : ''; ?></p>
                            </div>
                        </div>
                        <div class="right">
                            <div class='data'>
                                <h4 class="label">Message from agency</h4>
                                <p class="value"><?php echo isset($meetingDetails['message']) ? $meetingDetails['message'] : ''; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
       
                <div class="worker-info detail">
                    <div class='title'>
                        <h3 class='t-align-center w-100'>Worker Info</h3>
                    </div>
                    <div class="information w-100 flex-1">
                        <div class="left">
                            <div class='data'>
                                <h4 class="label">Name</h4>
                                <p class="value"><?php echo $allUserInfo['fname'] . " " . $allUserInfo['lname']; ?></p>
                            </div>
                            <div class='data'>
                                <h4 class="label">Email</h4>
                                <p class="value"><?php echo $allUserInfo['email']; ?></p>
                            </div>
                            <div class='data <?php echo (isset($allUserInfo['contactNo']) ? '' : 'hidden')?>'>
                                <h4 class="label">Contact Number</h4>
                                <p class="value"><?php echo $allUserInfo['contactNo']; ?></p>
                            </div>
                            <div class='data'>
                                <h4 class="label">Profile</h4>
                                <div class=" image-preview">
                                    <img src='<?php echo $allUserInfo['profilePic']?>' alt='profile-pic'>
                                </div>
                            </div>
                        </div>
                        <div class="right">
                            <div class='data'>
                                <h4 class="label">Worker Type</h4>
                                <p class="value"><?php echo $allUserInfo['workerType']; ?></p>
                            </div>
                            <div class='data'>
                                <h4 class="label">Age</h4>
                                <p class="value"><?php echo calculateAge($allUserInfo['birthdate']); ?></p>
                            </div>
                            <div class='data'>
                                <h4 class="label">Years of Experience</h4>
                                <p class='value'><?php echo $allUserInfo['yearsOfExperience'] ?></p>
                            </div>
                            <div class='data'>
                                <h4 class="label">Curriculum Vitae</h4>
                                <div class='image-preview'>
                                    <img src='<?php echo $allUserInfo['curriculumVitae'] ?>' alt='curriculumVitae-img'>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class='contract-info-buttons'>
                    <form action='./salary_payment.php' method='POST' class='<?php echo ($contractInfo['contractStatus'] != 'Hired' ? 'hidden' : '')?>'>
                        <input type='hidden' name='idEmployer' value='<?php echo $idEmployer?>'>
                        <input type='hidden' name='idWorker' value='<?php echo $allUserInfo['idWorker'] ?>'>
                        <input type='hidden' name='workerIdUser' value='<?php echo $workerIdUser ?>'>
                        <input type='hidden' name='idContract' value='<?php echo $idContract?>'>
                        <input type='hidden' name='workerName' value='<?php echo $allUserInfo['fname'] . " " . $allUserInfo['lname']; ?>'>
                        <button type='submit' class='pay-worker-btn green-white-btn '>Pay Worker Salary</button>
                    </form>
                    <form action='./contract_receipt.php' method='POST' class='<?php echo ($contractInfo['contractStatus'] != 'Hired' ? 'hidden' : '')?>' target="_blank">
                        <input type='hidden' name='idEmployer' value='<?php echo $idEmployer?>'>
                        <input type='hidden' name='idWorker' value='<?php echo $allUserInfo['idWorker'] ?>'>
                        <input type='hidden' name='workerIdUser' value='<?php echo $workerIdUser ?>'>
                        <input type='hidden' name='contractId' value='<?php echo $idContract?>'>
                        <input type='hidden' name='workerName' value='<?php echo $allUserInfo['fname'] . " " . $allUserInfo['lname']; ?>'>
                        <button type='submit' class='pay-worker-btn orange-white-btn '>Print Contract Details</button>
                    </form>
                    <form action='../database/update_contract_status.php' method='POST' class='<?php echo ((strtotime($contractInfo['endDate']) <= strtotime(date('Y-m-d'))) && ($contractInfo['contractStatus'] == 'Hired') ? '' : 'hidden') ?>'>
                        <input type='hidden' name='idContract' value='<?php echo $idContract?>'>
                        <input type='hidden' name='idWorker' value='<?php echo $allUserInfo['idWorker'] ?>'>
                        <input type='hidden' name='contractStatus' value='Completed'>
                        <button type='submit' class='pay-worker-btn green-white-btn'>Completed</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>