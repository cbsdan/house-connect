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
        $idWorkerSalary = $_POST['idWorkerSalary'];

        $salaryDetails = getWorkerSalaryAndPaymentDetails($_SESSION['idUser'], $idContract, $idWorkerSalary);
        $salaryDetails = $salaryDetails[0];
        $contract = getContractList($idContract);
        $contract = $contract[0];
    } else{
        header('Location: ./salary_payment.php');
    }

    echo "<style>
    @page {
                margin: 50px; /* Adjust the margin size as needed */
            }
            .pdf-container {
                border: 1px solid black; /* Add border */
                padding: 20px; /* Add padding */
            }
            .fw-bold {
                font-weight: 600;
            }
        </style>";

    
    echo "<div class='pdf-container'>";
    echo "<div style='text-align:center;'>";
    echo "<img src='$logoBase64' alt='Logo' style='width: 100px; height: 100px;'>";
    echo "<h1>House Connect</h1>";
    echo "<h2>Worker Payslip</h2>";
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
        $taxAmt = $salaryDetails['tax_amount'];
        $netPay = $workerSalaryAmount - $taxAmt;

        echo "<div class='details'>";
        echo "<p><span class='fw-bold'>Contract ID: </span>" .$contract['idContract']. "</p>";
        echo "<p><span class='fw-bold'>Worker Name: </span>". $contract['workerFname'] . " " . $contract['workerLname'] ."</p>";
        echo "<p><span class='fw-bold'>Employer Name: </span>" .$contract['employerFname']. " ". $contract['employerLname'] ."</p>";
        echo "<p><span class='fw-bold'>Paypal Account: </span>" .$contract['workerPaypalEmail']. "</p>";
        echo "<p><span class='fw-bold'>Status: </span>" . $status . "</p>";
        echo "<p><span class='fw-bold'>Amt Paid by Employer: </span>" . $amountPaidEmp . "</p>";
        echo "<p><span class='fw-bold'>End of Contract: </span>" . $endDate . "</p>";
        echo "<p><span class='fw-bold'>Salary Amount: </span>P" .$workerSalaryAmount. "</p>";
        echo "<p><span class='fw-bold'>Tax Amount: </span>P" .$taxAmt. "</p>";
        echo "<p><span class='fw-bold'>Net Pay: </span>P" .$netPay. "</p>";

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
