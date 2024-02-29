<?php
    //Check first if the user is logged in
    include_once('../functions/user_authenticate.php');
    include_once('../database/connect.php');

    if ($_SESSION['userType'] == 'Worker') {
        header('Location: ../worker/application.php');
        exit();
    }
    if ($_SESSION['userType'] == 'Employer') {
        header('Location: ../employer/account_profile.php');
        exit();
    }

    
    $sql = "SELECT u.idUser, u.fname, u.lname, u.sex, 
                TIMESTAMPDIFF(YEAR, u.birthdate, CURDATE()) AS age, 
                u.email, u.userType,
                CASE 
                    WHEN u.userType = 'Worker' THEN w.profilePic
                    WHEN u.userType = 'Employer' THEN e.profilePic
                    ELSE NULL
                END AS profile,
                CASE 
                    WHEN u.userType = 'Worker' THEN w.verifyStatus
                    WHEN u.userType = 'Employer' THEN e.verifyStatus
                    ELSE NULL
                END AS verifyStatus
            FROM user AS u
            LEFT JOIN worker AS w ON u.idUser = w.idUser
            LEFT JOIN employer AS e ON u.idUser = e.idUser
            WHERE (u.userType = 'Worker' OR u.userType = 'Employer') 
                AND ((u.userType = 'Worker' AND w.verifyStatus = 'Not Verified') 
                  OR (u.userType = 'Employer' AND e.verifyStatus = 'Not Verified'));";

    $result = $conn -> query($sql);
    
    if ($result) {
        if ($result->num_rows > 0) {
            $users = array(); // Initialize an array to store fetched rows
            while ($row = $result->fetch_assoc()) {
                $users[] = $row; // Append each row to the array
            }
        } else {
            echo "No rows found";
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>


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
                <div class='title'>
                    <div class="left">
                        <img class='user-profile' src='../img/groups-people.png' placeholder='groups-icon'>
                        <h3>User Accounts</h3>  
                    </div>
                    <div class="right">
                        <a class='nav display-users' href='./user_accounts.php'>
                            Display Users
                        </a>
                        <a class='nav verify-users fw-bold' href='./verify_users.php'>
                            Verify Users
                        </a>
                    </div>
                </div>
                <div class='info'>
                    <div class='table-result verify-users <?php echo (isset($users) ? '' : 'hidden') ?>'>
                        <table>
                            <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>Profile</th>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Sex</th>
                                    <th>Email</th>
                                    <th>User Type</th>
                                    <th>View</th>
                                    <th>Approve</th>
                                    <th>Decline</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($users as $user) {
                                        if (isset($user['profilePic'])) {
                                            $profile = getImageSrc($user['profilePic']);
                                        } else {
                                            $profile = '../img/user-icon.png';
                                        }

                                        echo "<tr>
                                                <td>". $user['idUser']."</td>
                                                <td class='t-align-center' ><img src='". $profile ."' alt='profile'></td>
                                                <td>".$user['fname'] . " " . $user['lname']."</td>
                                                <td>".$user['age']."</td>
                                                <td>".$user['sex']."</td>
                                                <td>".$user['email']."</td>
                                                <td class='t-align-center'>".$user['userType']."</td>
                                                <td class='t-align-center c-yellow view-btn'>[View]<span class='idUser'></span></td>
                                                <td class='t-align-center'><button class='green-white-btn'>Approve</button></td>
                                                <td class='t-align-center'><button class='red-white-btn'>Decline</button></td>
                                            </tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div class='no-record-label <?php echo (isset($users) ? 'hidden' : '') ?>'>
                        <p>There are no found record!</p>
                    </div>

                    <div class='detail-preview hidden user-info'>
                        <div class="preview">
                            <div class="detail">
                                <div class="title">
                                    <h3>Personal Information</h3>
                                </div>
                                <div class="info">
                                    <div class="left">
                                        <div class="data">
                                            <h4 class="label">User ID</h4>
                                            <p class="text-box">001</p>
                                        </div>
                                        <div class="data">
                                            <h4 class="label">Profile</h4>
                                            <p class="text-box"><img src='../img/user-icon.png'></p>
                                        </div>
                                        <div class="data">
                                            <h4 class="label">Profile</h4>
                                            <input class='file-upload' type='file'>
                                        </div>
                                        <div class="data">
                                            <h4 class="label">Email</h4>
                                            <input class="text-box" type='email' name='email' value='example_account@gmail.com'>
                                        </div>
                                        <div class="data">
                                            <h4 class="label">Password</h4>
                                            <input class="text-box" name='password' value='example_account@gmail.com'>
                                        </div>
                                        <div class="data">
                                            <h4 class="label">First Name</h4>
                                            <input class="text-box" type='text' name='fname' value='Daniel'>
                                        </div>
                                        <div class="data">
                                            <h4 class="label">Last Name</h4>
                                            <input class="text-box" type='text' name='lname' value='Cabasa'>
                                        </div>
                                        <div class="data">
                                            <h4 class="label">Sex</h4>
                                            <select class="text-box" type='text' name='sex' value='Male'>
                                                <option value="Male">Male</option>
                                                <option value="Male">Female</option>
                                            </select>
                                        </div>
                                        <div class="data">
                                            <h4 class="label">Birthdate</h4>
                                            <input class="text-box" type='date' name='birthdate'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>