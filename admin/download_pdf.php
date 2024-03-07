<?php
// Include necessary files and establish a database connection
require_once('../database/connect.php');

// Check if the form is submitted and the button for downloading PDF is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['downloadPDF'])) {
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
        </style>";

    
    echo "<div class='pdf-container'>";
    echo "<div style='text-align:center;'>";
    echo "<img src='$logoBase64' alt='Logo' style='width: 100px; height: 100px;'>";
    echo "<h1>House Connect</h1>";
    echo "<h2>Report and Analytics</h2>";
    echo "</div>";

    function displayRevenueSummary($conn) {
        $sql = "SELECT 
                    SUM(
                        CASE 
                            WHEN paymentStatus = 'Successful' AND DATE(submitted_at) = CURDATE() THEN amount * 0.1 
                            ELSE 0 
                        END
                    ) AS revenue_today,
                    SUM(
                        CASE 
                            WHEN paymentStatus = 'Successful' AND YEARWEEK(submitted_at) = YEARWEEK(CURDATE()) THEN amount * 0.1
                            ELSE 0 
                        END
                    ) AS revenue_this_week,
                    SUM(
                        CASE 
                            WHEN paymentStatus = 'Successful' AND YEAR(submitted_at) = YEAR(CURDATE()) AND MONTH(submitted_at) = MONTH(CURDATE()) THEN amount * 0.1 
                            ELSE 0 
                        END
                    ) AS revenue_this_month,
                    SUM(
                        CASE 
                            WHEN paymentStatus = 'Successful' AND YEAR(submitted_at) = YEAR(CURDATE()) THEN amount * 0.1 
                            ELSE 0 
                        END
                    ) AS revenue_this_year
                FROM 
                    employer_payment";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        echo "<div class='details'>";
        echo "<h3 class='title'>Summary Report of Revenue</h3>";
        echo "<hr>";
        echo "<p>Daily: P" . $row['revenue_today'] . "</p>";
        echo "<p>Weekly: P" . $row['revenue_this_week'] . "</p>";
        echo "<p>Monthly: P" . $row['revenue_this_month'] . "</p>";
        echo "<p>Yearly: P" . $row['revenue_this_year'] . "</p>";
        echo "</div>";
    }

    function displayWorkerTypes($conn) {
        $sql = "SELECT workerType, COUNT(*) AS typeCount
                FROM worker
                GROUP BY workerType";
        $result = mysqli_query($conn, $sql);
    
        $totalWorkers = 0;
    
        echo "<div class='details'>";
        echo "<h3 class='title'>Workers</h3>";
        echo "<hr>";
        echo "<ul>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<li>" . $row['workerType'] . ": " . $row['typeCount'] . "</li>";
            $totalWorkers += $row['typeCount'];
        }
        echo "<li>Total Workers: $totalWorkers</li>";
        echo "</ul>";
        echo "</div>";
    }
    

    function displayContractStatus($conn) {
        $sql = "SELECT COUNT(*) AS totalContracts,
                    SUM(CASE WHEN contractStatus = 'Active' THEN 1 ELSE 0 END) AS activeContracts,
                    SUM(CASE WHEN contractStatus = 'Pending' THEN 1 ELSE 0 END) AS pendingContracts,
                    SUM(CASE WHEN contractStatus = 'Completed' THEN 1 ELSE 0 END) AS completedContracts,
                    SUM(CASE WHEN contractStatus = 'Canceled' THEN 1 ELSE 0 END) AS canceledContracts
                FROM contract";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        echo "<div class='details'>";
        echo "<h3 class='title'>Contracts</h3>";
        echo "<hr>";
        echo "<ul>";
        echo "<li>Total: " . $row['totalContracts'] . "</li>";
        echo "<li>Active: " . $row['activeContracts'] . "</li>";
        echo "<li>Pending: " . $row['pendingContracts'] . "</li>";
        echo "<li>Completed: " . $row['completedContracts'] . "</li>";
        echo "<li>Canceled: " . $row['canceledContracts'] . "</li>";
        echo "</ul>";
        echo "</div>";
    }

    function displayVerificationStatus($conn) {
        $sqlWorkers = "SELECT COUNT(*) AS totalWorkers, 
                            SUM(CASE WHEN w.verifyStatus = 'Verified' THEN 1 ELSE 0 END) AS verifiedWorkers,
                            SUM(CASE WHEN w.verifyStatus != 'Verified' THEN 1 ELSE 0 END) AS nonVerifiedWorkers
                    FROM worker w";
        $resultWorkers = mysqli_query($conn, $sqlWorkers);
        $rowWorkers = mysqli_fetch_assoc($resultWorkers);

        $sqlEmployers = "SELECT COUNT(*) AS totalEmployers, 
                            SUM(CASE WHEN e.verifyStatus = 'Verified' THEN 1 ELSE 0 END) AS verifiedEmployers,
                            SUM(CASE WHEN e.verifyStatus != 'Verified' THEN 1 ELSE 0 END) AS nonVerifiedEmployers
                        FROM employer e";
        $resultEmployers = mysqli_query($conn, $sqlEmployers);
        $rowEmployers = mysqli_fetch_assoc($resultEmployers);

        echo "<div class='details'>";
        echo "<h3 class='title'>Users</h3>";
        echo "<hr>"; 
        echo "<ul>";
        echo "<li>Total Users: " . ($rowWorkers['totalWorkers'] + $rowEmployers['totalEmployers']) . "</li>";
        echo "<li>Verified Workers: " . $rowWorkers['verifiedWorkers'] . "</li>";
        echo "<li>Non-verified Workers: " . $rowWorkers['nonVerifiedWorkers'] . "</li>";
        echo "<li>Verified Employers: " . $rowEmployers['verifiedEmployers'] . "</li>";
        echo "<li>Non-verified Employers: " . $rowEmployers['nonVerifiedEmployers'] . "</li>";
        echo "</ul>";
        echo "</div>";
    }

    // Call the functions
    displayRevenueSummary($conn);
    displayWorkerTypes($conn);
    displayContractStatus($conn);
    displayVerificationStatus($conn);

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
