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
        // var_dump($_POST);
        // exit();
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
        $candidateWorkers = searchCandidateWorkers($workerType, $sex, $age, $height, $yearsOfExperience);

    }


    // Check if the confirm button is clicked
    if (isset($_POST['confirm'])) {
        // Get preferences from the form
        $interviewPlatform = $_POST['interview_platform'];
        $preferredSex = $_POST['sex_preferred'];
        $preferredAge = $_POST['preferred_age'];
        $preferredHeight = $_POST['preferred_height'];
        $preferredExperience = $_POST['preferred_experience'];

        // Build the SQL query to fetch a random worker based on preferences
        $query = "SELECT user.fname, user.sex, worker.workerType, worker.height, worker.yearsOfExperience
                FROM user
                JOIN worker ON user.idUser = worker.idUser
                ORDER BY RAND()
                LIMIT 1";

        // Use prepared statement to prevent SQL injection
        $stmt = $your_db_connection->prepare($query);
        $stmt->bind_param("ssisiiii", $interviewPlatform, $preferredSex, $preferredSex, $preferredAge, $preferredAge, $preferredHeight, $preferredHeight, $preferredExperience, $preferredExperience);
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch the result as an associative array
        $workerData = $result->fetch_assoc();

        // Close the statement
        $stmt->close();

        // Display the fetched worker information
        // You can use the displayWorker() JavaScript function from the previous example
        echo "<script>displayWorker(" . json_encode($workerData) . ");</script>";
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
                        <button class='find-worker-btn'>CONFIRM</button>
                        <button class='find-another-worker-btn hidden'>FIND ANOTHER</button>
                        <button class='cancel-btn hidden'>CANCEL</button>
                        <button class='hire-worker-btn hidden'>HIRE WORKER</button>
                        <button class='interview-submit-btn hidden'>CONFIRM</button>
                    </div> 
                </div>
                <form class='find-worker-form info' action='./find_a_worker.php' method='POST'>
                     <div class='left'>
                        <label class='label'>Worker Type</label>
                        <select class='text-box' name='workerType-preferred'>
                            <option value='nanny'>Nanny</option>
                            <option value='maid'>Maid</option>
                            <option value='gardener'>Gardener</option>
                            <option value='cook'>Cook</option>
                            <option value='driver'>Driver</option>
                        </select>
                        <label class='label'>Preferred Sex (Optional)</label>
                        <select class='text-box' name='sex-preferred'>
                            <option value='unset'>Unset</option>
                            <option value='Male'>Male</option>
                            <option value='Female'>Female</option>
                        </select>
                        <label class='label'>Preferred Age (Optional)</label>
                        <input class='text-box' name='age-preferred' type='number' min=0 max=100>
                     </div>

                     <div class='right'>
                         <label class='label'>Preferred Height (Optional)</label>
                         <input class='text-box' name='height-preferred' type='number' placeholder="(cm)" min=0 max=1000>
                         <label class='label'>Preferred Years of Experience (Optional)</label>
                         <input class='text-box' name='yearsOfExperience-preferred' type='number' min=0 max=100>
                    </div>
                </form>

                <!-- Display the Worker Info -->
                <div class='info flex-column flex-center hidden'>
                    <div class='top flex-1'>
                        <div class='profile-image image-preview'>
                            <img src='../img/user-icon.png' alt='user-img'>
                            <P class='f-italic fs-small t-align-center'>Profile Image</P>
                        </div>
                        <div class='curriculum-vitae image-preview'>
                            <img src='../img/document-sample.jpg' alt='cv-img'>
                            <P class='f-italic fs-small t-align-center'>Curriculum Vitae</P>
                        </div>
                        <div class='certificates image-preview'>
                            <img src='../img/document-sample.jpg' alt='certificate-img'>
                            <P class='f-italic fs-small t-align-center'>Certificate</P>
                        </div>
                    </div>
                    <div class=' flex-1'>
                        <div class="left">
                            <p class="label">Worker Name</p>
                            <p class="text-box">[Worker Name]</p>
                            <p class="label">Age</p>
                            <p class="text-box">[Age]</p>
                            <p class="label">Sex</p>
                            <p class="text-box">[Sex]</p>
                        </div>
                        <div class="right">
                            <p class="label">Worker Type</p>
                            <p class="text-box">[Worker Type]</p>
                            <p class="label">Height</p>
                            <p class="text-box">[Height]</p>
                            <p class="label">Years of Experience</p>
                            <p class="text-box">[Years of Experience]</p>
                        </div>
                    </div>
                </div>

                <form class="info hidden" action='' method='POST'> 
                    <div class="left">
                        <label class="label">Interview Platform</label>
                        <input class="text-box" name='platform' type='text' placeholder="[Interview Platform]">
                        <label class="label">Interview Link</label>
                        <input class="text-box" name='link' type='text' placeholder="[Interview Link]">
                        <label class="label">Interview Schedule</label>
                        <input class="text-box" type='date' name='schedule'>
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