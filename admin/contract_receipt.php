<?php
// Include necessary files and establish a database connection
require_once('../database/connect.php');

// Check if the form is submitted and the button for downloading PDF is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['contractId'])) {
    // Include the dompdf library
    require_once '../dompdf-master/vendor/autoload.php';

    // Create a new instance of the Dompdf class
    $dompdf = new \Dompdf\Dompdf();

    // Fetch HTML content of the section
    ob_start();

    $userData = fetchEmployerData($_SESSION['idUser']);
    if ($userData['verifyStatus'] == 'Not Verified') {
        header('Location: ./account_profile.php');
        exit();
    }
    
    $myIdUser = $_SESSION['idUser'];
    $myIdEmployer = getEmployerOrWorkerID($myIdUser);

    $contracts = getContractList($_POST['contractId']);

    // Define $selectedStatus and retrieve its value from $_GET if set
    $selectedStatus = isset($_GET['status']) ? $_GET['status'] : 'All';

    if ($selectedStatus != 'All') {
        $contracts = array_filter($contracts, function($contract) use ($selectedStatus) {
            return $contract['contractStatus'] == $selectedStatus;
        });
    }
    
    // Rest of your code...

    $logoPath = '../img/logo.png';
    $logoData = file_get_contents($logoPath);
    $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);

   

echo "<style>
    @page {
        margin: 50px; /* Adjust the margin size as needed */
    }
    .pdf-container {
        border: 1px solid #ba4b2f; /* Add border with theme color */
        padding: 20px; /* Add padding */
        background-color: #f2f2f2; /* Add background color */
    }
    .contract-header {
        text-align: center;
        margin-bottom: 30px;
    }
    .contract-header img {
        width: 100px;
        height: 100px;
    }
    .contract-title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
        color: #ba4b2f; /* Set title color to theme color */
    }
    .contract-content {
        font-size: 16px;
        line-height: 1.5;
    }
    .contract-signature {
        margin-top: 50px;
        font-size: 18px;
        color: #ba4b2f; /* Set signature color to theme color */
    }
    .contract-signature p {
        margin-bottom: 10px;
    }
    .logo {
        opacity: 0.5;
        width: 100px;
        height: 100px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
</style>";
    echo "<div class='pdf-container'>";
    echo "<div class='contract-header'>";
    echo "<img src='$logoBase64' alt='Logo'>";
    echo "<h1>House Connect</h1>";
    echo "</div>";

    echo "<div class='contract-content'>";
    echo "<h2 class='contract-title'>Contract Agreement:</h2>";
    echo "<hr>";
    echo "<p>This Contract Agreement (Agreement) is made effective as of the date of the last signature below (Effective Date), between the following parties:</p>";
    echo "</div>";

    $location = 'Taguig';
    if (isset($contracts)) {
        foreach ($contracts as $contract) {
            // Check if the contract should be displayed based on the selected status
            if ($selectedStatus == 'All' || $contract['contractStatus'] == $selectedStatus) {
                $employerQuery = "SELECT u.fname, u.lname
                FROM employer AS e
                INNER JOIN user AS u ON e.idUser = u.idUser
                WHERE e.idEmployer = ?";
                $employerStmt = $conn->prepare($employerQuery);
                $employerStmt->bind_param("i", $contract['idEmployer']);
                $employerStmt->execute();
                $employerResult = $employerStmt->get_result();
                if ($employerRow = $employerResult->fetch_assoc()) {
                    echo "<p> Employer: <strong>" . $employerRow['fname'] . " " . $employerRow['lname'] . "</strong>, hereinafter referred to as the (Employer), having its principal place of business at " . $location . "</p>";
                }
                echo "<p> Worker: <strong>" . $contract['workerFname'] . " " . $contract['workerLname'] . "</strong>, hereinafter referred to as the (Worker), having its principal place of business at " . $location . "</p>";
                $workerQuery = "SELECT profilePic FROM worker WHERE idWorker = ?";
                $workerStmt = $conn->prepare($workerQuery);
                $workerStmt->bind_param("i", $contract['idWorker']);
                $workerStmt->execute();
                $workerResult = $workerStmt->get_result();

                echo "<br>";
                echo "Worker Type: " . $contract['workerType'] . "<br>";
                echo "<br>";
                echo "<img src='$logoBase64' alt='Logo' class ='logo'>";
                echo "<h2><strong> Contract Details: </strong> </h2>";
                echo "<hr>";
                echo "<p>Contract ID: ". $contract['idContract'] . "</p>";
                echo "<p>Contract Status: ". $contract['contractStatus'] . "</p>";
                echo "<p>Start Date: ". $contract['startDate'] . "</p>";
                echo "<p>End Date: ". $contract['endDate'] . "</p>";
                echo "<p>Date Created: ". $contract['date_created'] . "</p>";
                echo "<hr>";
               
            }
        }
    }

    echo "<p><strong>1. Duties and Responsibilities: </strong><br><br>
    The Worker agrees to perform the following duties and responsibilities as required by the Employer:<br><br>
    &emsp;- Undertake tasks essential to household management and upkeep.<br>
    &emsp;- Provide assistance with daily chores and activities as directed by the Employer.<br>
    &emsp;- Maintain cleanliness and organization within the premises.<br>
    &emsp;- Execute tasks efficiently and with attention to detail.<br><br>
    <img src='$logoBase64' alt='Logo' class ='logo'><br><br>
    <strong>2. Compensation: </strong><br><br>
    In consideration of the services provided by the Worker, the Employer agrees to pay the Worker a total compensation of [insert compensation details, e.g., hourly rate, monthly salary] payable [insert payment schedule, e.g., weekly, bi-weekly, monthly].<br><br>
    <strong> 3. Termination: </strong><br><br>
    Either party may terminate this Agreement with [insert notice period, e.g., two weeks] written notice to the other party.<br><br>
    <strong> 4. Confidentiality: </strong><br><br>
    The Worker agrees to maintain the confidentiality of any information obtained during the course of employment, including but not limited to personal information about the Employer and their household.<br><br>
    <strong> 5. Governing Law: </strong><br><br>
    This Agreement shall be governed by and construed in accordance with the laws of [insert jurisdiction].<br><br>
    <strong> IN WITNESS WHEREOF, the parties hereto have executed this Agreement as of the Effective Date. </strong></p>"; 


    echo "<div class='contract-signature'>";
    echo "<p>___________________________</p>";
    echo "<p>Signature of Employer</p>";
    echo "<p>Date: _______________</p>";
    echo "</div>";

    echo "<div class='contract-signature'>";
    echo "<p>___________________________</p>";
    echo "<p>Signature of Worker</p>";
    echo "<p>Date: _______________</p>";
    echo "</div>";
     
  
    echo "<p>Note: This contract is for informational purposes only and should be reviewed by legal counsel before use. </p>";

    echo "</div>"; // End of pdf-container

    $html = ob_get_clean();

    // Load HTML content into dompdf
    $dompdf->loadHtml($html);

    // Set paper size (optional)
    $dompdf->setPaper('A4', 'portrait');

    // Render PDF (first step)
    $dompdf->render();

    // Add watermark
  
    // Output the generated PDF (second step - create a download link)
    $dompdf->stream('house_connect_contract.pdf', array('Attachment' => 0));
    exit;
}
?>
