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
    
    if (isset($_POST['idUser']) && isset($_POST['userType'])) {
        $userType = $_POST['userType'];
        $idUser = $_POST['idUser'];

        $user = fetchAllUserInformation($idUser, $userType);

    } else {
        header('Location: ../admin/user_accounts.php');
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
    <script src='../scripts/jquery-3.7.1.min.js' defer></script>

</head>
<body>
    <header class='logged-in'>
        <div class="content">
            <button class='orange-white-btn'><a href='./verify_users.php' class='c-light'>Back to Verify Users</a></button>
        </div>
    </header>

    <main class='admin edit-user'>
        <div class='container application'>
            <form class='content' action='../database/update_user_account.php' method="POST" enctype="multipart/form-data">
                <div class="title">
                    <div class='image-preview'><img class='userProfile' src='<?php echo $user['profilePic'] ?>' alt='profile'></div>
                    <h3>Personal Information</h3>
                </div>
                <div class="info">
                    <div class="left">
                        <div class="data">
                            <h4 class="label">User ID</h4>
                            <input class="text-box userId" type='text' name='userId' value='<?php echo $user['idUser'];?>' readonly >
                        </div>
                        <div class="data">
                            <h4 class="label">First Name</h4>
                            <input class="text-box userFname" type='text' name='fname' value='<?php echo $user['fname'];?>' readonly>
                        </div>
                        <div class="data">
                            <h4 class="label">Last Name</h4>
                            <input class="text-box userLname" type='text' name='lname' value='<?php echo $user['lname'];?>' readonly>
                        </div>
                        <div class="data">
                            <h4 class="label">Sex</h4>
                            <input class="text-box userSex" type='text' name='sex' value='<?php echo $user['sex'];?>' readonly>
                        </div>
                        <div class="data">
                            <h4 class="label">User Type</h4>
                            <input class="text-box userTypeEl" type='text' name='usertype' value='<?php echo $user['userType'];?>' readonly>
                        </div>
                    </div>
                    <div class='right'>
                        <div class="data">
                            <h4 class="label">Email</h4>
                            <input class="text-box userEmail" type='email' name='email' value='<?php echo $user['email'];?>' readonly>
                        </div>
                        <div class="data">
                            <h4 class="label">Birthdate</h4>
                            <input class="text-box userBirthdate" type='date' name='birthdate' value='<?php echo $user['birthdate'];?>' readonly>
                        </div>
                        <div class="data">
                            <h4 class="label">Address</h4>
                            <input class="text-box userTypeEl" type='text' name='address' value='<?php echo $user['address'];?>' readonly>
                        </div>
                        <div class="data">
                            <h4 class="label">Contact No.</h4>
                            <input class="text-box userTypeEl" type='text' name='contactNo' value='<?php echo $user['contactNo'];?>' readonly>
                        </div>
                    </div>
                </div>

                <div class="title">
                    <h3><?php echo $user['userType'];?> Information</h3>
                </div>
                <div class="info <?php echo ($user['userType'] == 'Worker' ? '' : 'hidden')?>">
                    <h4 class='c-red t-align-center fs-medium w-100 <?php echo (isset($user['idWorker'])) ? 'hidden' : ''?>'>This worker have to submit application documents first.</h4>
                    <div class="left <?php echo (isset($user['idWorker'])) ? '' : 'hidden'?>">
                        <div class="data">
                            <h4 class="label">Worker Status</h4>
                            <input class="text-box" name='workerStatus' type='text' readonly value='<?php echo $user['workerStatus']; ?>'>
                        </div>
                        <div class="data">
                            <h4 class="label">Worker Type</h4>
                            <input class="text-box" type='text' name='workerType' value='<?php echo $user['workerType']; ?>' readonly>
                        </div>
                        <div class="data">
                            <h4 class="label">Paypal Email Address</h4>
                            <input class="text-box userTypeEl" type='email' name='paypalEmail' value='<?php echo (isset($user['paypalEmail']) ? $user['paypalEmail'] : '');?>' readonly>
                        </div>
                    </div>
                    <div class="right <?php echo (isset($user['idWorker'])) ? '' : 'hidden'?>">
                        <div class="data">
                            <h4 class="label">Years of Experience</h4>
                            <input class="text-box userTypeEl" type='number' name='yearsOfExperience' value='<?php echo (isset($user['yearsOfExperience']) ? $user['yearsOfExperience'] : '');?>' readonly>
                        </div>
                        <div class="data">
                            <h4 class="label">Height (cm)</h4>
                            <input class="text-box userTypeEl" type='number' name='height' value='<?php echo (isset($user['height']) ? $user['height'] : '');?>' readonly>
                        </div>
                        <div class="data">
                            <h4 class="label">Verification Status</h4>
                            <input class="text-box userTypeEl" type='text' name='workerVerifyStatus' value='<?php echo $user['verifyStatus'];?>' readonly>
                        </div>
                    </div>
                </div>
                <div class="title <?php echo (isset($user['idWorkerDocuments']) ? '' : 'hidden')?>">
                    <h3>Worker documents</h3>
                </div>
                <div class="info flex-column <?php echo (isset($user['idWorkerDocuments']) ? '' : 'hidden')?>">
                    <div class="top flex-row flex-center flex-1 m-b-2">
                        <div class="image-preview flex-center flex-column flex-1">
                            <img src='<?php echo $user['curriculumVitae'];?>'>
                            <h4>Curriculum Vitae</h4>
                        </div>
                        <div class="image-preview flex-center flex-column flex-1">
                            <img src='<?php echo $user['validID'];?>'>
                            <h4>Valid ID</h4>
                        </div>
                        <div class="image-preview flex-center flex-column flex-1">
                            <img src='<?php echo $user['medical'];?>'>
                            <h4>Medical</h4>
                        </div>
                        <div class="image-preview flex-center flex-column flex-1">
                            <img src='<?php echo $user['nbi'];?>'>
                            <h4>NBI</h4>
                        </div>
                        <div class="image-preview flex-center flex-column flex-1 <?php echo (isset($user['certificate']) ? '' : 'hidden' )?>">
                            <img src='<?php echo $user['certificate'];?>'>
                            <h4>Certificate</h4>
                        </div>
                    </div>
                </div>
                <div class="info <?php echo ($user['userType'] == 'Employer' ? '' : 'hidden')?>">
                    <div class="left">
                        <div class="data <?php echo (isset($user['validId']) ? '' : 'hidden'); ?>">
                            <div class="image-preview flex-row"><h4 class="label">Valid ID</h4><img class='validIdImg' src="<?php echo $user['validId'] ?>" alt="valid ID"></div>
                        </div>
                    </div>
                    <div class="right"></div>
                </div>

                <div class='buttons'>
                    <button class='orange-white-btn'><a class='c-light' href='./verify_users.php'>Cancel</a></button>
                    <form action='../database/update_verify_status.php' method='GET' enctype="multipart/form-data">
                        <input type='hidden' name='idUser' value="<?php echo $user['idUser']; ?>">
                        <input type='hidden' name='userType' value="<?php echo $user['userType']; ?>">
                        <button type='submit' name='decline' value='decline' class='red-white-btn'>Decline</button>
                    </form>
                    <form action='../database/update_verify_status.php' method='GET'>
                        <input type='hidden' name='idUser' value="<?php echo $user['idUser']; ?>">
                        <input type='hidden' name='userType' value="<?php echo $user['userType']; ?>">
                        <button type='submit' name='approve' value='approve' class='green-white-btn'>Approve</button>
                    </form>
                </div>

            </form>
        </div>
    </main>
</body>
</html>