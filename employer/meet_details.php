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
        $workerIdUser = $_POST['workerIdUser']; 
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
                    <nav >
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
                        <button id='formSubmit' class='interview-submit-btn'>CONFIRM</button>
                    </div> 
                </div>

                <form class="info" action='../database/create-contract.php' method='POST' id='meetScheduleForm'> 
                    <input type='hidden' name='workerIdUser' value='<?php echo $workerIdUser?>'>
                    <input type='hidden' name='employerIdUser' value='<?php echo $_SESSION['idUser']?>'>
                    <div class="left">
                        <label class="label">Interview Platform</label>
                        <input class="text-box" name='platform' type='text' placeholder="[Interview Platform]" required>
                        <label class="label">Interview Link</label>
                        <input class="text-box" name='link' type='text' placeholder="[Interview Link]" required>
                        <label class="label">Interview Schedule</label>
                        <input class="text-box" type='datetime-local' name='schedule' required>
                    </div>
                    <div class="right">
                        <label class="label">Leave a Message (Optional)</label>
                        <textarea class="text-box" name='message' rows='6' placeholder="[Interview Platform]"></textarea>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>

<script>
    const formSubmitBtn = document.querySelector('#formSubmit');
    const meetScheduleForm = document.querySelector('#meetScheduleForm');

    formSubmitBtn.addEventListener('click', function(event) {
        // Check if all required fields are filled
        if (meetScheduleForm.checkValidity()) {
            // Display a confirmation dialog
            const confirmation = confirm('Are you sure you want to submit the form?');

            // If user confirms, submit the form
            if (confirmation) {
                meetScheduleForm.submit(); // Submit the form
            }
        } else {
            // If required fields are not filled, show a message or perform any other action
            alert('Please fill out all required fields before submitting.');
        }
    });

</script>