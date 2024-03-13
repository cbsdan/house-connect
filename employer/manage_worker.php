<?php
    //Check first if the user is logged in
    include_once('../database/connect.php');

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
    
    $myIdUser = $_SESSION['idUser'];
    $myIdEmployer = getEmployerOrWorkerID($myIdUser);

    $contracts = getContractListByEmployerID($myIdEmployer);
    // var_dump($contracts);

    // Define a variable to store the selected status
    $selectedStatus = isset($_GET['status']) ? $_GET['status'] : 'All';

    // Filter contracts based on the selected status
    if ($selectedStatus != 'All') {
        $contracts = array_filter($contracts, function($contract) use ($selectedStatus) {
            return $contract['contractStatus'] == $selectedStatus;
        });
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

    <link rel="stylesheet" href="../styles/employer_styles/manage_worker.css">
    <link rel="stylesheet" href="../styles/employer_styles/default.css">

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
                        <a href='./request_a_worker.php' class='c-light'>REQUEST A WORKER</a>
                        <a href='./worker_requests.php' class='c-light'>WORKER REQUESTS</a>
                        <a href='./manage_worker.php' class='c-light fw-bold'>MANAGE WORKER</a>
                        <a href='./account_profile.php' class='c-light'>ACCOUNT PROFILE</a>
                    </nav>
                    <nav>
                        <a href='../logout.php' class='c-light'>LOG OUT</a>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <main class='employer'>
        <div class='container application'>
            <div class='content'>
                <div class='title'>
                    <img class='user-profile' src='../img/worker-group-icon.png' placeholder='profile'>
                    <h3>Manage Worker</h3>   
                </div>
                <div class='info'>  
                    <label class='label fw-bold fs-extra-large <?php echo (!isset($contracts) ? 'hidden' : '');?>'>List of Workers</label>
                    <!-- Add an onchange event to the dropdown to trigger filtering -->
                    <select name='status' class='select-worker-type <?php echo (!isset($contracts) ? 'hidden' : '');?>' onchange="filterContracts()">
                        <option value='All' <?php echo ($selectedStatus == 'All') ? 'selected' : ''; ?>>All</option>
                        <option value='Hired' <?php echo ($selectedStatus == 'Hired') ? 'selected' : ''; ?>>Hired</option>
                        <option value='Pending' <?php echo ($selectedStatus == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value='Canceled' <?php echo ($selectedStatus == 'Canceled') ? 'selected' : ''; ?>>Canceled</option>
                        <option value='Completed' <?php echo ($selectedStatus == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                </select>
                    <div class='table-container'>
                        <table class='<?php echo (!isset($contracts) ? 'hidden' : '');?>'>
                            <thead>
                                <tr>
                                    <th>Contract ID</th>
                                    <th>Status</th>
                                    <th>Worker Profile</th>
                                    <th>Worker Name</th>
                                    <th>Worker Type</th>
                                    <th>Date Created</th>
                                    <th>Details</th>
                                    <th>Payments</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Display the filtered contracts -->
                            <?php
                                if (isset($contracts)) {
                                    foreach ($contracts as $contract) {
                                        // Check if the contract should be displayed based on the selected status
                                        if ($selectedStatus == 'All' || $contract['contractStatus'] == $selectedStatus) {
                                    ?>
                                        <tr>
                                            <td class='t-align-center'><?php echo $contract['idContract']; ?></td>
                                            <td><?php echo $contract['contractStatus']; ?></td>
                                            <td class='t-align-center'><img src='../img/user-icon.png' alt='profile'></td>
                                            <td><?php echo $contract['workerFname'] . " " . $contract['workerLname']; ?></td>
                                            <td class='t-align-center'><?php echo $contract['workerType']; ?></td>
                                            <td><?php echo $contract['date_created']; ?></td>
                                            <td class='t-align-center'>
                                                <!-- Form for passing worker information to contract_info.php -->
                                                <form action='./contract_info.php' method='POST' class='open-detail-preview'>
                                                    <input type='hidden' name='idContract' value='<?php echo $contract['idContract']; ?>'>
                                                    <input type='hidden' name='workerIdUser' value='<?php echo $contract['workerIdUser']; ?>'>
                                                    <input type='hidden' name='workerName' value='<?php echo $contract['workerFname'] . " " . $contract['workerLname']; ?>'>
                                                    <input type='hidden' name='workerType' value='<?php echo $contract['workerType']; ?>'>
                                                    <input type='hidden' name='contractStatus' value='<?php echo $contract['contractStatus']; ?>'>
                                                    <input type='hidden' name='workerProfilePic' value='../img/user-icon.png'> <!-- Update with actual path -->
                                                    <button type='submit' class='c-yellow details'>[Details]</button>
                                                </form>
                                            </td>
                                            <td class='t-align-center'>
                                                <!-- Form for passing worker information to contract_info.php -->
                                                <form action='./salary_payments.php' method='POST' class='open-detail-preview'>
                                                    <input type='hidden' name='idContract' value='<?php echo $contract['idContract']; ?>'>
                                                    <input type='hidden' name='workerIdUser' value='<?php echo $contract['workerIdUser']; ?>'>
                                                    <input type='hidden' name='workerName' value='<?php echo $contract['workerFname'] . " " . $contract['workerLname']; ?>'>
                                                    <input type='hidden' name='workerType' value='<?php echo $contract['workerType']; ?>'>
                                                    <input type='hidden' name='contractStatus' value='<?php echo $contract['contractStatus']; ?>'>
                                                    <input type='hidden' name='workerProfilePic' value='../img/user-icon.png'> <!-- Update with actual path -->
                                                    <button type='submit' class='c-yellow details'>[View]</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php
                                        }                                     
                                    }
                                }
                                ?>
                                
            </tbody>
                        </table>
                        <div class='no-record-label <?php echo (isset($contracts) ? 'hidden' : '');?>'>
                            <p>There are no found records!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
            function filterContracts() {
                var status = document.querySelector('.select-worker-type').value;
                window.location.href = 'manage_worker.php?status=' + status;
            }
    </script>
</body>
</html>