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

    $payments= getAllEmployerPayments(null, null, null);

    if (isset($_POST['idEmployerPayment']) || isset($_POST['paymentStatus'])) {
        $idEmployerPayment = $_POST['idEmployerPayment'] ?? null;
        $paymentStatus = $_POST['paymentStatus'] ?? null;
        if ($idEmployerPayment == '') {
            $idEmployerPayment = null;
        }
        if ($paymentStatus == 'All' || $paymentStatus == '') {
            $paymentStatus = null;
        }
        $payments= getAllEmployerPayments($idEmployerPayment, null, $paymentStatus);
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

    <link rel="stylesheet" href="../styles/admin_styles/payment.css">
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
                        <a href='./employer_requests.php' class='c-light'>EMPLOYER REQUESTS</a>
                        <a href='./contract_manager.php' class='c-light'>CONTRACT MANAGER</a>
                        <a href='./payment.php' class='c-light fw-bold'>PAYMENT</a>
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
                    <img class='user-profile' src='../img/wallet-icon.png' placeholder='wallet-icon'>
                    <h3>Payment</h3>  
                </div>
                <div class='info'>
                    <form class="search-contract flex-row" action='./payment.php' method='POST'>
                        <input type="number" name='idEmployerPayment' class='text-box' placeholder='Search by Payment ID'>
                        <select name='paymentStatus'>
                        <option value='All' selected>All</option>
                        <option value='Pending'>Pending</option>
                        <option value='Successful'>Successful</option>
                        <option value='Failed'>Failed</option>
                        </select>
                        <button type='submit' class='label' name='submit' value='submit'><img class='search-icon' src='../img/search-icon.png' alt='Search'></button>
                    </form>
                    <div class='table-result employer-payments <?php echo (isset($payments) ? '' : 'hidden') ?>'>
                        <table>
                            <thead>
                                <tr>
                                    <th>Payment ID</th>
                                    <th>Employer Name</th>
                                    <th>Worker Name</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Receipt Image</th>
                                    <th>Status</th>
                                    <th>Contract ID</th>
                                    <th>Submitted At</th>
                                    <th>[View]</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    if (isset($payments)) {
                                        foreach($payments as $payment) {
                                            echo "
                                                <tr>
                                                    <td class='t-align-center'>".$payment['idEmployerPayment']."</td>
                                                    <td>".$payment['employerFname']. " " . $payment['employerLname'] ."</td>
                                                    <td>".$payment['workerFname']. " " . $payment['workerLname']."</td>
                                                    <td>₱".$payment['employerPaymentAmount']."</td>
                                                    <td>".$payment['employerPaymentMethod']."</td>
                                                    <td class='image-preview t-align-center'><img src='".(isset($payment['imgReceipt']) ? getImageSrc($payment['imgReceipt']) : '../img/document-sample.jpg') ."' alt='Receipt''></td>
                                                    <td>".$payment['employerPaymentStatus']."</td>
                                                    <td class='t-align-center'>".$payment['idContract']."</td>
                                                    <td>".$payment['submitted_at']."</td>
                                                    <td class='t-align-center'>
                                                        <form class='' action='./payment_info.php' method='POST'>
                                                            <input type='hidden' name='idEmployerPayment' value='".$payment['idEmployerPayment']."'>
                                                            <button type='submit' name='submit' value='submit' class='c-yellow'>[View]</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            ";
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class='no-record-label <?php echo (isset($payments) ? 'hidden' : '') ?>'>
                        <p>There are no found record!</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>