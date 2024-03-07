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
    
    if (isset($_POST['idContract'])) {
        $paymentInfo = getAllEmployerPayments(null, $_POST['idContract']);
    } else {
        header('Location: ../admin/contract_manager.php');
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

    <link rel="stylesheet" href="../styles/admin_styles/default.css">

    <!-- JavaScript -->
    <script src='../scripts/worker.js' defer></script>
    <script src='../scripts/jquery-3.7.1.min.js' defer></script>

</head>
<body>
    <header class='logged-in'>
        <div class="content">
            <button class='orange-white-btn'><a href='./contract_manager.php' class='c-light'>Back to Contract Manager</a></button>
        </div>
    </header>

    <main class='admin edit-user'>
        <div class='container application'>
            <div class='content'>
                <div class="title">
                    <img src='../img/wallet-icon.png'>   
                    <h3>Salary Payments</h3>
                </div>
                <div class="info flex-wrap">
                    
                    <table>
                        <thead>
                            <tr>
                                <th>Payment ID</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Receipt Image</th>
                                <th>Payment Status</th>
                                <th>Submitted at</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if (isset($paymentInfo)) {
                                foreach($paymentInfo as $payment) {
                                    echo "<tr>
                                            <td class='t-align-center'>". $payment['idEmployerPayment'] ."</td>
                                            <td> ₱". $payment['employerPaymentAmount'] ."</td>
                                            <td>". $payment['employerPaymentMethod'] ."</td>
                                            <td class='image-preview t-align-center'><img src='". (isset($payment['imgReceipt']) ? getImageSrc($payment['imgReceipt']) : '../img/document-sample.jpg') ."'></td>
                                            <td class='t-align-center'>". $payment['employerPaymentStatus'] ."</td>
                                            <td>". $payment['submitted_at'] ."</td>
                                            <td class='t-align-center'>
                                                <form action='./payment_info.php' method='POST'>
                                                    <input type='hidden' name='idEmployerPayment' value='". $payment['idEmployerPayment'] ."'>
                                                    <button type='submit' name='submit' class='c-yellow'>Edit</button>
                                                </form>
                                            </td>
                                        </tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                            </div>

            </div>
        </div>
    </main>
</body>
</html>