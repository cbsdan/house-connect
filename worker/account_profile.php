<?php
    //Check first if the user is logged in
    include_once('../functions/user_authenticate.php');
    include_once('../database/connect.php');

    if ($_SESSION['userType'] == 'Employer') {
        header('Location: ../employer/account_profile.php');
        exit();
    }
    if ($_SESSION['userType'] == 'Admin') {
        header('Location: ../admin/dashboard.php');
        exit();
    }

    // Fetch user and worker information
    $user = fetchUserInformation($conn);
    $worker = fetchWorkerInformation($conn);

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Update other profile information in the database
        $birthdate = $_POST['birthdate'];
        $address = $_POST['address'];
        $contactNo = $_POST['contactNo'];
        $yearsOfExperience = $_POST['yearsOfExperience'];

        // Update user and worker table with new information
        $updateUserQuery = "UPDATE user SET birthdate='$birthdate', address='$address', contactNo='$contactNo' WHERE idUser='" . $user['idUser'] . "'";
        $updateWorkerQuery = "UPDATE worker SET yearsOfExperience='$yearsOfExperience'";

        // Handle profile picture upload
        if(isset($_FILES["profilePic"]) && $_FILES["profilePic"]["error"] == 0) {
            $profilePic = addslashes(file_get_contents($_FILES['profilePic']['tmp_name']));
            $updateWorkerQuery .= ", profilePic='$profilePic'";
        }

        $updateWorkerQuery .= " WHERE idUser='" . $user['idUser'] . "'";

        if ($conn->query($updateUserQuery) === TRUE && $conn->query($updateWorkerQuery) === TRUE) {
            // If update successful, fetch the updated information
            $user = fetchUserInformation($conn);
            $worker = fetchWorkerInformation($conn);
        } else {
            echo "<script>alert(Error updating profile: " . $conn->error. ")</script>";
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

    <link rel="stylesheet" href="../styles/worker_styles/account_profile.css">
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
                        <a href='./salary_payment.php' class='c-light'>SALARY PAYMENT</a>
                        <a href='./account_profile.php' class='c-light fw-bold'>ACCOUNT PROFILE</a>
                    </nav>
                    <nav>
                        <a href='../login.php' class='c-light'>LOG OUT</a>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <main class='worker'>
        <div class='container application'>
            <div class='content'>
                <div class='title'>
                    <div class='left'>
                        <img class='user-profile' src='../img/user-icon.png' placeholder='profile'>
                        <h3>Account Profile</h3>   
                    </div>
                    <div class='right'>
                        <img class='edit-profile' src='../img/edit-icon.png' placeholder>
                        <button class='save-changes hidden'>Save Changes</button>
                    </div>
                </div>
                <div class='info view-only'>
                    <div class='left'>
                        <p class='label'>Email Address</p>
                        <p class='text-box'><?php echo $user['email']; ?></p>
                        <p class='label'>First Name</p>
                        <p class='text-box'><?php echo $user['fname']; ?></p>
                        <p class='label'>Last Name</p>
                        <p class='text-box'><?php echo $user['lname']; ?></p>
                        <p class='label'>Sex</p>
                        <p class='text-box'><?php echo $user['sex']; ?></p>
                        <p class='label'>Height (cm)</p>
                        <p class='text-box'><?php echo $worker['height']; ?></p>
                    </div>
                    <div class='right'>
                        <p class='label'>Birthdate</p>
                        <p class='text-box'><?php echo $user['birthdate']; ?></p>
                        <p class='label'>Address</p>
                        <p class='text-box'><?php echo $user['address']; ?></p>
                        <p class='label'>Contact Number</p>
                        <p class='text-box'><?php echo $user['contactNo']; ?></p>
                        <p class='label'>Years of Experience</p>
                        <p class='text-box'><?php echo $worker['yearsOfExperience']; ?></p>
                        <p class='label'>User Type</p>
                        <p class='text-box'><?php echo $user['userType']; ?></p>
                    </div>
                </div>
                
                <!-- Editable form -->
                <form id="profileForm" action='./account_profile.php' method='POST' class='info editable hidden' enctype="multipart/form-data">
                    <div class='left'>
                        <p class='label'>Email Address</p>
                        <p class='text-box'><?php echo $user['email']; ?></p>
                        <p class='label'>First Name</p>
                        <p class='text-box'><?php echo $user['fname']; ?></p>
                        <p class='label'>Last Name</p>
                        <p class='text-box'><?php echo $user['lname']; ?></p>
                        <p class='label'>Sex</p>
                        <p class='text-box'><?php echo $user['sex']; ?></p>
                        <p class='label'>Height</p>
                        <p class='text-box'><?php echo $worker['height']; ?></p>
                    </div>
                    <div class="right">
                        <label class='label'>Change Profile Picture</label>
                        <input class='text-box' type="file" name="profilePic">
                        <label class='label'>Birthdate (Editable)</label>
                        <input class='text-box' type="date" name="birthdate" value="<?php echo $user['birthdate']; ?>">
                        <label class='label'>Address (Editable)</label>
                        <input class='text-box' type="text" name="address" value="<?php echo $user['address']; ?>">
                        <label class='label'>Contact Number (Editable)</label>
                        <input class='text-box' type="text" name="contactNo" value="<?php echo $user['contactNo']; ?>">
                        <label class='label'>Years of Experience (Editable)</label>
                        <input class='text-box' type="number" name="yearsOfExperience" value="<?php echo $worker['yearsOfExperience']; ?>">
                    </div>
                </form>

            </div>
        </div>
    </main>

    <!-- JavaScript to submit the form when Save Changes button is clicked -->
    <script>
        document.querySelector('.save-changes').addEventListener('click', function() {
            document.getElementById('profileForm').submit();
        });
    </script>
</body>
</html>