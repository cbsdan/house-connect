<?php
include_once('../database/connect.php');
include_once('../functions/user_authenticate.php');

if ($_SESSION['userType'] == 'Worker') {
    header('Location: ../worker/application.php');
    exit();
}

if ($_SESSION['userType'] == 'Admin') {
    header('Location: ../admin/dashboard.php');
    exit();
}

$userData = fetchEmployerData($_SESSION['idUser']);
if ($userData['verifyStatus'] == 'Not Verified') {
    header('Location: ./account_profile.php');
    exit();
}

require_once('../Classes/Contract.php');
$contractObj = new Contract($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['input-contract-details'])) {
        $_SESSION['idContract'] = $_POST['idContract'];
        $_SESSION['workerIdUser'] = $_POST['workerIdUser'];

        $idContract = $_POST['idContract'];
        $idWorker = $_POST['idWorker'];
        $contractStatus = $_POST['contractStatus'];
    }
    if (isset($_POST['update-contract'])) {
        $idContract = $_POST['idContract'] ?? null;
        $idWorker = $_POST['idWorker'];
        $contractStatus = $_POST['contractStatus'] ?? null;
        $salaryAmt = $_POST['salaryAmt'] ?? null;
        $startDate = $_POST['startDate'] ?? null;
        $endDate = $_POST['endDate'] ?? null;
        
        if (isset($_FILES['contractImg']) && is_uploaded_file($_FILES['contractImg']['tmp_name'])) {
            $contractImg = addslashes(file_get_contents($_FILES['contractImg']['tmp_name']));
        } else {
            $contractImg = null;
        }
        $result = $contractObj->updateContract($idContract, $contractStatus, $startDate, $endDate, $salaryAmt, $contractImg);
        updateWorkerStatus($idWorker, $contractStatus);
        header('Location: ./manage_worker.php');
        exit();
    }

} else {
    header('Location: ./manage_worker.php');
    exit();
}

//get user profile image source
if (isset($editUserData['profilePic']) && !($editUserData['profilePic'] == '')) {
    $profilePic = getImageSrc($editUserData['profilePic']);
} else {
    $profilePic = '../img/user-icon.png';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House Connect - Edit Profile</title>
    <link rel="icon" href="../img/favicon.ico" type="image/x-icon">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="../styles/variables.css">
    <link rel="stylesheet" href="../styles/includes.css">
    <link rel="stylesheet" href="../styles/media_query.css">
    <link rel="stylesheet" href="../styles/default.css">

    <link rel="stylesheet" href="../styles/employer_styles/manage_worker.css">
    <link rel="stylesheet" href="../styles/employer_styles/default.css">
</head>

<body>
    <header class='logged-in'>
        <div class="content">
            <button class='orange-white-btn'><a href='./contract_info.php' class='c-light'>Back to Contract Info</a></button>
        </div>
    </header>

    <main class='employer'>
        <div class='container application'>
            <div class='content contract-details'>
                <div class='title'>
                    <img src='../img/documents-icon.png' placeholder='profile'>
                    <h3>Contract Details</h3>
                </div>
                <form class='info editable' action='' method='POST' enctype="multipart/form-data">
                    <div class='left'>
                        <input type='hidden' name='contractStatus' value='Hired'>
                        <input type='hidden' name='idWorker' value='<?php echo $idWorker ?>'>
                        <label class='label'>Contract ID</label>
                        <input class='text-box' type='number' name='idContract' readonly value="<?php echo $idContract;?>">
                        <label class='label'>Salary Amount ₱ (Required)</label>
                        <input class='text-box' name='salaryAmt' type='number' min=15000 placeholder="minimum ₱ 15,000" required>
                    </div>
                    <div class='right'>
                        <label class='label'>Start Date (Required)</label>
                        <input class='text-box' name='startDate' type='date' required>
                        <label class='label'>End Date (Required)</label>
                        <input class='text-box' name='endDate' type='date' required>
                        <label for="imageUpload" class='label'>Contract Image</label>
                        <input type="file" class='text-box' id="contractImg" name="contractImg" accept="image/jpeg, image/png, image/jpg">
                    </div>
                    <div class='m-l-auto'><button type='submit' id='update-contract' name='update-contract' value='true' class='green-white-btn m-t-auto'>Save Changes</button></div>
                    </form>
            </div>
        </div>
    </main>
</body>

</html>
