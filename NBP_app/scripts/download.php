<?php
require_once '../db/db_connect.php';

$dbConnection = DatabaseConnection::getInstance();
$conn = $dbConnection->getConnection();

try {
    // Get all records from the conversion_logs table
    $selectQuery = "SELECT * FROM conversion_logs";
    $result = $conn->query($selectQuery);

    if ($result->num_rows > 0) {
        // CSV filename
        $filename = 'conversion_logs.csv';

        // Create and write data to the CSV file
        $file = fopen($filename, 'w');

        // Column headers
        $headers = array('ID', 'Source Currency', 'Target Currency', 'Amount', 'Converted Amount', 'Timestamp');
        fputcsv($file, $headers);

        // Write conversion data
        while ($row = $result->fetch_assoc()) {
            $rowData = array(
                $row['id'],
                $row['source_currency'],
                $row['target_currency'],
                $row['amount'],
                $row['converted_amount'],
                $row['timestamp']
            );
            fputcsv($file, $rowData);
        }

        fclose($file);

        // Set HTTP headers for file download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Pragma: no-cache');
        readfile($filename);

        // Delete the CSV file
        unlink($filename);
    } else {
        echo 'No conversion logs found.';
    }
} catch (Exception $e) {
    echo "Error downloading conversion logs: " . $e->getMessage();
}

$conn->close();
?>
