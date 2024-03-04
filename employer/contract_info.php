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
        if (!isset($_POST['idContract'])) {
            header('Location: ./manage_worker.php');
            exit();
        }
        
        $idContract = $_POST['idContract'];

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

    <link rel="stylesheet" href="../styles/employer_styles/manage_worker.css">
    <link rel="stylesheet" href="../styles/employer_styles/default.css">

    <!-- JavaScript -->
    <script src='../scripts/worker.js' defer></script>

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
                        <div class='title'>
                            <h3 class='t-align-center w-100'>Contract Info</h3>
                        </div>
                        <div class='information w-100 flex-1 flex-row align-start'>
                            <div class="left">
                                <div class='data'>
                                    <h4 class="label">Contract ID</h4>
                                    <p class="value">001</p>
                                </div>
                                <div class='data'>
                                    <h4 class="label">Status</h4>
                                    <p class="value">Hired</p>
                                </div>
                                <div class='data'>
                                    <h4 class="label">Date Created</h4>
                                    <p class="value">January 01, 2024</p>
                                </div>
                            </div>
                            <div class="right">
                                <div class='data'>
                                    <h4 class="label">Start Date</h4>
                                    <p class="value">January 01, 2024</p>
                                </div>
                                <div class='data'>
                                    <h4 class="label">End Date</h4>
                                    <p class="value">January 01, 2025</p>
                                </div>
                                <div class='data'>
                                    <h4 class="label">Salary Amount</h4>
                                    <p class="value">P20,000</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="meet-info detail">
                        <div class='title'>
                            <h3 class='t-align-center w-100'>Interview Info</h3>
                        </div>
                        <div class="information w-100 flex-1">
                            <div class="left">
                                <div class='data'>
                                    <h4 class="label">Interview Data</h4>
                                    <p class="value">001</p>
                                </div>
                                <div class='data'>
                                    <h4 class="label">Platform</h4>
                                    <p class="value">Google Meet</p>
                                </div>
                            </div>
                            <div class="right">
                                <div class='data'>
                                    <h4 class="label">Link</h4>
                                    <p class="value">url-link</p>
                                </div>
                                <div class='data'>
                                    <h4 class="label">Employer Message</h4>
                                    <p class="value">Hello!</p>
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
                                    <h4 class="label">Profile</h4>
                                    <p class="value">
                                        <div class='image-preview'>
                                            <img src='../img/user-icon.png'>
                                        </div>
                                    </p>
                                </div>
                                <div class='data'>
                                    <h4 class="label">Name</h4>
                                    <p class="value">Juan Dela Cruz</p>
                                </div>
                                <div class='data'>
                                    <h4 class="label">Age</h4>
                                    <p class="value">25</p>
                                </div>
                            </div>
                            <div class="right">
                                <div class='data'>
                                    <h4 class="label">Worker Type</h4>
                                    <p class="value">Driver</p>
                                </div>
                                <div class='data'>
                                    <h4 class="label">Years of Experience</h4>
                                    <p class="value">1</p>
                                </div>
                                <div class='data'>
                                    <h4 class="label">Curriculum Vitae</h4>
                                    <p class="value">
                                        <div class='image-preview'>
                                            <img src='../img/document-sample.jpg' alt='contract-img'>
                                        </div>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form action='./salary_payment.php' method='POST'>
                        <input type='hidden' name='idEmployer' value=''>
                        <input type='hidden' name='idWorker' value=''>
                        <input type='hidden' name='idContract' value=''>
                        <button type='submit' class='pay-worker-btn green-white-btn '>Pay Worker Salary</button>
                    </form>
                </div>
            </div>
        </div>
    </main>


</body>
</html>