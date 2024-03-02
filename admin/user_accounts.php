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

    //Fetch all the users except for an admin account
    $sql = "SELECT u.idUser, u.fname, u.lname, u.sex, 
                TIMESTAMPDIFF(YEAR, u.birthdate, CURDATE()) AS age, 
                u.email, u.userType,
                CASE 
                    WHEN u.userType = 'Worker' THEN w.profilePic
                    WHEN u.userType = 'Employer' THEN e.profilePic
                    ELSE NULL
                END AS profilePic,
                CASE 
                    WHEN u.userType = 'Worker' THEN w.verifyStatus
                    WHEN u.userType = 'Employer' THEN e.verifyStatus
                    ELSE NULL
                END AS verifyStatus
            FROM user AS u
            LEFT JOIN worker AS w ON u.idUser = w.idUser
            LEFT JOIN employer AS e ON u.idUser = e.idUser
            WHERE u.userType = 'Worker' OR u.userType = 'Employer'
            ORDER BY u.idUser;";

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
                        <a class='nav display-users fw-bold' href='./user_accounts.php'>
                            Display Users
                        </a>
                        <a class='nav verify-users' href='./verify_users.php'>
                            Verify Users
                        </a>
                    </div> 
                </div>
                <div class='info'>
                    <div class='table-result user-accounts <?php echo (isset($users) ? '' : 'hidden') ?>'>
                        <table>
                            <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>Profile</th>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Sex</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>User Type</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
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
                                        
                                        if (isset($user['verifyStatus'])) {
                                            $verifyStaus = $user['verifyStatus'];
                                        } else {
                                            $verifyStaus = 'Not Verified';
                                        }

                                        echo "<tr>
                                                <td class='t-align-center'>". $user['idUser']."</td>
                                                <td class='t-align-center' ><img src='". $profile ."' alt='profile'></td>
                                                <td>".$user['fname'] . " " . $user['lname']."</td>
                                                <td class='t-align-center'>".$user['age']."</td>
                                                <td>".$user['sex']."</td>
                                                <td>".$user['email']."</td>
                                                <td>".$verifyStaus."</td>
                                                <td class='t-align-center'>".$user['userType']."</td>
                                                <td class='t-align-center view-btn'>
                                                    <form action='./edit-user-account.php' method='POST'>
                                                        <input type='hidden' name='idUser' value=" .$user['idUser'] .">
                                                        <input type='hidden' name='userType' value=" .$user['userType'] .">
                                                        <button type='submit' class='c-yellow'>[Edit]</button>
                                                    </form>
                                                </td>
                                                <td class='t-align-center'>
                                                    <form action='../database/delete_user_account.php' method='POST' id='deleteForm' onsubmit='return confirmDelete()'>
                                                        <input type='hidden' name='idUser' value=" .$user['idUser'] .">
                                                        <button type='submit' class='c-red'>[Delete]</button>
                                                    </form>
                                                </td>
                                            </tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div class='no-record-label <?php echo (isset($users) ? 'hidden' : '') ?>'>
                        <p>There are no found record!</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>