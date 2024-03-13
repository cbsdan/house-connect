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

    $userId = $_SESSION['idUser'];
    
    $worker = $workerObj-> getWorkersByConditions(null, null, null, null, null, null, null, null, $userId);

    if (isset ($worker) && $worker != false) {
        $worker = $worker[0];

        $workerDocuments = $workerDocumentsObj -> getWorkerDocumentById($worker['idWorkerDocuments']);

        if ($worker['verifyStatus'] == 'Not Verified') {
            $stepNumber = 3; 
        } else {
            if (isset($workerDocuments['medical']) && isset($workerDocuments['nbi']) && $worker['qualification_status'] == 'Qualified') {
                header('Location: ./current_employer.php');
                exit();  
            } else if ($worker['qualification_status'] != 'Qualified') {
                $interview = $interviewObj -> getInterviewByConditions(['worker_idWorker' => $worker['idWorker']]);
                if ($interview != false) {
                    $interview = $interview[0];
                }
                $stepNumber = 4;
            }
            else {
                $stepNumber = 5;
            }
        }
    } else {
        if (isset($_POST['step1done'])) {
            $stepNumber = 2; 
        } else {
            $stepNumber = 1; 
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
    <link rel="stylesheet" href="../styles/home.css">
    <link rel="stylesheet" href="../styles/includes.css">
    <link rel="stylesheet" href="../styles/media_query.css">
    <link rel="stylesheet" href="../styles/default.css">

    <link rel="stylesheet" href="../styles/worker_styles/application.css">
    <link rel="stylesheet" href="../styles/worker_styles/default.css">

    <!-- JavaScript -->
    <script src='../scripts/worker.js' defer></script>
    <style>
        .bottom {
            gap: 1rem;
        }
    </style>
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
            <div class='bottom empty'>
                <div class="navigation-container">
                    <nav class='w-100'>
                        <a href='../logout.php' class='c-light m-l-auto'>LOG OUT</a>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <main class='worker'>
        <div class='container application'>
            <div class='content'>
                <div class='steps-container'>
                    <div class='guideline'></div>
                    <div class='steps'>
                        <div class="step 1 <?php echo (($stepNumber == 1) ? 'active' : '')?>">
                            <p>Step 1</p>
                            <span class='label'>Personal Information</span>
                        </div>
                        <div class="step 2 <?php echo (($stepNumber == 2) ? 'active' : '')?>">
                            <p>Step 2</p>
                            <span class='label'>Documents Submission</span>
                        </div>
                        <div class="step 3 <?php echo (($stepNumber == 3) ? 'active' : '')?>">
                            <p>Step 3</p>
                            <span class='label'>Documents Verification</span>
                        </div>
                        <div class="step 4 <?php echo (($stepNumber == 4) ? 'active' : '')?>">
                            <p>Step 4</p>
                            <span class='label'>Interview with Agency</span>
                        </div>
                        <div class="step 5 <?php echo (($stepNumber == 5) ? 'active' : '')?>">
                            <p>Step 5</p>
                            <span class='label'>Additional Documents</span>
                        </div>
                    </div>
                </div>
                <div class='form-container step1 <?php echo (($stepNumber == 1) ? '' : 'hidden')?>'>
                    <div class='title'>
                        <img src='../img/user-icon.png'>
                        <h3>Personal Information</h3>
                    </div>
                    <form action="./application.php" method="POST" enctype="multipart/form-data">
                        <div class='left'>
                            <div class='input-container'>
                                <label for="selectWorkType">Select Work Type</label>
                                <select name="workType" id='selectWorkType'>
                                    <option value="Nanny">Nanny</option>
                                    <option value="Cook">Cook</option>
                                    <option value="Maid">Maid</option>
                                    <option value="Gardener">Gardener</option>
                                    <option value="Driver">Driver</option>
                                </select>
                            </div>  
                            <div class='input-container'>
                                <label for="inputYrsOfExp">Years of Experience</label>
                                <input id='inputYrsOfExp' name='yearsOfExperience' type="number" min=0 max=100 required>
                            </div>  
                            <div class='input-container'>
                                <label for="inputHeight">Height (cm)</label>
                                <input id='inputHeight' name='height' type="number" min=0 max=1000 required>
                            </div>  
                        </div>
                        <button type='submit' class='right next1' name='step1done' value='1'>NEXT</button>
                    </form>
                </div>
                
                <div class='form-container step2 <?php echo (($stepNumber == 2) ? '' : 'hidden')?>'>
                    <div class='title'>
                        <img src='../img/documents-icon.png'>
                        <h3>Required Documents</h3>
                    </div>
                    <form action="../database/submit_application.php" method="POST" enctype="multipart/form-data">
                        <!-- Store also the data from step1 form -->
                        <input type='hidden' name = 'workType' value='<?php echo (isset($_POST['workType']) ? $_POST['workType'] : '')?>'>
                        <input type='hidden' name = 'yearsOfExperience' value='<?php echo (isset($_POST['yearsOfExperience']) ? $_POST['yearsOfExperience'] : '')?>'>
                        <input type='hidden' name = 'height' value='<?php echo (isset($_POST['height']) ? $_POST['height'] : '')?>'>
                        <div class='left'>
                            <div class='input-container'>
                                <label for="imageUpload">Choose an image to upload</label>
                                <input type="file" id="imageUpload" name="profilePic" accept="image/jpeg, image/png, image/jpg" required>
                            </div>  
                            <div class='input-container'>
                                <label for="uploadCV">Curriculum Vitae</label>
                                <input type="file" id="uploadCV" name="curriculumVitae" accept="image/jpeg, image/png, image/jpg" >
                            </div>  
                            <div class='input-container'>
                                <label for="uploadValidID">One (1) Valid ID</label>
                                <input type="file" id="uploadValidID" name="validID" accept="image/jpeg, image/png, image/jpg" required>
                            </div>  
                            <div class='input-container'>
                                <label for="uploadCertifications">Certifications [optional]</label>
                                <input type="file" id="uploadCertifications" name="certifications" accept="image/jpeg, image/png, image/jpg" >
                            </div>  
                        </div>

                        <button type='submit' class='right next2' name='step2done' value='1'>NEXT</button>
                    </form>
                </div>

                <div class='form-container step3 <?php echo (($stepNumber == 3) ? '' : 'hidden')?>'>
                    <div class='title'>
                        <img src='../img/documents-icon.png'>
                        <h3>Documents Verification</h3>
                    </div>
                    <div class='info'>
                        <div class='left'>
                            <div class='input-container'>
                                <label for="documentStatus" class='label m-b-2'>Status</label>
                                <input type="text" id="documentStatus" name="documentStatus" class='text-box c-red' value='<?php echo $worker['verifyStatus'];?>'>
                            </div>  
                            <span class='f-italic '>Approval might take more than 24 hours or more. Thank you for your patience!</span>
                        </div>
                    </div>
                </div>
                
                <div class='form-container step4 <?php echo (($stepNumber == 4) ? '' : 'hidden')?>'>
                    <div class='title'>
                        <img src='../img/documents-icon.png'>
                        <h3>Interview with Agency</h3>
                    </div>
                    <div class='info d-flex flex-column'>
                        <div class="top">
                            <span class='f-italic fs-large fw-bold m-b-2 <?php echo ($worker['qualification_status'] == 'Pending' ? 'c-blue' : 'c-red'); ?>'>
                                <?php echo ($worker['qualification_status'] == 'Pending' ? 'You need to come to Interview with agency' : 'Sorry you are not qualified based on your interview with agency')?>
                            </span>
                        </div>
                        <div class="bottom d-flex">
                            <div class='left'>
                                <div class='input-container'>
                                    <label for="documentStatus" class='label m-b-2'>Status</label>
                                    <input type="text" id="documentStatus" name="documentStatus" class='text-box' value='<?php echo $worker['qualification_status'];?>'>
                                </div>  
                                <div class='input-container'>
                                    <label class='label m-b-2'>Interview Date</label>
                                    <input type="text" id="documentStatus" name="interviewDate" class='text-box' value='<?php echo $interview['interviewDate'];?>'>
                                </div>  
                            </div>
                            <div class="left">
                                <div class='input-container'>
                                    <label class='label m-b-2'>Interview Location</label>
                                    <input type="text" id="documentStatus" name="interviewDate" class='text-box' value="<?php echo $interview['interviewLocation'];?>">
                                </div>  
                                <div class='input-container'>
                                    <label class='label m-b-2'>Message from Agency</label>
                                    <input type="text" id="documentStatus" name="interviewDate" class='text-box' value="<?php echo $interview['message'];?>">
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>

                <div class='form-container step4 <?php echo (($stepNumber == 5) ? '' : 'hidden')?>'>
                    <div class='title'>
                        <img src='../img/documents-icon.png'>
                        <h3>Additional Documents</h3>
                    </div>
                    <form action="../database/submit_application.php" method="POST" enctype="multipart/form-data">
                        <div class='left'> 
                            <div class='input-container'>
                                <label for="uploadNBI">NBI Clearance</label>
                                <input type="file" id="uploadNBI" name="nbi" accept="image/jpeg, image/png, image/jpg" required>
                            </div>  
                            <div class='input-container'>
                                <label for="uploadMedical">Medical</label>
                                <input type="file" id="uploadMedical" name="medical" accept="image/jpeg, image/png, image/jpg" required>
                            </div>  
                            <div class='input-container'>
                                <label for="paypalEmail">Paypal Email</label>
                                <input id='paypalEmail' name='paypalEmail' type="gmail" required>
                            </div>  
                        </div>
                        <button type='submit' class='right' name='step4done' value='1'>SUBMIT</button>
                    </form>
                </div>
            </div>

        </div>
    </main>
</body>
</html>