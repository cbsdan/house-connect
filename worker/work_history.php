<?php
    //Check first if the user is logged in
    include_once('../functions/user_authenticate.php');
    include_once('../database/connect.php');

    if ($_SESSION['userType'] == 'Employer') {
        header('Location: ../employer/account_profile.php');
        exit();
    }
    if ($_SESSION['userType'] == 'Admin') {
        header('Location: ../admin/dashboard.php');
        exit();
    }
    
    $sql = "SELECT contract.*, userEmployer.fname AS employer_fname, userEmployer.lname AS employer_lname, 
                userWorker.fname AS worker_fname, userWorker.lname AS worker_lname
            FROM contract 
            LEFT JOIN employer ON contract.idEmployer = employer.idEmployer 
            LEFT JOIN user AS userEmployer ON employer.idUser = userEmployer.idUser
            LEFT JOIN worker ON worker.idWorker = contract.idWorker
            LEFT JOIN user AS userWorker ON worker.idUser = userWorker.idUser
            WHERE userWorker.idUser = " .$_SESSION['idUser'];

    $result = mysqli_query($conn, $sql);

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
            <div class='bottom' id='nav-worker'>
                <div class='navigation-container'>
                    <nav >
                        <a href='./current_employer.php' class='c-light'>CURRENT EMPLOYER</a>
                        <a href='./work_history.php' class='c-light fw-bold'>WORK HISTORY</a>
                        <a href='./salary_payment.php' class='c-light'>SALARY PAYMENT</a>
                        <a href='./account_profile.php' class='c-light'>ACCOUNT PROFILE</a>
                    </nav>
                    <nav>
                        <a href='../logout.php' class='c-light'>LOG OUT</a>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <main class='worker'>
        <div class='container application'>
            <div class='content'>
                <div class='title'>
                    <img src='../img/work-icon.png'>
                    <h3>Work History</h3>   
                </div>
                <div class='info'>
                    <table class='<?php echo ($result->num_rows == 0 ? 'hidden' : '');?>'>
                        <thead>
                            <tr>
                                <th>Contract ID</th>
                                <th>Status</th>
                                <th>Employer</th>
                                <th>Start of Contract</th>
                                <th>End of Contract</th>
                                <th>Salary Amount</th>
                                <th>Date Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                while ($row = mysqli_fetch_array($result)) {
                                    $contractStatus = $row['contractStatus'];
                                    $startDate = $row['startDate'];
                                    $endDate = $row['endDate'];
                                    $salaryAmt = $row['salaryAmt'];
                                    $employerName = $row['employer_fname'] . " " . $row['employer_lname'];
                                    $date_created = $row['date_created'];
                                
                                    echo 
                                    "<tr>
                                        <td class='t-align-center'>".$row['idContract']."</td>
                                        <td>$contractStatus</td>
                                        <td>$employerName</td>
                                        <td>" . (isset($startDate) ? $startDate : 'N/A') . "</td>
                                        <td>" . (isset($endDate) ? $endDate : 'N/A') . "</td>
                                        <td>" . (isset($salaryAmt) ? "₱$salaryAmt" : 'N/A') . "</td>
                                        <td>$date_created</td>
                                    </tr>";
                                }
                            ?>
                        </tbody>
                    </table>

                    <!-- If there is no previous work -->
                    <div class='no-record-label <?php echo ($result->num_rows > 0 ? 'hidden' : '');?>'>
                        <p>You have no previous work in the system!</p>
                    </div>
                </div>
            </div>
        </div>
    </main>


</body>
</html>
