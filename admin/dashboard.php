<?php
    require_once ('../database/connect.php');
    include_once('../functions/user_authenticate.php');

    if ($_SESSION['userType'] == 'Worker') {
        header('Location: ../worker/application.php');
        exit();
    }
    if ($_SESSION['userType'] == 'Employer') {
        header('Location: ../employer/account_profile.php');
        exit();
    };
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

    <link rel="stylesheet" href="../styles/admin_styles/dashboard.css">
    <link rel="stylesheet" href="../styles/admin_styles/default.css">

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
                    <nav>
                        <a href='./dashboard.php' class='c-light fw-bold'>DASHBOARD</a>
                        <a href='./contract_manager.php' class='c-light'>CONTRACT MANAGER</a>
                        <a href='./payment.php' class='c-light'>PAYMENT</a>
                        <a href='./user_accounts.php' class='c-light'>USER ACCOUNTS</a>
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
                    <img class='user-profile' src='../img/dashboard-icon.png' placeholder='dashboard-icon'>
                    <h3>Dashboard</h3>  
                </div>
                <div class='info'>
                    <div class="details">
                    <?php
                        function displayRevenueSummary($conn) {
                            
                            $sql = "SELECT SUM(CASE WHEN paymentStatus = 'Successful' THEN amount * 0.9 ELSE amount END) AS revenue
                                    FROM employer_payment";
                            $result = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_assoc($result);
                            $revenue = $row['revenue'];
                            
                            echo "<div class='details'>";
                            echo "<h3 class='title'>Summary Report of Revenue</h3>";
                            
                            echo "<div class='data'>";
                            echo "<p class='label'>Daily</p>";
                            echo "<p class='value'>P" . number_format($revenue / 365, 2) . "</p>";
                            echo "</div>";
                            
                            echo "<div class='data'>";
                            echo "<p class='label'>Weekly</p>";
                            echo "<p class='value'>P" . number_format($revenue / 52, 2) . "</p>";
                            echo "</div>";
                            
                            echo "<div class='data'>";
                            echo "<p class='label'>Monthly</p>";
                            echo "<p class='value'>P" . number_format($revenue / 12, 2) . "</p>";
                            echo "</div>";
                            
                            echo "<div class='data'>";
                            echo "<p class='label'>Yearly</p>";
                            echo "<p class='value'>P" . number_format($revenue, 2) . "</p>";
                            echo "</div>";
                            
                            echo "</div>";
                        }
                        displayRevenueSummary($conn);
                        ?>
                    </div>
                    <?php
                        function displayWorkerTypes($conn) {
                            $sql = "SELECT workerType, COUNT(*) AS typeCount
                                    FROM worker
                                    GROUP BY workerType";
                            $result = mysqli_query($conn, $sql);
                            
                            echo "<div class='details'>";
                            echo "<h3 class='title'>Workers</h3>";
                            
                            $totalWorkers = 0;
                            
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<div class='data'>";
                                echo "<p class='label'>" . $row['workerType'] . "</p>";
                                echo "<p class='value'>" . $row['typeCount'] . "</p>";
                                echo "</div>";
                                
                                $totalWorkers += $row['typeCount'];
                            }
                            
                            echo "<div class='data'>";
                            echo "<p class='label'>Total</p>";
                            echo "<p class='value'>$totalWorkers</p>";
                            echo "</div>";
                            
                            echo "</div>"; 
                        }
                        displayWorkerTypes($conn);
                        ?>

                    <div class="details">
                    <?php
                        function displayContractStatus($conn) {

                            $sql = "SELECT COUNT(*) AS totalContracts,
                                        SUM(CASE WHEN contractStatus = 'Hired' THEN 1 ELSE 0 END) AS activeContracts,
                                        SUM(CASE WHEN contractStatus = 'Pending' THEN 1 ELSE 0 END) AS pendingContracts,
                                        SUM(CASE WHEN contractStatus = 'Completed' THEN 1 ELSE 0 END) AS completedContracts,
                                        SUM(CASE WHEN contractStatus = 'Canceled' THEN 1 ELSE 0 END) AS canceledContracts
                                    FROM contract";
                            $result = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_assoc($result);
                            
                            echo "<div class='info'>";
                            echo "<div class='details'>";
                            echo "<h3 class='title'>Contracts</h3>";
                            
                            echo "<div class='data'>";
                            echo "<p class='label'>Total</p>";
                            echo "<p class='value'>" . $row['totalContracts'] . "</p>";
                            echo "</div>";
                            
                            echo "<div class='data'>";
                            echo "<p class='label'>Hired</p>";
                            echo "<p class='value'>" . $row['activeContracts'] . "</p>";
                            echo "</div>";
                            
                            echo "<div class='data'>";
                            echo "<p class='label'>Pending</p>";
                            echo "<p class='value'>" . $row['pendingContracts'] . "</p>";
                            echo "</div>";
                            
                            echo "<div class='data'>";
                            echo "<p class='label'>Completed</p>";
                            echo "<p class='value'>" . $row['completedContracts'] . "</p>";
                            echo "</div>";
                            
                            echo "<div class='data'>";
                            echo "<p class='label'>Canceled</p>";
                            echo "<p class='value'>" . $row['canceledContracts'] . "</p>";
                            echo "</div>";
                            
                            echo "</div>";
                            echo "</div>";
                        }
                        displayContractStatus($conn);
                        ?>
                    </div>
                    <div class="details">
                    <?php
                        function displayVerificationStatus($conn) {
                            
                            $sqlWorkers = "SELECT COUNT(*) AS totalWorkers, 
                                                SUM(CASE WHEN w.verifyStatus = 'Verified' THEN 1 ELSE 0 END) AS verifiedWorkers,
                                                SUM(CASE WHEN w.verifyStatus != 'Verified' THEN 1 ELSE 0 END) AS nonVerifiedWorkers
                                        FROM worker w";
                            $resultWorkers = mysqli_query($conn, $sqlWorkers);
                            $rowWorkers = mysqli_fetch_assoc($resultWorkers);
                            
                            $sqlEmployers = "SELECT COUNT(*) AS totalEmployers, 
                                                SUM(CASE WHEN e.verifyStatus = 'Verified' THEN 1 ELSE 0 END) AS verifiedEmployers,
                                                SUM(CASE WHEN e.verifyStatus != 'Verified' THEN 1 ELSE 0 END) AS nonVerifiedEmployers
                                            FROM employer e";
                            $resultEmployers = mysqli_query($conn, $sqlEmployers);
                            $rowEmployers = mysqli_fetch_assoc($resultEmployers);
                            
                            echo "<div class='info'>";
                            echo "<div class='details'>";
                            echo "<h3 class='title'>Users</h3>";
                            
                            $totalUsers = $rowWorkers['totalWorkers'] + $rowEmployers['totalEmployers'];
                            echo "<div class='data'>";
                            echo "<p class='label'>Total</p>";
                            echo "<p class='value'>$totalUsers</p>";
                            echo "</div>";
                            
                            echo "<div class='data'>";
                            echo "<p class='label'>Verified Workers</p>";
                            echo "<p class='value'>" . $rowWorkers['verifiedWorkers'] . "</p>";
                            echo "</div>";
                            
                            echo "<div class='data'>";
                            echo "<p class='label'>Non-verified Users</p>";
                            echo "<p class='value'>" . $rowWorkers['nonVerifiedWorkers'] . "</p>";
                            echo "</div>";
                            
                            echo "<div class='data'>";
                            echo "<p class='label'>Verified Employers</p>";
                            echo "<p class='value'>" . $rowEmployers['verifiedEmployers'] . "</p>";
                            echo "</div>";
                            
                            echo "<div class='data'>";
                            echo "<p class='label'>Non-verified Employers</p>";
                            echo "<p class='value'>" . $rowEmployers['nonVerifiedEmployers'] . "</p>";
                            echo "</div>";
                            
                            echo "</div>";
                            echo "</div>";
                        }
                        displayVerificationStatus($conn);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>