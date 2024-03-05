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

    $contracts = getContractList();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['idContract'])) {
            $contracts = getContractList($_POST['idContract']);
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

    <link rel="stylesheet" href="../styles/admin_styles/contract_manager.css">
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
                        <a href='./dashboard.php' class='c-light'>DASHBOARD</a>
                        <a href='./contract_manager.php' class='c-light fw-bold'>CONTRACT MANAGER</a>
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
                    <img class='user-profile' src='../img/documents-icon.png' placeholder='contract-icon'>
                    <h3>Contract Manager</h3>  
                </div>
                <div class='info'>
                <form class="search-contract flex-row <?php echo (!isset($contracts) ? 'hidden' : '');?>" action='contract_manager.php' method='POST'>
                    <input type="number" name='idContract' class='text-box' placeholder='Search by Contract ID'>
                    <button type='submit' class='label' name='submit' value='submit'><img class='search-icon' src='../img/search-icon.png' alt='Search'></button>
                </form>
                <div class='table-container'>
                    <table class='<?php echo (!isset($contracts) ? 'hidden' : '');?>'>
                        <thead>
                            <tr>
                                <th>Contract ID</th>
                                <th>Status</th>
                                <th>Employer Name</th>
                                <th>Worker Name</th>
                                <th>Worker Type</th>
                                <th>Date Created</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                if (isset($contracts)){
                                    foreach($contracts as $contract) {
                                        echo "
                                            <tr>
                                            <td class='t-align-center'>" . $contract['idContract'] . "</td>
                                                <td>" . $contract['contractStatus'] . "</td>
                                                <td>" . $contract['employerFname'] . " " . $contract['employerLname'] . "</td>
                                                <td>" . $contract['workerFname'] . " " . $contract['workerLname'] . "</td>
                                                <td class='t-align-center'>" . $contract['workerType'] . "</td>
                                                <td>" . $contract['date_created'] . "</td>
                                                <td class='t-align-center'>
                                                    <form action='./contract_info.php' method='POST' class='open-detail-preview '>
                                                        <input type='hidden' name='idContract' value='" . $contract['idContract'] . "'>
                                                        <button type='submit' class='c-yellow details'>[Details]</button>
                                                    </form>
                                                </td>  
                                            </tr>
                                        ";
                                    }
                                }
                                ?>
                        </tbody>
                    </table>
                    <div class='no-record-label <?php echo (isset($contracts) ? 'hidden' : '');?>'>
                        <p>There are no found record!</p>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>