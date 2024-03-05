<?php
    //Check first if the user is logged in
    include_once('../functions/user_authenticate.php');

    if ($_SESSION['userType'] == 'Worker') {
        header('Location: ../worker/application.php');
        exit();
    }
    if ($_SESSION['userType'] == 'Admin') {
        header('Location: ../admin/dashboard.php');
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $_SESSION['idContract'] = $_POST['idContract']; //Use to avoid error when clicking the back to manage worker button
        $_SESSION['workerIdUser'] = $_POST['workerIdUser']; //Use to avoid error when clicking the back to manage worker button
    } else {
        header('Location: ./manage_worker.php');
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

    <link rel="stylesheet" href="../styles/employer_styles/salary_payment.css">
    <link rel="stylesheet" href="../styles/employer_styles/default.css">

    <!-- JavaScript -->
    <script src='../scripts/worker.js' defer></script>

</head>
<body>
    <header class='logged-in'>
        <div class="content">
            <button class='orange-white-btn'><a href='./contract_info.php'>Back to Contract Info</a></button>
        </div>
    </header>

    <main class='employer'>
        <div class='container application'>
            <div class='content'>
                <div class='title'>
                    <img class='user-profile' src='../img/wallet-icon.png' placeholder='payment-icon'>
                    <h3>Salary Payment</h3>  
                </div>
                <form class="info" action='' method='POST' enctype="multipart/form-data"> 
                        <input type="hidden" name='worker_id' value=''>
                        <label class="label">Contract ID</label>
                        <input class='text-box' name='contract-id' value='001' readonly>
                        <label class="label">Worker Name</label>
                        <input class="text-box" name='name' type='text' value='Juan Dela Cruz' readonly>
                        <label class="label">Enter Amount</label>
                        <input class="text-box" name='amount' type='number' min=0 required>
                        <label class="label">Method</label>
                        <input class="text-box" type='text' name='method' value='Paypal'>
                        <label class="label">Image Receipt</label>
                        <input class='text-box' type="file" id="image-receipt" name="image_receipt" accept="image/png, image/jpeg, image/jpg">
                </form>
            </div>
        </div>
    </main>
</body>
</html>