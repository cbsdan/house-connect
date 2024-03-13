<?php
// Check first if the user is logged in
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
    if (isset($_POST['idWorker'])) {
        $worker = $workerObj -> getWorkerById($_POST['idWorker']);
    }
    if (isset($_POST['submitInterviewInfo'])) {
        if (isset($_POST['message']) && $_POST['message'] != '') {
            $message = $_POST['message'];
        } else {
            $message = null;
        }
        $interviewObj -> createInterview($_POST['interviewDate'], $_POST['interviewLocation'], $message, $worker['idWorker']);
        $workerObj -> updateWorker($worker['idWorker'], null, null, 'Verified', null, null, null, null, null, null);
        header ('Location: user_accounts.php');
        exit();
    }
} else {
    header('Location: ./verify_users.php');
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

    <link rel="stylesheet" href="../styles/employer_styles/salary_payment.css">
    <link rel="stylesheet" href="../styles/employer_styles/default.css">

    <!-- JavaScript -->
    <script src='../scripts/worker.js' defer></script>

</head>
<body>
    <header class='logged-in'>
        <div class="content">
            <button class='orange-white-btn'><a href='./verify_users.php'>Back to Verify Users</a></button>
        </div>
    </header>

    <main class='employer'>
        <div class='container application'>
            <div class='content'>
                <div class='title'>
                    <img class='user-profile' src='../img/wallet-icon.png' placeholder='payment-icon'>
                    <h3>Interview Information</h3>  
                </div>
                <form class="info" action='' method='POST' enctype="multipart/form-data"> 
                    <input type="hidden" name='idWorker' value='<?php echo $worker['idWorker']?>'>
                    
                    <label class="label">Inteview Date</label>
                    <input class='text-box' name='interviewDate' type='datetime-local'>
                    
                    <label class="label">Interview Location</label>
                    <input class="text-box" name='interviewLocation' type='text'>
                    
                    <label class="label">Additional Message (optional)</label>
                    <textarea class="text-box" name='message' placeholder="Enter here" required></textarea>
                    
                    <button class="orange-white-btn" type="submit" name="submitInterviewInfo">Submit</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>