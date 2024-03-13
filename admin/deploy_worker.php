<?php
// Check first if the user is logged in
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

if (isset($_POST['idEmployerPreference'])) {
    $employerPreference = $employerRequestsObj -> getEmployerRequestById($_POST['idEmployerPreference']);
    $employer = $employerObj -> getEmployerById($employerPreference['employer_idEmployer']);
    $employerDetails = $userObj -> getUserById($employer['idUser']);
    $allWorkerResult = $workerObj -> getWorkersByConditions(null, 'Available', 'Verified', 'Qualified', null, null, null, null, null);

    $workerType = ($employerPreference['workerType'] != '' ? $employerPreference['workerType'] : null); 
    $sex = ($employerPreference['sex'] != '' ? $employerPreference['sex'] : null); 
    $age = ($employerPreference['age'] != '' ? $employerPreference['age'] : null); 
    $height = ($employerPreference['height'] != '' ? $employerPreference['height'] : null); 
    $yearsOfExperience = ($employerPreference['yrsOfExperience'] != '' ? $employerPreference['yrsOfExperience'] : null); 

    $candidateWorkers = searchCandidateWorkersIdUser($workerType, $sex, $age, $height, $yearsOfExperience);


} else {
    header('Location: ./employer_requests.php');
    exit();
}

