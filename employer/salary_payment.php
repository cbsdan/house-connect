<?php
// Check first if the user is logged in
include_once('../functions/user_authenticate.php');
include_once('../database/connect.php');

if ($_SESSION['userType'] == 'Worker') {
    header('Location: ../worker/application.php');
    exit();
}
if ($_SESSION['userType'] == 'Admin') {
    header('Location: ../admin/dashboard.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['idContract']) && isset($_POST['workerIdUser'])) {
        $_SESSION['idContract'] = $_POST['idContract'];
        $_SESSION['workerIdUser'] = $_POST['workerIdUser'];
    
        // Retrieve other values
        $contractId = $_POST['idContract'];
        $contractInfo = getContractList($contractId);
        $contractInfo = $contractInfo[0];
    
        $workerName = isset($_POST['workerName']) ? $_POST['workerName'] : '';
    
        // Check if the workerName is set and has a space
        if (strpos($workerName, ' ') !== false) {
            list($workerFname, $workerLname) = explode(' ', $workerName, 2);
        } else {
            // Handle the case where the workerName does not contain a space
            $workerFname = $workerName;
            $workerLname = ''; // You can set it to an empty string or handle it differently based on your needs
        }
    }
    if (isset($_POST['submitPayment'])) {
        // Retrieve form data
        $amount = $_POST['amount'];
        $method = $_POST['method'];
        $contractId = $_POST['idContract'];
        // Handle image receipt upload
        $imageReceipt = ''; // Initialize with an empty string
        if (isset($_FILES['image_receipt']) && is_uploaded_file($_FILES['image_receipt']['tmp_name'])) {
            $imageReceipt = addslashes(file_get_contents($_FILES['image_receipt']['tmp_name']));
        }

        $idEmployerPayment = insertEmployerPayment($contractId, $amount, $method, $imageReceipt);

        insertWorkerSalary("samplepaypal@gmail.com", $amount * 0.9, $idEmployerPayment);

        header('Location: ./manage_worker.php');
        exit();
    }
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
                    <input type="hidden" name='idContract' value='<?php echo $contractId; ?>'>
                    <input type="hidden" name='workerName' value='<?php echo $workerName; ?>'>

                    <label class="label">Contract ID</label>
                    <input class='text-box' name='contract-id' value='<?php echo $contractId; ?>' readonly>

                    <label class="label">Worker Name</label>
                    <input class="text-box" name='name' type='text' value='<?php echo $workerFname . ' ' . $workerLname; ?>' readonly>

                    <label class="label">Enter Amount (P)</label>
                    <input class="text-box" name='amount' type='number' min=<?php echo $contractInfo['salaryAmt']?> required>

                    <label class="label">Method</label>
                    <input class="text-box" type='text' name='method' value='Paypal' readonly>

                    <label class="label">Image Receipt</label>
                    <input class='text-box' type="file" id="image-receipt" name="image_receipt" accept="image/png, image/jpeg, image/jpg">
                    <button class="orange-white-btn" type="submit" name="submitPayment">Submit Payment</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>