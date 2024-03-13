<?php
    //Check first if the user is logged in
    include_once('../database/connect.php');

    if ($_SESSION['userType'] == 'Worker') {
        header('Location: ../worker/application.php');
        exit();
    }
    if ($_SESSION['userType'] == 'Admin') {
        header('Location: ../admin/dashboard.php');
        exit();
    }

    $userData = fetchEmployerData($_SESSION['idUser']);
    if ($userData['verifyStatus'] == 'Not Verified') {
        header('Location: ./account_profile.php');
        exit();
    }
    
    $employer = $employerObj -> getEmployerByConditions(["idUser" => $_SESSION['idUser']]);
    // var_dump($employer);
    // exit();
    if ($employer != false) {
        $employer = $employer[0];
        $employerRequests = $employerRequestsObj -> getEmployerRequestByConditions(['employer_idEmployer' => $employer['idEmployer'], "status" => "Pending"]);
    }

    // Check if the confirm button is clicked
    if (isset($_POST['confirm'])) {
        // Get preferences from the form
        $workerType = $_POST['workerType-preferred'];
        $preferredSex = $_POST['sex-preferred'] ?? null;
        $preferredAge = $_POST['age-preferred'] ?? null;
        $preferredHeight = $_POST['height-preferred'] ?? null;
        $preferredExperience = $_POST['yearsOfExperience-preferred'] ?? null;
        $additionalMessage = $_POST['additionalMessage'] ?? null;

        $employerRequestsObj->createEmployerRequest($workerType, 'Pending', date('Y-m-d'), $employer['idEmployer'], null, $preferredAge, $preferredSex, $preferredHeight, $preferredExperience, $additionalMessage);
        header('Location: ./request_a_worker.php');
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
                    <nav >
                        <a href='./request_a_worker.php' class='c-light fw-bold'>REQUEST A WORKER</a>
                        <a href='./worker_requests.php' class='c-light fw-bold'>WORKER REQUESTS</a>
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
                <div class='request-label'>
                    <p>You have <span class='c-yellow'><?php echo ($employerRequests != false ? count($employerRequests) : 0); ?> pending </span> requests. See more <a href='./worker_request.php' class='t-underline c-light'>here</a></p>
                </div>
                <div class='title'>
                    <div class="left">
                        <img class='user-profile' src='../img/search-icon.png' placeholder='profile'>
                        <h3>Find a Worker</h3>  
                    </div>
                    <div class='right'>
                        <button class='find-worker-btn'>CONFIRM</button>
                    </div> 
                </div>
                <form class='find-worker-form info' action='' method='POST'>
                    <input class='hidden' name='confirm' value='1'>
                     <div class='left'>
                        <label class='label'>Worker Type</label>
                        <select class='text-box' name='workerType-preferred'>
                            <option value='Nanny'>Nanny</option>
                            <option value='Maid'>Maid</option>
                            <option value='Gardener'>Gardener</option>
                            <option value='Cook'>Cook</option>
                            <option value='Driver'>Driver</option>
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
                         <label class='label'>Message Request (Optional)</label>
                         <textarea class='text-box' name='additionalMessage' placeholder="type your message here"></textarea>
                    </div>
                </form>
                
            </div>
        </div>
    </main>
</body>
</html>