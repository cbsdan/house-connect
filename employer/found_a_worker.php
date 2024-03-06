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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['next-worker'])) {
            array_pop($_POST);

            $candidateWorkers = [];
            foreach($_POST as $candidateWorker) {
                $candidateWorkers[]['idUser'] = $candidateWorker;
            }            
            
        } else {
            $workerType = $_POST['workerType-preferred'];
    
            if (!isset($_POST['sex-preferred']) || $_POST['sex-preferred'] == 'unset') {
                $sex = null;
            } else {
                $sex = $_POST['sex-preferred'];
            }
    
            if (isset($_POST['height-preferred']) && $_POST['height-preferred'] != '') {
                $height = $_POST['height-preferred'];
            } else {
                $height = null;
            }
    
            if (isset($_POST['age-preferred']) && $_POST['age-preferred'] != '') {
                $age = $_POST['age-preferred'];
            } else {
                $age = null;
            }
    
            if (isset($_POST['yearsOfExperience-preferred']) && $_POST['yearsOfExperience-preferred'] != '') {
                $yearsOfExperience = $_POST['yearsOfExperience-preferred'];
            } else {
                $yearsOfExperience = null;
            }
            $candidateWorkers = searchCandidateWorkersIdUser($workerType, $sex, $age, $height, $yearsOfExperience);
            
        }
        
        if (isset($candidateWorkers)) {
            if(is_array($candidateWorkers) && count($candidateWorkers) > 1) {
                $otherCandidates = array_slice($candidateWorkers, 1);
            }
            $candidateWorkerId = $candidateWorkers[0]['idUser'];
            $candidateWorker = fetchAllWorkerInformation($candidateWorkerId);        
        }

    } else {
        header('Location: ./find_a_worker.php');
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
                    <nav>
                        <a href='./find_a_worker.php' class='c-light fw-bold'>FIND A WORKER</a>
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
                    <div class="left">
                        <img class='user-profile' src='../img/search-icon.png' placeholder='profile'>
                        <h3>Find a Worker</h3>  
                    </div>
                    <div class='right'>
                        <form class='find-another-worker-btn' action='./found_a_worker.php' method='POST' <?php echo (isset($otherCandidates) ? '' : 'hidden')?>>
                            <?php 
                                // If $otherCandidates is set, loop through and generate hidden inputs
                                if (isset($otherCandidates)) {
                                    foreach ($otherCandidates as $index => $otherCandidate) {
                                        foreach ($otherCandidate as $inputName => $inputValue) {
                                            echo "<input type='hidden' name='$inputName$index' value='$inputValue'>";
                                        }
                                    }
                                }
                            ?>
                            <button type='submit' name='next-worker' value='submit'>FIND ANOTHER</button>
                        </form>
                        <button class='cancel-btn'><a href='./find_a_worker.php' class='c-light'>CANCEL</a></button>
                        <form class='hire-worker-btn <?php echo (isset($candidateWorker) ? '' : 'hidden') ?>' action='./meet_details.php' method='POST'>
                            <input type='hidden' name='workerIdUser' value='<?php echo $candidateWorker['idUser'];?>' >
                            <button type='submit'>INTERVIEW WORKER</button>
                        </form>
                    </div> 
                </div>

                <!-- Display the Worker Info -->
                <div class='info flex-column flex-center <?php echo (isset($candidateWorker) ? '' : 'hidden');?>'>
                    <div class='top flex-1'>
                        <div class='profile-image image-preview'>
                            <img src='<?php echo $candidateWorker['profilePic']?>' alt='user-img'>
                            <P class='f-italic fs-small t-align-center'>Profile Picture</P>
                        </div>
                        <div class='curriculum-vitae image-preview'>
                            <img src='<?php echo $candidateWorker['curriculumVitae']?>' alt='cv-img'>
                            <P class='f-italic fs-small t-align-center'>Curriculum Vitae</P>
                        </div>
                        <div class='certificates image-preview <?php echo (isset($candidateWorker['certificate']) ? '' : 'hidden') ?>'>
                            <img src='<?php echo $candidateWorker['certificate']?>' alt='certificate-img'>
                            <P class='f-italic fs-small t-align-center'>Certificate</P>
                        </div>
                    </div>
                    <div class=' flex-1 flex-row'>
                        <div class="left">
                            <p class="label">Worker Name</p>
                            <p class="text-box"><?php echo $candidateWorker['fname'] . " " . $candidateWorker['lname']?></p>
                            <p class="label">Age / Birthdate </p>
                            <p class="text-box"><?php echo date_diff(date_create($candidateWorker['birthdate']), date_create('today'))->y . " years old (" . $candidateWorker['birthdate'] . ") "?></p>
                            <p class="label">Sex</p>
                            <p class="text-box"><?php echo $candidateWorker['sex'] ?></p>
                        </div>
                        <div class="right">
                            <p class="label">Worker Type</p>
                            <p class="text-box"><?php echo $candidateWorker['workerType'] ?></p>
                            <p class="label">Height</p>
                            <p class="text-box"><?php echo $candidateWorker['height'] ?></p>
                            <p class="label">Years of Experience</p>
                            <p class="text-box"><?php echo $candidateWorker['yearsOfExperience'] ?></p>
                        </div>
                    </div>
                </div>

                <!-- If there is no previous work -->
                <div class='no-record-label <?php echo (isset($candidateWorker) ? 'hidden' : '');?>'>
                    <p>There are no worker found!</p>
                </div>

            </div>
        </div>
    </main>
</body>
</html>