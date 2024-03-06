<?php
    //Check first if the user is logged in
    include_once('../functions/user_authenticate.php');
    include_once('../database/connect.php');

    if ($_SESSION['userType'] == 'Worker') {
        header('Location: ../worker/application.php');
        exit();
    }
    if ($_SESSION['userType'] == 'Employer') {
        header('Location: ../employer/account_profile.php');
        exit();
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $idContract = $_POST['idContract'];
    } else {
        header('Location: ./contract_manager.php');
        exit();
    }
    
    $contract = getContractList($idContract);
    $contract = $contract[0];

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

    <link rel="stylesheet" href="../styles/admin_styles/contract_manager.css">
    <link rel="stylesheet" href="../styles/employer_styles/manage_worker.css">
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

    <main class='employer'>
        <div class='container application'>
            <div class='content'>
                <div class='info contract'>  
                    <form class="contract-info detail" action='../database/update_contract.php' method="POST" enctype="multipart/form-data">
                        <div class='title'>
                            <h3 class='t-align-center w-100'>Contract Info</h3>
                        </div>
                        <div class='information w-100 flex-1 flex-row align-start'>
                            <div class="left">
                                <input type='hidden' name='idWorker' value='<?php echo $contract['idWorker'] ?>' readonly>
                                <div class='data'>
                                    <h4 class="label">Contract ID</h4>
                                    <input class="value" type='text' name='idContract' value='<?php echo $contract['idContract'] ?>' readonly>
                                </div>
                                <div class='data'>
                                    <h4 class="label">Status (Editable)</h4>
                                    <select class='value' name='contractStatus'>
                                        <option value='Hired' <?php echo ($contract['contractStatus'] == 'Hired' ? 'selected' : '')?>>Hired</option>
                                        <option value='Pending' <?php echo ($contract['contractStatus'] == 'Pending' ? 'selected' : '')?>>Pending</option>
                                        <option value='Completed' <?php echo ($contract['contractStatus'] == 'Completed' ? 'selected' : '')?>>Completed</option>
                                        <option value='Canceled' <?php echo ($contract['contractStatus'] == 'Canceled' ? 'selected' : '')?>>Canceled</option>
                                    </select>
                                </div> 
                                <div class='data'>
                                    <h4 class="label">Date Created</h4>
                                    <p class="value"><?php echo $contract['date_created'] ?></p>
                                </div>
                                <div class='data <?php echo (isset($contract['contractImg']) && ($contract['contractStatus'] == 'Hired' || $contract['contractStatus'] == 'Completed') ? '' : 'hidden')?>' >
                                    <h4 class="label">Contract Image</h4>
                                    <div class="image-preview"><img src='<?php echo getImageSrc($contract['contractImg'])?>'></div>
                                </div>
                            </div>
                            <div class="right">
                                <div class='data'>
                                    <h4 class="label">Start Date (Editable)</h4>
                                    <?php echo ($contract['contractStatus'] == 'Hired' || $contract['contractStatus'] == 'Completed' 
                                        ? "<input class='value' type='date' name='startDate' value=". $contract['startDate']. ">" 
                                        : "<p class='value'>N/A</p>");
                                        ?>
                                </div>
                                <div class='data'>
                                    <h4 class="label">End Date (Editable)</h4>
                                    <?php echo ($contract['contractStatus'] == 'Hired' || $contract['contractStatus'] == 'Completed' 
                                        ? "<input class='value' type='date' name='endDate' value=". $contract['endDate']. ">" 
                                        : "<p class='value'>N/A</p>");
                                        ?>
                                </div>
                                <div class='data'>
                                    <h4 class="label">Salary Amount (Editable)</h4>
                                    <?php echo ($contract['contractStatus'] == 'Hired' || $contract['contractStatus'] == 'Completed' 
                                        ? "<input class='value' type='text' name='salaryAmt' value=". $contract['salaryAmt']. ">" 
                                        : "<p class='value'>N/A</p>");
                                        ?>                                
                                </div>
                                <div class='data <?php echo ($contract['contractStatus'] == 'Hired' || $contract['contractStatus'] == 'Completed' ? '' : 'hidden')?>' >
                                    <h4 class="label">Change Contract Image</h4>
                                    <input class="value" type='file' name='contractImg' accept="image/jpeg, image/png, image/jpg"></p>
                                </div>
                            </div>
                        </div>
                        <div class='m-l-auto'>
                            <button type='submit' name='submit' value='submit' class='green-white-btn '>Save Changes</button>
                        </div>
                    </form>

                    <form class="meet-info detail" action='../database/update_meet_info.php' method='POST'>
                        <div class='title'>
                            <h3 class='t-align-center w-100'>Interview Info</h3>
                        </div>
                        <div class="information w-100 flex-1">
                            <input type='hidden' name='idMeeting' value='<?php echo $contract['idMeeting']?>'>
                            <div class="left">
                                <div class='data'>
                                    <h4 class="label">Interview Date</h4>
                                    <input class="value" type='datetime-local' name='meetDate' value='<?php echo $contract['meetDate'] ?>'>
                                </div>
                                <div class='data'>
                                    <h4 class="label">Platform</h4>
                                    <input class="value" type='text' name='platform' value='<?php echo $contract['platform'] ?>'>
                                </div>
                            </div>
                            <div class="right">
                                <div class='data'>
                                    <h4 class="label">Link</h4>
                                    <input class="value" type='text' name='link' value='<?php echo $contract['link'] ?>'>
                                </div>
                                <div class='data'>
                                    <h4 class="label">Employer Message</h4>
                                    <input class="value" type='text' name='employerMessage' value="<?php echo $contract['employerMessage'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class='m-l-auto'>
                            <button type='submit' name='submit' value='submit' class='green-white-btn '>Save Changes</button>
                        </div>
                    </form>

                    <div class="employer-info detail">
                        <div class='title'>
                            <h3 class='t-align-center w-100'>Employer Info</h3>
                        </div>
                        <div class="information w-100 flex-1">
                            <div class="left">
                                <div class='data'>
                                    <h4 class="label">Employer Profile</h4>
                                    <div class="image-preview"><img src='<?php echo (isset($contract['employerProfilePic']) ? getImageSrc($contract['employerProfilePic']) : '../img/user-icon.png') ?>' alt='profile'></div>
                                </div>
                                <div class='data'>
                                    <h4 class="label">Employer Name / User ID</h4>
                                    <p class="value">
                                        <?php echo $contract['employerFname'] . " " . $contract['employerLname'] . " / <span class='c-blue'>(" . $contract['employerIdUser'] . ")</span>"?>
                                    </p>
                                </div>
                                <div class='data'>
                                    <h4 class="label">Employer Age</h4>
                                    <p class="value"><?php echo calculateAge($contract['employerBirthdate']) ?></p>
                                </div>
                            </div>
                            <div class="right">
                                <div class='data'>
                                    <h4 class="label">Employer Sex</h4>
                                    <p class="value"><?php echo $contract['employerSex'] ?></p>
                                </div>
                                <div class='data'>
                                    <h4 class="label">Employer Email Address</h4>
                                    <p class="value"><?php echo $contract['employerEmail'] ?></p>
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
                                    <h4 class="label">Worker Name / ID</h4>
                                    <p class="value "><?php echo $contract['workerFname'] . " " . $contract['workerLname'] . " / <span class='c-blue'>(" . $contract['workerIdUser'] . ")</span>"?></p>
                                </div>
                                <div class='data'>
                                    <h4 class="label">Worker Type</h4>
                                    <p class="value "><?php echo $contract['workerEmail'] ?></p>
                                </div>
                                <div class='data'>
                                    <h4 class="label">Worker Sex</h4>
                                    <p class="value "><?php echo $contract['workerSex'] ?></p>
                                </div>
                                <div class='data'>
                                    <h4 class="label">Worker Profile</h4>
                                    <div class='image-preview'>
                                        <img src="<?php echo (isset($contract['workerProfilePic']) ? getImageSrc($contract['workerProfilePic']) : '../img/user-icon.png') ?>" alt='worker profile'>
                                    </div>
                                </div>
                            </div>
                            <div class="right">
                                <div class='data'>
                                    <h4 class="label">Worker Age</h4>
                                    <p class="value "><?php echo calculateAge($contract['workerBirthdate']) ?></p>
                                </div>
                                <div class='data'>
                                    <h4 class="label">Worker Type</h4>
                                    <p class="value "><?php echo $contract['workerType'] ?></p>
                                </div>
                                <div class='data'>
                                    <h4 class="label">Years of Experience</h4>
                                    <p class="value "><?php echo $contract['yearsOfExperience'] ?></p>
                                </div>
                                <div class='data'>
                                    <h4 class="label">Curriculum Vitae</h4>
                                    <div class='image-preview'>
                                        <img src='<?php echo (isset($contract['curriculumVitae']) ? getImageSrc($contract['curriculumVitae']) : '../img/document-sample.jpg') ?>' alt='curriculum vitae'>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='buttons m-l-auto flex-row'>
                        <form action='../database/delete_contract.php' method='POST' onsubmit='return confirmDelete()'>
                            <input type='hidden' name='idContract' value='<?php echo $contract['idContract'] ?>'>
                            <button class='red-white-btn' type='submit' name='submit' value='submit'>Delete Contract</button>
                        </form>
                        <form action='./display_salary_payments.php' method='POST' class='<?php echo $contract['contractStatus'] != 'Hired' ? 'hidden' : '' ?>'>
                            <input type='hidden' name='idContract' value='<?php echo $contract['idContract'] ?>'>
                            <button class='green-white-btn' type='submit' name='submit' value='submit'>Salary Payments</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>




</body>
</html>