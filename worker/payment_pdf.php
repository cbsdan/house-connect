<?php
// Include necessary files and establish a database connection
require_once('../database/connect.php');

// Check if the form is submitted and the button for downloading PDF is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['receipt'])) {
    // Include the dompdf library
    require_once '../dompdf-master/vendor/autoload.php';

    // Create a new instance of the Dompdf class
    $dompdf = new \Dompdf\Dompdf();

 

    // Fetch HTML content of the section
    
    ob_start();
   
    $logoPath = '../img/logo.png';
    $logoData = file_get_contents($logoPath);
    $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);

    $salaryDetails = getWorkerSalaryAndPaymentDetails($_SESSION['idUser']);
    if (isset($_POST['idContract'])) {
        $idContract = $_POST['idContract'];
        $salaryDetails = getWorkerSalaryAndPaymentDetails($_SESSION['idUser'], $idContract);
        $salaryDetails = $salaryDetails[0];
        $contract = getContractList($idContract);
        $contract = $contract[0];
    }

    echo "<style>
    @page {
                margin: 50px; /* Adjust the margin size as needed */
            }
            .pdf-container {
                border: 1px solid black; /* Add border */
                padding: 20px; /* Add padding */
            }
        </style>";

    
    echo "<div class='pdf-container'>";
    echo "<div style='text-align:center;'>";
    echo "<img src='$logoBase64' alt='Logo' style='width: 100px; height: 100px;'>";
    echo "<h1>House Connect</h1>";
    echo "<h2>Official Receipt</h2>";
    echo "<hr>";
    echo "</div>";

    
    
    if (isset($salaryDetails)) {
        $contractInfo = getContractList($salaryDetails['idContract']);
        $contractInfo = $contractInfo[0];

        $paypalacc = $salaryDetails['workerPaypalEmail'];
        $status = $salaryDetails['workerSalaryStatus'];
        $amountPaidEmp = $salaryDetails['employerPaymentAmount'];
        $endDate = $salaryDetails['endDate'];
        $workerSalaryAmount = $salaryDetails['workerSalaryAmount'];
        $workerName = $contractInfo['workerFname'] . " " . $contractInfo['workerLname'];
    
        echo "<div class='details'>";
        echo "<p>Contract ID: " .$contract['idContract']. "</p>";
        echo "<p>Worker Name: ". $contract['workerFname'] . " " . $contract['workerLname'] ."</p>";
        echo "<p>Employer Name: " .$contract['employerFname']. " ". $contract['employerLname'] ."</p>";
        echo "<p>Paypal Account: " .$contract['workerPaypalEmail']. "</p>";
        echo "<p>Status: " . $status . "</p>";
        echo "<p>Amt Paid by Employer: " . $amountPaidEmp . "</p>";
        echo "<p>End of Contract: " . $endDate . "</p>";
        echo "<p>Salary Amount: P" .$workerSalaryAmount. "</p>";

        echo "</div>";

            
        
    }
    
    echo "</tbody>";
    echo "</table>";
    echo "</div>";

    echo "</div>";

    $html = ob_get_clean();
    // Load HTML content into dompdf
    $dompdf->loadHtml($html);

    // Set paper size (optional)
    $dompdf->setPaper('A4', 'portrait');

    // Render PDF (first step)
    $dompdf->render();


    // Output the generated PDF (second step - create a download link)
    $dompdf->stream('house_connect_receipt.pdf', array('Attachment' => 0));
    exit;
}
?>
