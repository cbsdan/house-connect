<?php
    //Check first if the user is logged in
    include_once('../database/connect.php');

    if ($_SESSION['userType'] == 'Employer') {
        header('Location: ../employer/account_profile.php');
        exit();
    }
    if ($_SESSION['userType'] == 'Admin') {
        header('Location: ../admin/dashboard.php');
        exit();
    }


    $worker = $workerObj -> getWorkersByConditions(null, null, null, null, null, null, null, null, $_SESSION['idUser']);
    if ($worker != false) {
        $worker = $worker[0];
    }

    $contract = $contractObj -> getContractByConditions(["idWorker" => $worker['idWorker']]);

    if ($contract != false) {
        $employerPayment = $employerPaymentObj -> getEmployerPaymentByConditions(["idContract" => $contract['idContract']]);

        if ($salaryDetails != false ) {
            $workerSalary = $workerSalaryObj -> getWorkerSalaryByConditions(["idEmployerPayment" => $salaryDetails['idEmployerPayment']]);
        }
    }
    
    if (isset($_POST['filter']) && isset($_POST['idContract'])) {
        $employerPayment = $employerPaymentObj -> getEmployerPaymentByConditions(["idContract" => $_POST['idContract']]);
        
    }

    if (isset($_POST['idWorker']) && isset($_POST['paypalEmail'])) {
        $workerObj -> updateWorker($_POST['idWorker'], null, null, null, null, null, null, $_POST['paypalEmail'], null, null);
        header('Location: ./salary_payment.php');
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
                        <a href='./work_history.php' class='c-light'>WORK HISTORY</a>
                        <a href='./salary_payment.php' class='c-light fw-bold'>SALARY PAYMENT</a>
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
                    <img src='../img/wallet-icon.png'>
                    <h3>Salary Payment</h3>   
                </div>
                <div class='info flex-column'>
                    <form action='' method='POST' class='flex-row  m-b-2'>
                        <label class='label fw-bold'>Paypal Email:</label>
                        <input type='email' class='text-box' name='paypalEmail' placeholder="Please set-up your paypal email for your salary" value='<?php echo (isset($worker['paypalEmail']) ? $worker['paypalEmail'] : '');?>'>
                        <input type='hidden' name='idWorker' value='<?php echo $worker['idWorker'];?>'>
                        <button type='submit' name='update' class='green-white-btn'>Update</button>
                    </form>
                    <form action='' method='POST' class='flex-row'>
                        <label class='label fw-bold'>Search Payment:</label>
                        <input type='number' name='idContract' class='flex-1' placeholder="Search by contract ID">
                        <button type='submit' name='filter' class='orange-white-btn'>Search</button>
                    </form>
                    <table class='<?php echo ($employerPayment != false ? '' : 'hidden');?>'>
                        <thead>
                            <tr>
                                <th>Contract ID</th>
                                <th>Employer Name</th>
                                <th>Paypal Account</th>
                                <th>Status</th>
                                <th>Amt. Paid by Employer</th>
                                <th>End of Contract</th>
                                <th>Salary Amount</th>
                                <th>Date</th>
                                <th>Print</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            if (isset($employerPayment) && $employerPayment != false) {
                                foreach ($employerPayment as $row) {
                                    $contractInfo = getContractList($row['idContract']);
                                    $contractInfo = $contractInfo[0];

                                    $paypalacc = $row['workerPaypalEmail'];
                                    $status = $row['workerSalaryStatus'];
                                    $amountPaidEmp = $row['employerPaymentAmount'];
                                    $endDate = $row['endDate'];
                                    $salaryamt = $row['workerSalaryAmount'];

                                    $workerSalary = getWorkerSalaryInformation($row['idContract']);
                                    $date = $workerSalary['modified_at'];

                                    echo 
                                    "<tr>
                                        <td class='t-align-center'>".$row['idContract']."</td>
                                        <td>".$contractInfo['employerFname']. " ". $contractInfo['employerLname'] ."</td>
                                        <td>$paypalacc</td>
                                        <td>$status</td>
                                        <td>₱ $amountPaidEmp</td>
                                        <td>$endDate</td>
                                        <td>₱ $salaryamt</td>
                                        <td>$date</td>
                                        <td class='t-align-center'>
                                            <form action='payment_pdf.php' method='post'>
                                            <input type='hidden' name='idContract' value='".$row['idContract']."'>
                                            <button type='submit' class='c-yellow' name='receipt'>[Receipt]</button>
                                            </form>
                                        </td>
                                    </tr>";
                                }
                            }
                         ?>
                        </tbody>
                    </table>

                    <!-- If there is no previous work -->
                    <div class='no-record-label <?php echo (isset($salaryDetails) ? 'hidden' : '');?>'>
                        <p>You have no previous salary payment in the system!</p>
                    </div>
                </div>
            </div>
        </div>
    </main>


</body>
</html>