if (isset($_POST['deploy-worker'])) {
    //create new contract
    $idContract = $contractObj -> createContract('Pending', null, null, null, null, null, $_POST['idWorker'], $_POST['idEmployer']);

    $message = (isset($_POST['message']) ? $_POST['message'] : null);
    $meetingObj-> createMeeting($_POST['meetDate'], $_POST['locationAddress'], $message, $idContract);
    $workerObj -> updateWorker($_POST['idWorker'], null, "Pending", null, null, null, null, null, null, null);
    //update employerPreference
    $employerRequestsObj -> updateEmployerRequest($_POST['idEmployerPreference'], ["status" => 'Successful', "contract_idContract" => $idContract]);
    header('Location: ./employer_requests.php');
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

    <link rel="stylesheet" href="../styles/admin_styles/create-new-contract.css">
    <link rel="stylesheet" href="../styles/admin_styles/default.css">

    <!-- JavaScript -->
    <script src='../scripts/worker.js' defer></script>

    <style>
        .max-height-overflow-scroll {
            max-height: 500px;
            overflow-y: auto;
        }
        .employer-preferences {
            background-color: var(--main-color);
            color: var(--light-text);
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
        }
    </style>

</head>
<body>
    <header class='logged-in'>
        <div class="content">
            <button class='orange-white-btn'><a href='./employer_requests.php' class='c-light'>Back to Employer Requests</a></button>
        </div>
    </header>

    <main class='admin'>
        <div class='container application'>
            <div class='content'>
                <div class='title'>
                    <img class='' src='../img/documents-icon.png' placeholder='documents-icon'>
                    <h3>Deploy a Worker</h3>  
                </div>
                <div class="info flex-wrap">
                <div class='employer-preferences m-b-2'>
                    <div class="top m-b-2">
                        <h3 class='t-align-center fs-large'>Employer Preferences</h3>
                    </div>
                    <div class="bottom d-flex">
                        <div class='left flex-1'>
                            <p class='label'>Age</p>
                            <p class='text-box'><?php echo ($employerPreference['age'] != '' ? $employerPreference['age'] : 'Not Set') ?></p>
                            <p class='label'>Sex</p>
                            <p class='text-box'><?php echo ($employerPreference['sex'] != '' ? $employerPreference['sex'] : 'Not Set') ?></p>
                            <p class='label'>Height</p>
                            <p class='text-box'><?php echo ($employerPreference['height'] != '' ? $employerPreference['height'] : 'Not Set') ?></p>
                        </div>
                        <div class="right flex-1">
                            <p class='label'>Years of Experience</p>
                            <p class='text-box'><?php echo ($employerPreference['yrsOfExperience'] != '' ? $employerPreference['yrsOfExperience'] : 'Not Set') ?></p>
                            <p class='label'>Request Note</p>
                            <p class='text-box'><?php echo ($employerPreference['additionalMessage'] != '' ? $employerPreference['additionalMessage'] : 'Not Set') ?></p>
                        </div>
                    </div>
                </div>
                <table class='max-height-overflow-scroll m-b-2'>
                    <thead>
                        <tr>
                            <th>ID Worker</th>
                            <th>Profile</th>
                            <th>Name</th>
                            <th>Worker Type</th>
                            <th>Age</th>
                            <th>Sex</th>
                            <th>Years of Experience</th>
                            <th>Height</th>
                            <th>Status</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if ($candidateWorkers != false) {
                            foreach($candidateWorkers as $candidateWorker) {
                                $worker = $workerObj -> getWorkerById($candidateWorker['idWorker']);
                                $workerDetails = $userObj -> getUserById($worker['idUser']);

                                echo "<tr>
                                        <td class='t-align-center'>". $worker['idWorker'] ."</td>
                                        <td class='t-align-center image-preview'><img src='". ($worker['profilePic'] != '' ?  getImageSrc($worker['profilePic']) : '../img/user-icon.png') ."'></td>
                                        <td>". $workerDetails['fname'] . " " . $workerDetails['lname']  . "</td>
                                        <td>". $worker['workerType'] . "</td>
                                        <td>". calculateAge($workerDetails['birthdate']) ."</td>
                                        <td>". $workerDetails['sex'] . "</td>
                                        <td>". $worker['yearsOfExperience'] . "</td>
                                        <td>". $worker['height'] . "</td>
                                        <td>". $worker['workerStatus'] . "</td>
                                        <td class='t-align-center'>
                                            <form action='./edit-user-account.php' method='POST'>
                                                <input type='hidden' name='idUser' value='". $workerDetails['idUser'] ."'>
                                                <input type='hidden' name='userType' value='". $workerDetails['userType'] ."'>
                                                <button type='submit' name='submit' class='c-yellow'>[View]</button>
                                            </form>
                                        </td>
                                    </tr>";
                            }
                        } else if ($allWorkerResult != false){
                            foreach($allWorkerResult as $workerResult) {
                                $worker = $workerObj -> getWorkerById($workerResult['idWorker']);
                                $workerDetails = $userObj -> getUserById($workerResult['idUser']);

                                echo "<tr>
                                        <td class='t-align-center'>". $worker['idWorker'] ."</td>
                                        <td class='t-align-center image-preview'><img src='". ($worker['profilePic'] != '' ?  getImageSrc($worker['profilePic']) : '../img/user-icon.png') ."'></td>
                                        <td>". $workerDetails['fname'] . " " . $workerDetails['lname']  . "</td>
                                        <td>". $worker['workerType'] . "</td>
                                        <td>". calculateAge($workerDetails['birthdate']) ."</td>
                                        <td>". $workerDetails['sex'] . "</td>
                                        <td>". $worker['yearsOfExperience'] . "</td>
                                        <td>". $worker['height'] . "</td>
                                        <td>". $worker['workerStatus'] . "</td>
                                        <td class='t-align-center'>
                                            <form action='./edit-user-account.php' method='POST'>
                                                <input type='hidden' name='idUser' value='". $workerDetails['idUser'] ."'>
                                                <input type='hidden' name='userType' value='". $workerDetails['userType'] ."'>
                                                <button type='submit' name='submit' class='c-yellow'>[View]</button>
                                            </form>
                                        </td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr>
                                    <td class='label t-align-center' colspan='9'>There are no worker available!</td>
                                  </tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <form class="info" action='' method='POST' enctype="multipart/form-data"> 
                    <div class="left">
                        <input class='text-box m-b-2' type="hidden" name="idEmployer" value='<?php echo $employer['idEmployer']?>'>
                        <input class='text-box m-b-2' type="hidden" name="idEmployerPreference" value='<?php echo $employerPreference['idEmployerPreference']?>'>
                        <label class="label">Select a Worker (Required)</label>
                        <select class='idWorker m-b-2' name='idWorker' required>
                            <?php
                                foreach($allWorkerResult as $worker) {
                                    $workerDetails = $userObj -> getUserById($worker['idUser']);

                                    echo "<option value='".$worker['idWorker']."'>".$worker['idWorker'] . " - " . $workerDetails['fname'] . " " . $workerDetails['lname'] . " (" . $worker['workerType'] . ")</option>";
                                };
                            ?>
                        </select>
                        <label class="label">Meet Date</label>
                        <input class='text-box m-b-2' type="datetime-local" name="meetDate" required>
                    </div>    
                    <div class="right">
                        <label class="label">Location Address</label>
                        <input class='text-box m-b-2' type="text" name="locationAddress" placeholder="Enter Address" required>
                        <label class="label">Message to Employer/Worker</label>
                        <textarea class='text-box m-b-2' type="text" name="message" placeholder="Enter Message"></textarea>
                    </div>
                    <button type='submit' class='orange-white-btn' name='deploy-worker' value='1'>Submit</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>