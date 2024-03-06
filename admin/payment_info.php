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
    
    if (isset($_POST['idEmployerPayment'])) {
        $paymentInfo = getAllEmployerPayments($_POST['idEmployerPayment']);
        $paymentInfo = $paymentInfo[0];
    } else {
        header('Location: ../admin/user_accounts.php');
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

    <link rel="stylesheet" href="../styles/admin_styles/user_accounts.css">
    <link rel="stylesheet" href="../styles/admin_styles/default.css">

    <!-- JavaScript -->
    <script src='../scripts/worker.js' defer></script>
    <script src='../scripts/jquery-3.7.1.min.js' defer></script>

</head>
<body>
    <header class='logged-in'>
        <div class="content">
            <button class='orange-white-btn'><a href='./payment.php' class='c-light'>Back to Payment Manager</a></button>
        </div>
    </header>

    <main class='admin edit-user'>
        <div class='container application'>
            <div class='content'>
                <div class="title">
                    <img src='../img/wallet-icon.png'>   
                    <h3>Payment Information</h3>
                </div>
                <form class="info flex-wrap" action='../database/update_employer_payment.php' method='POST' enctype="multipart/form-data">
                    <div class="left">
                        <div class="data">
                            <h4 class="label">Payment ID</h4>
                            <input class="text-box" type='text' name='idEmployerPayment' value='<?php echo $paymentInfo['idEmployerPayment'];?>' readonly >
                        </div>
                        <div class="data">
                            <h4 class="label">Payment Amount (₱)</h4>
                            <input class="text-box" type='number' name='employerPaymentAmount' value='<?php echo $paymentInfo['employerPaymentAmount'];?>'>
                        </div>
                        <div class="data">
                            <h4 class="label">Method</h4>
                            <input class="text-box" type='text' name='employerPaymentMethod' value='<?php echo $paymentInfo['employerPaymentMethod'];?>'>
                        </div>
                        <div class="data">
                            <h4 class="label">Payment Receipt</h4>
                            <div class='image-preview'>
                                <img src='<?php echo (isset($paymentInfo['imgReceipt']) ? getImageSrc($paymentInfo['imgReceipt']) : '../img/document-sample.jpg');?>'>
                            </div>
                        </div>
                    </div>
                    <div class='right'>
                        <div class="data">
                            <h4 class="label">Payment Status</h4>
                            <select class='text-box' name='employerPaymentStatus'>
                                <option value='Pending' <?php echo ($paymentInfo['employerPaymentStatus'] == 'Pending') ? 'selected' : ''?>>Pending</option>    
                                <option value='Successful' <?php echo ($paymentInfo['employerPaymentStatus'] == 'Successful') ? 'selected' : ''?>>Successful</option>    
                                <option value='Failed' <?php echo ($paymentInfo['employerPaymentStatus'] == 'Failed') ? 'selected' : ''?>>Failed</option>    
                            </select>
                        </div>
                        <div class="data">
                            <h4 class="label">Modified at</h4>
                            <input class="text-box" type='datetime-local' name='submitted_at' value='<?php echo $paymentInfo['submitted_at'];?>'>
                        </div>
                        <div class="data">
                            <h4 class="label">Update Receipt</h4>
                            <input class="text-box" type='file' name='imgReceipt' accept="image/jpeg, image/png, image/jpg">
                        </div>
                    </div>
                    <div class='flex-basis-100 m-l-auto justify-content-end'>
                        <button type='submit' class='green-white-btn' name='submit' value='submit'>Save Changes</button>
                    </div>
                </form>

                <div class="title">
                    <img class='logo' src='../img/documents-icon.png'>
                    <h3>Contract Information</h3>
                </div>
                <div class="info">
                    <div class="left">
                        <div class="data">
                            <h4 class="label">Contract ID</h4>
                            <input class="text-box" name='idContract' type='text' readonly value='<?php echo $paymentInfo['idContract']; ?>' readonly>
                        </div>
                        <div class="data">
                            <h4 class="label">Contract Status</h4>
                            <input class="text-box" type='text' name='contractStatus' value='<?php echo $paymentInfo['contractStatus']; ?>' readonly>
                        </div>
                        <div class="data">
                            <h4 class="label">Start Date</h4>
                            <input class="text-box" type='date' name='startDate' value='<?php echo $paymentInfo['startDate'];?>' readonly>
                        </div>
                        <div class="data">
                            <h4 class="label">End Date</h4>
                            <input class="text-box" type='date' name='endDate' value='<?php echo $paymentInfo['endDate'];?>' readonly>
                        </div>
                    </div>
                    <div class="right">
                        <div class="data">
                            <h4 class="label">Salary Amount</h4>
                            <input class="text-box" type='number' name='salaryAmt' value='<?php echo $paymentInfo['salaryAmt'];?>'>
                        </div>
                        <div class="data">
                            <h4 class="label">Date Created</h4>
                            <input class="text-box" type='datetime-local' name='date_created' value='<?php echo $paymentInfo['date_created'];?>' readonly>
                        </div>
                        <div class="data">
                            <h4 class="label">Contract Image</h4>
                            <div class="image-preview"> 
                                <img src='<?php echo (isset($paymentInfo['contractImg']) ? getImageSrc($paymentInfo['contractImg']) : '../img/document-sample.jpg');?>'>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="title">
                    <div class='image-preview'> <img class='logo' src='<?php echo (isset($paymentInfo['employerProfilePic']) ? getImageSrc($paymentInfo['employerProfilePic']) : '../img/user-icon.png') ?>'></div>
                    <h3>Employer Information</h3>
                </div>
                <div class="info">
                    <div class="left">
                        <div class="data">
                            <h4 class="label">User ID</h4>
                            <input class="text-box" type='text' readonly value='<?php echo $paymentInfo['employerIdUser']; ?>' readonly>
                        </div>
                        <div class="data">
                            <h4 class="label">Name</h4>
                            <input class="text-box" type='text' value='<?php echo $paymentInfo['employerFname'] . " " . $paymentInfo['employerLname'] ; ?>' readonly>
                        </div>
                        <div class="data">
                            <h4 class="label">Email</h4>
                            <input class="text-box" type='email' value='<?php echo $paymentInfo['employerEmail'];?>' readonly>
                        </div>
                    </div>
                    <div class="right">
                        <div class="data">
                            <h4 class="label">Age</h4>
                            <input class="text-box" type='number' value='<?php echo calculateAge($paymentInfo['employerBirthdate']);?>' readonly>
                        </div>
                        <div class="data">
                            <h4 class="label">Sex</h4>
                            <input class="text-box" type='text' value='<?php echo $paymentInfo['employerSex'];?>'>
                        </div>
                    </div>
                </div>

                <div class="title">
                    <div class='image-preview'> <img class='logo' src='<?php echo (isset($paymentInfo['workerProfilePic']) ? getImageSrc($paymentInfo['workerProfilePic']) : '../img/user-icon.png') ?>'></div>
                    <h3>Worker Information</h3>
                </div>
                <div class="info">
                    <div class="left">
                        <div class="data">
                            <h4 class="label">User ID</h4>
                            <input class="text-box" type='text' readonly value='<?php echo $paymentInfo['workerIdUser']; ?>' readonly>
                        </div>
                        <div class="data">
                            <h4 class="label">Name</h4>
                            <input class="text-box" type='text' value='<?php echo $paymentInfo['workerFname'] . " " . $paymentInfo['workerLname'] ; ?>' readonly>
                        </div>
                        <div class="data">
                            <h4 class="label">Email</h4>
                            <input class="text-box" type='email' value='<?php echo $paymentInfo['workerEmail'];?>' readonly>
                        </div>
                        <div class="data">
                            <h4 class="label">Age</h4>
                            <input class="text-box" type='number' value='<?php echo calculateAge($paymentInfo['workerBirthdate']);?>' readonly>
                        </div>
                    </div>
                    <div class="right">
                        <div class="data">
                            <h4 class="label">Worker Type</h4>
                            <input class="text-box" type='text' value='<?php echo $paymentInfo['workerType'];?>' readonly>
                        </div>
                        <div class="data">
                            <h4 class="label">Worker Status</h4>
                            <input class="text-box" type='text' value='<?php echo $paymentInfo['workerStatus'];?>' readonly>
                        </div>
                        <div class="data">
                            <h4 class="label">Sex</h4>
                            <input class="text-box" type='text' value='<?php echo $paymentInfo['workerSex'];?>'>
                        </div>
                    </div>
                </div>

                <div class='buttons'>
                    <form action='../database/delete_employer_payment.php' method='POST' onsubmit='return confirmDelete()'>
                        <input type='hidden' name='idEmployerPayment' value="<?php echo $paymentInfo['idEmployerPayment']; ?>">
                        <button type='submit' name='delete' value='delete' class='red-white-btn'>Delete Payment</button>
                    </form>
                </div>

            </div>
        </div>
    </main>
</body>
</html>