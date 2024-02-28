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
                        <a href='../login.php' class='c-light m-l-auto'>LOG OUT</a>
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
                        <div class="step 1 active">
                            <p>Step 1</p>
                            <span class='label'>Personal Information</span>
                        </div>
                        <div class="step 2">
                            <p>Step 2</p>
                            <span class='label'>Documents Submission</span>
                        </div>
                        <div class="step 3">
                            <p>Step 3</p>
                            <span class='label'>Documents Verification</span>
                        </div>
                    </div>
                </div>
                <div class='form-container step1'>
                    <div class='title'>
                        <img src='../img/user-icon.png'>
                        <h3>Personal Information</h3>
                    </div>
                    <form action="" method="POST" enctype="multipart/form-data">
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
                            <div class='input-container'>
                                <label for="imageUpload">Choose an image to upload</label>
                                <input type="file" id="imageUpload" name="profilePic" accept="image/jpeg, image/png, image/jpg" required>
                            </div>  
                        </div>
                        <button class='right next1' name='step1done'>NEXT</button>
                    </form>
                </div>

                <div class='form-container step2 hidden'>
                    <div class='title'>
                        <img src='../img/documents-icon.png'>
                        <h3>Required Documents</h3>
                    </div>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class='left'>
                            <div class='input-container'>
                                <label for="uploadCV">Curriculum Vitae</label>
                                <input type="file" id="uploadCV" name="curriculumVitae" accept="image/jpeg, image/png, image/jpg" >
                            </div>  
                            <div class='input-container'>
                                <label for="uploadValidID">One (1) Valid ID</label>
                                <input type="file" id="uploadValidID" name="validID" accept="image/jpeg, image/png, image/jpg" required>
                            </div>  
                            <div class='input-container'>
                                <label for="uploadNBI">NBI Clearance</label>
                                <input type="file" id="uploadNBI" name="nbi" accept="image/jpeg, image/png, image/jpg" required>
                            </div>  
                            <div class='input-container'>
                                <label for="uploadMedical">Medical</label>
                                <input type="file" id="uploadMedical" name="medical" accept="image/jpeg, image/png, image/jpg" required>
                            </div>  
                            <div class='input-container'>
                                <label for="uploadCertifications">Certifications [optional]</label>
                                <input type="file" id="uploadCertifications" name="certifications" accept="image/jpeg, image/png, image/jpg" >
                            </div>  
                        </div>

                        <button class='right next2' name='step2done'>NEXT</button>
                    </form>
                </div>

                <div class='form-container step3 hidden'>
                    <div class='title'>
                        <img src='../img/documents-icon.png'>
                        <h3>Documents Verification</h3>
                    </div>
                    <form action="../database/" method="POST">
                        <div class='left'>
                            <div class='input-container'>
                                <label for="documentStatus">Status</label>
                                <input type="text" id="documentStatus" name="documentStatus" accept="image/jpeg, image/png, image/jpg" >
                            </div>  
                            <span class='f-italic'>Approval might take more than 24 hours or more. Thank you for your patience!</span>
                        </div>
                        <button class='right next3' name='step3done'><a class='c-light' href='./current_employer.php'>SUBMIT</a></button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>