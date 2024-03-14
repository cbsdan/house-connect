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
    
    if (isset($_POST['idUser']) && isset($_POST['userType'])) {
        $userType = $_POST['userType'];
        $idUser = $_POST['idUser'];

        $user = fetchAllUserInformation($idUser, $userType);
        
        if ($userType == 'Worker') {
            $worker = $workerObj -> getWorkersByConditions(null, null, null, null, null, null, null, null, $_POST['idUser']);

            if ($interview = $interviewObj -> getInterviewByConditions(["worker_idWorker" => $worker[0]['idWorker']])) {
                $interview = $interviewObj -> getInterviewByConditions(["worker_idWorker" => $worker[0]['idWorker']]);
            } 
            
        }
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

    <style>
        .bottom {
            align-items: start;
        }
    </style>
</head>
<body>
    <header class='logged-in'>
        <div class="content">
            <button class='orange-white-btn'><a href='./user_accounts.php' class='c-light'>Back to User Accounts</a></button>
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
                            <input class="text-box userFname" type='text' name='fname' value='<?php echo $user['fname'];?>'>
                        </div>
                        <div class="data">
                            <h4 class="label">Last Name</h4>
                            <input class="text-box userLname" type='text' name='lname' value='<?php echo $user['lname'];?>'>
                        </div>
                        <div class="data">
                            <h4 class="label">Sex</h4>
                            <select class="text-box userSex" type='text' name='sex' ?>'>
                                <option value="Male" <?php echo ($user['sex'] == 'Male' ? 'selected' : '');?>>Male</option>
                                <option value="Female" <?php echo ($user['sex'] == 'Female' ? 'selected' : '');?>>Female</option>
                            </select>
                        </div>
                        <div class="data">
                            <h4 class="label">User Type</h4>
                            <input class="text-box userTypeEl" type='text' name='usertype' value='<?php echo $user['userType'];?>' readonly>
                        </div>
                    </div>
                    <div class='right'>
                        <div class="data">
                            <h4 class="label">Email</h4>
                            <input class="text-box userEmail" type='email' name='email' value='<?php echo $user['email'];?>'>
                        </div>
                        <div class="data">
                            <h4 class="label">Password</h4>
                            <input class="text-box userPassword" type='text' name='password' value='<?php echo $user['password'];?>'>
                        </div>
                        <div class="data">
                            <h4 class="label">Birthdate</h4>
                            <input class="text-box userBirthdate" type='date' name='birthdate' value='<?php echo $user['birthdate'];?>'>
                        </div>
                        <div class="data">
                            <h4 class="label">Address</h4>
                            <input class="text-box userTypeEl" type='text' name='address' value='<?php echo $user['address'];?>' >
                        </div>
                        <div class="data">
                            <h4 class="label">Contact No.</h4>
                            <input class="text-box userTypeEl" type='text' name='contactNo' value='<?php echo $user['contactNo'];?>' >
                        </div>
                    </div>
                </div>

                <div class="title">
                    <h3><?php echo $user['userType'];?> Information</h3>
                </div>
                <div class="info <?php echo ($user['userType'] == 'Worker' ? '' : 'hidden')?>">
                    <h4 class='c-red t-align-center fs-medium w-100 <?php echo (isset($user['idWorker'])) ? 'hidden' : ''?>'>This worker have to submit application documents first.</h4>
                    <div class="left <?php echo (isset($user['idWorker'])) ? '' : 'hidden'?>">
                        <div class='data <?php echo (isset($user['idWorker'])) ? '' : 'hidden'?>'>
                            <h4 class="label">Profile Picture</h4>
                            <input class="text-box " type='file' name='workerProfilePic' accept="image/jpeg, image/png, image/jpg" >
                        </div>
                        <div class="data">
                            <h4 class="label">Worker Status</h4>
                            <select class="text-box" name='workerStatus'>
                                <option value="Available" <?php if ($user['workerStatus'] == 'Available') echo 'selected'?>>Available</option>
                                <option value="Hired" <?php if ($user['workerStatus'] == 'Hired') echo 'selected'?>>Hired</option>
                                <option value="Pending" <?php if ($user['workerStatus'] == 'Pending') echo 'selected'?>>Pending</option>
                                <option value="Unavailable" <?php if ($user['workerStatus'] == 'Unavailable') echo 'selected'?>>Unavailable</option>
                            </select>
                        </div>
                        <div class="data">
                            <h4 class="label">Worker Type</h4>
                            <select class="text-box" name='workerType'>
                                <option value="Nanny" <?php if ($user['workerType'] == 'Nanny') echo 'selected'?>>Nanny</option>
                                <option value="Maid" <?php if ($user['workerType'] == 'Maid') echo 'selected'?>>Maid</option>
                                <option value="Gardener" <?php if ($user['workerType'] == 'Gardener') echo 'selected'?>>Gardener</option>
                                <option value="Cook" <?php if ($user['workerType'] == 'Cook') echo 'selected'?>>Cook</option>
                                <option value="Driver" <?php if ($user['workerType'] == 'Driver') echo 'selected'?>>Driver</option>
                            </select>
                        </div>
                        <div class="data">
                            <h4 class="label">Paypal Email Address</h4>
                            <input class="text-box userTypeEl" type='email' name='paypalEmail' value='<?php echo (isset($user['paypalEmail']) ? $user['paypalEmail'] : '');?>' >
                        </div>
                    </div>
                    <div class="right <?php echo (isset($user['idWorker'])) ? '' : 'hidden'?>">
                        <div class="data">
                            <h4 class="label">Years of Experience</h4>
                            <input class="text-box userTypeEl" type='number' name='yearsOfExperience' value='<?php echo (isset($user['yearsOfExperience']) ? $user['yearsOfExperience'] : '');?>' >
                        </div>
                        <div class="data">
                            <h4 class="label">Height (cm)</h4>
                            <input class="text-box userTypeEl" type='number' name='height' value='<?php echo (isset($user['height']) ? $user['height'] : '');?>' >
                        </div>
                        <div class="data">
                            <h4 class="label">Verification Status</h4>
                            <select class="text-box" name='workerVerifyStatus'>
                                <option value="Verified" <?php if ($user['verifyStatus'] == 'Verified') echo 'selected'?>>Verified</option>
                                <option value="Not Verified" <?php if ($user['verifyStatus'] == 'Not Verified') echo 'selected'?>>Not Verified</option>
                            </select>
                        </div>
                        <div class="data">
                            <h4 class="label">Qualification Status</h4>
                            <select class="text-box" name='qualification_status' required>
                                <option value="Not Qualified" <?php if ($user['qualification_status'] == 'Not Qualified') echo 'selected'?>>Not Qualified</option>
                                <option value="Pending" <?php if ($user['qualification_status'] == 'Pending') echo 'selected'?>>Pending</option>
                                <option value="Qualified" <?php if ($user['qualification_status'] == 'Qualified') echo 'selected'?>>Qualified</option>
                            </select>
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
                        <div class="image-preview flex-center flex-column flex-1 <?php echo (isset($user['medical']) ? '' : 'hidden' )?>">
                            <img src='<?php echo $user['medical'];?>'>
                            <h4>Medical</h4>
                        </div>
                        <div class="image-preview flex-center flex-column flex-1 <?php echo (isset($user['nbi']) ? '' : 'hidden' )?>">
                            <img src='<?php echo $user['nbi'];?>'>
                            <h4>NBI</h4>
                        </div>
                        <div class="image-preview flex-center flex-column flex-1 <?php echo (isset($user['certificate']) ? '' : 'hidden' )?>">
                            <img src='<?php echo $user['certificate'];?>'>
                            <h4>Certificate</h4>
                        </div>
                    </div>
                    <div class="bottom flex-row align-start flex-1">
                        <div class="left flex-1">
                            <input type='hidden' name='idWorkerDocuments' value='<?php echo $user['idWorkerDocuments'];?>'>
                            <div class='data'>
                                <h4 class="label">Update Curriculum Vitae</h4>
                                <input class="text-box " type='file' name='curriculumVitae' accept="image/jpeg, image/png, image/jpg" >
                            </div>
                            <div class='data'>
                                <h4 class="label">Update Worker ValidId</h4>
                                <input class="text-box " type='file' name='workerValidId' accept="image/jpeg, image/png, image/jpg" >
                            </div>
                            <div class='data'>
                                <h4 class="label">Update NBI</h4>
                                <input class="text-box " type='file' name='nbi' accept="image/jpeg, image/png, image/jpg" >
                            </div>
                        </div>
                        <div class="right flex-1">
                            <div class='data'>
                                <h4 class="label">Update Medical</h4>
                                <input class="text-box " type='file' name='medical' accept="image/jpeg, image/png, image/jpg" >
                            </div>
                            <div class='data'>
                                <h4 class="label">Update Certification</h4>
                                <input class="text-box " type='file' name='certificate' accept="image/jpeg, image/png, image/jpg" >
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="title <?php echo ($interview == false ? 'hidden' : '')?>">
                    <h3>Interview Information</h3>
                </div>
                <?php if (isset($interview) && $interview !== false): ?>
                    <div class="info d-flex">
                        <div class="left">
                            <input type="hidden" name='idInterview' value='<?php echo $interview[0]['idInterview'] ?>'>
                            <div class='data'>
                                <h4 class="label">Interview Date</h4>
                                <input class="text-box" type='datetime-local' name='interviewDate' value='<?php echo $interview[0]['interviewDate'] ?>' required>
                            </div>
                            <div class='data'>
                                <h4 class="label">Interview Location</h4>
                                <input class="text-box" type='text' name='interviewLocation' value="<?php echo $interview[0]['interviewLocation'] ?>" required>
                            </div>
                        </div>
                        <div class="right">
                            <div class='data'>
                                <h4 class="label">Message from Agency</h4>
                                <input class="text-box" type='text' name='message' value="<?php echo $interview[0]['message'] ?>">
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="info <?php echo ($user['userType'] == 'Employer' ? '' : 'hidden')?>">
                    <div class="left">
                        <div class="data">
                            <h4 class="label">Change Valid ID</h4>
                            <input class="text-box" type='file' name='employerValidId' accept="image/jpeg, image/png, image/jpg" >
                        </div>
                        <div class="data">
                            <h4 class="label">Profile Picture</h4>
                            <input class="text-box" type='file' name='employerProfilePic' accept="image/jpeg, image/png, image/jpg">
                        </div>
                    </div>
                    <div class="right">
                        <div class="data <?php echo (isset($user['validId']) ? '' : 'hidden'); ?>">
                            <div class="image-preview flex-row"><h4 class="label">Valid ID</h4><img class='validIdImg' src="<?php echo $user['validId'] ?>" alt="valid ID"></div>
                        </div>
                        <div class="data">
                            <h4 class="label">Verification Status</h4>
                            <select class="text-box" name='employerVerifyStatus'>
                                <option value="Verified" <?php if ($user['verifyStatus'] == 'Verified') echo 'selected'?>>Verified</option>
                                <option value="Not Verified" <?php if ($user['verifyStatus'] == 'Not Verified') echo 'selected'?>>Not Verified</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class='buttons'>
                    <button class='red-white-btn cancelBtn'><a href='./user_accounts.php' class='c-light'>Cancel</a></button>
                    <button type='submit' class='green-white-btn'>Save Changes</button>
                </div>

            </form>
        </div>
    </main>
</body>
</html>