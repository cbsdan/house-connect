<?php
// Include necessary files and establish a database connection
require_once('../database/connect.php');


// Check if the form is submitted and the button for downloading PDF is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['print'])) {
    // Include the dompdf library
    require_once '../dompdf-master/vendor/autoload.php';

    // Create a new instance of the Dompdf class
    $dompdf = new \Dompdf\Dompdf();

    // Fetch HTML content of the section
    ob_start();
    
    $logoPath = '../img/logo.png';
    $logoData = file_get_contents($logoPath);
    $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);

    echo "<style>
    @page {
        margin: 50px; /* Adjust the margin size as needed */
    }
    .pdf-container {
        border: 1px solid black; /* Add border */
        padding: 20px; /* Add padding */
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #ba4b2f;
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #ba4b2f;
        color: white;
    }
</style>";


    
    echo "<div class='pdf-container'>";
    echo "<div style='text-align:center;'>";
    echo "<img src='$logoBase64' alt='Logo' style='width: 100px; height: 100px;'>";
    echo "<h1>House Connect</h1>";
    echo "<h2>Contract Reports</h2>";
    echo "</div>";

    // Fetch the contract details
    $contracts = getContractList();
    echo "<table>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Contract ID</th>";
    echo "<th>Status</th>";
    echo "<th>Employer Name</th>";
    echo "<th>Worker Name</th>";
    echo "<th>Worker Type</th>";
    echo "<th>Date Created</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach($contracts as $contract) {
        echo "<tr>";
        echo "<td>" . $contract['idContract'] . "</td>";
        echo "<td>" . $contract['contractStatus'] . "</td>";
        echo "<td>" . $contract['employerFname'] . " " . $contract['employerLname'] . "</td>";
        echo "<td>" . $contract['workerFname'] . " " . $contract['workerLname'] . "</td>";
        echo "<td>" . $contract['workerType'] . "</td>";
        echo "<td>" . $contract['date_created'] . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
    echo "</div>";


   

    $html = ob_get_clean();

    // Load HTML content into dompdf
    $dompdf->loadHtml($html);

    // Set paper size (optional)
    $dompdf->setPaper('A4', 'portrait');

    // Render PDF (first step)
    $dompdf->render();

    // Output the generated PDF (second step - create a download link)
    $dompdf->stream('house_connect_reports.pdf', array('Attachment' => 0));
    exit;
}
?>
