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

    //Fetch all the users except for an admin account
    $workers = $workerObj -> getWorkersByConditions(null, null, 'Verified', 'Pending', null, null, null, null, null);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['idUser'])) {
            $workers = $workerObj -> getWorkersByConditions(null, null, 'Verified', 'Pending', null, null, null, null, $_POST['idUser']);
        } 
        if (isset($_POST['qualified-btn'])) {
            $workerObj -> updateWorker($_POST['idWorker'], null, null, null, 'Qualified', null, null, null, null, null, null);
            header('Location: ./qualify_workers.php');
            exit();
        }
        if (isset($_POST['not-qualified-btn'])) {
            $workerObj -> updateWorker($_POST['idWorker'], null, null, null, 'Not Qualified', null, null, null, null, null, null);
            header('Location: ./qualify_workers.php');
            exit();
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
    <link rel="stylesheet" href="../styles/includes.css">
    <link rel="stylesheet" href="../styles/media_query.css">
    <link rel="stylesheet" href="../styles/default.css">

    <link rel="stylesheet" href="../styles/admin_styles/user_accounts.css">
    <link rel="stylesheet" href="../styles/admin_styles/default.css">

    <!-- JavaScript -->
    <script src='../scripts/worker.js' defer></script>
    <script src='../scripts/jquery-3.7.1.min.js' defer></script>

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
                        <a href='./dashboard.php' class='c-light'>DASHBOARD</a>
                        <a href='./employer_requests.php' class='c-light'>EMPLOYER REQUESTS</a>
                        <a href='./contract_manager.php' class='c-light'>CONTRACT MANAGER</a>
                        <a href='./payment.php' class='c-light'>PAYMENT</a>
                        <a href='./user_accounts.php' class='c-light fw-bold'>USER ACCOUNTS</a>
                    </nav>
                    <nav>
                        <a href='../logout.php' class='c-light'>LOG OUT</a>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <main class='admin'>
        <div class='container application'>
            <div class='content'>
                <div class='title border-bottom'>
                    <div class="left">
                        <img class='user-profile' src='../img/groups-people.png' placeholder='groups-icon'>
                        <h3>User Accounts</h3>  
                    </div>
                    <div class="right">
                        <a class='nav display-users' href='./user_accounts.php'>
                            Display Users
                        </a>
                        <a class='nav verify-users' href='./verify_users.php'>
                            Verify Users
                        </a>
                        <a class='nav verify-users fw-bold' href='./qualify_workers.php'>
                            Qualify Workers
                        </a>
                    </div> 
                </div>
                
                <div class='info'>
                    <form class="search-contract flex-row" action='./qualify_workers.php' method='POST'>
                        <input type="number" name='idUser' class='text-box' placeholder='Search by User ID'>
                        <button type='submit' class='label' name='submit' value='submit'><img class='search-icon' src='../img/search-icon.png' alt='Search'></button>
                    </form>
                    <div class='table-result user-accounts <?php echo ($workers != false ? '' : 'hidden') ?>'>
                        <table>
                            <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>Profile</th>
                                    <th>Name</th>
                                    <th>Sex</th>
                                    <th>Email</th>
                                    <th>Verification Status</th>
                                    <th>Qualification Status</th>
                                    <th>Approve</th>
                                    <th>Decline</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($workers != false) {
                                    foreach($workers as $worker) {
                                        if (isset($worker['profilePic'])) {
                                            $profile = getImageSrc($worker['profilePic']);
                                        } else {
                                            $profile = '../img/user-icon.png';
                                        }
                                        
                                        if (isset($worker['verifyStatus'])) {
                                            $verifyStaus = $worker['verifyStatus'];
                                        } else {
                                            $verifyStaus = 'Not Verified';
                                        }

                                        if (isset($worker['qualification_status'])) {
                                            $qualification_status = $worker['qualification_status'];
                                        } else {
                                            $qualification_status = 'Pending';
                                            if ($worker['userType'] == 'Employer') {
                                                $qualification_status = 'N/A';
                                            }
                                        }

                                        $workerDetails = $userObj -> getUserById($worker['idUser']);

                                        echo "<tr>
                                                <td class='t-align-center'>". $workerDetails['idUser']."</td>
                                                <td class='t-align-center image-preview' ><img src='". $profile ."' alt='profile'></td>
                                                <td>".$workerDetails['fname'] . " " . $workerDetails['lname']."</td>
                                                <td>".$workerDetails['sex']."</td>
                                                <td>".$workerDetails['email']."</td>
                                                <td>".$verifyStaus."</td>
                                                <td>".$qualification_status."</td>
                                                <td class='t-align-center view-btn'>
                                                    <form action='' method='POST'>
                                                        <input type='hidden' name='idUser' value=" .$workerDetails['idUser'] .">
                                                        <input type='hidden' name='idWorker' value=" .$worker['idWorker'] .">
                                                        <button type='submit' name='qualified-btn' class='green-white-btn'>Qualified</button>
                                                    </form>
                                                </td>
                                                <td class='t-align-center'>
                                                    <form action='' method='POST' id='deleteForm'>
                                                        <input type='hidden' name='idUser' value=" .$workerDetails['idUser'] .">
                                                        <input type='hidden' name='idWorker' value=" .$worker['idWorker'] .">
                                                        <button type='submit' name='not-qualified-btn' class='red-white-btn'>Not Qualified</button>
                                                    </form>
                                                </td>
                                            </tr>";
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div class='no-record-label <?php echo ($workers != false ? 'hidden' : '') ?>'>
                        <p>There are no found record!</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>