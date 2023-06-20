<?php
// Enable error reporting and display errors on the page
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Required files and classes
require_once 'db/db_connect.php';
require_once 'classes/CurrencyForm.php';
require_once 'classes/CurrencyRatesUpdater.php';
require_once 'classes/CurrencyConverter.php';
require_once 'classes/CurrencyConversionLogger.php';

// Database connection initialization
$dbConnection = DatabaseConnection::getInstance();
$conn = $dbConnection->getConnection();

// Create a CurrencyForm object
$form = new CurrencyForm();

// Create a CurrencyConversionLogger object
$logger = new CurrencyConversionLogger($dbConnection);

// Table type (A, B, or C)
$table = 'A';

// Create a CurrencyRatesUpdater object
$updater = new CurrencyRatesUpdater($dbConnection);

try {
    // Update currency rates
    $updater->updateCurrencyRates($table);
} catch (Exception $e) {
    echo "Error updating currency rates: " . $e->getMessage();
}

// Query currency rates from the database
$selectQuery = "SELECT currency, code, mid FROM currency_rates";
$result = $conn->query($selectQuery);

// Handle "Clear History" button
if (isset($_POST['clear'])) {
    try {
        // Clear conversion logs
        $logger->clearConversionLogs();
        header("Location: index.php"); // Redirect to the same page after clearing history
        exit();
    } catch (Exception $e) {
        echo "Error clearing conversion history: " . $e->getMessage();
    }
}

// Handle "Download History" button
if (isset($_POST['downloadBtn'])) {
    try {
        // Redirect to the download.php file
        header('Location: scripts/download.php');
        exit();
    } catch (Exception $e) {
        echo "Error initiating download: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/styles.css">
    <title>NBP Currency Rates</title>
</head>
<body>
<h1>NBP Currency Rates</h1>

<form method="post" action="">
    <label for="amount">Amount:</label>
    <input type="text" name="amount" id="amount" required pattern="\d+(\.\d{1,2})?"><br>

    <label for="source">Source Currency:</label>
    <select name="source" id="source" required>
        <?php
        // Display options for source currency selection
        while ($row = $result->fetch_assoc()) {
            $currency = $row['currency'];
            $code = $row['code'];
            echo "<option value='" . htmlspecialchars($code, ENT_QUOTES, 'UTF-8') . "'>$currency ($code)</option>";
        }
        ?>
    </select><br>

    <label for="target">Target Currency:</label>
    <select name="target" id="target" required>
        <?php
        // Display options for target currency selection
        $result->data_seek(0); // Reset the result pointer to the beginning
        while ($row = $result->fetch_assoc()) {
            $currency = $row['currency'];
            $code = $row['code'];
            echo "<option value='" . htmlspecialchars($code, ENT_QUOTES, 'UTF-8') . "'>$currency ($code)</option>";
        }
        ?>
    </select><br>

    <input type="submit" name="submit" value="Convert">
</form>

<?php
try {
    // Handle form submission
    $form->handleFormSubmission();
} catch (Exception $e) {
    echo "Error processing form submission: " . $e->getMessage();
}

?>

<h2>Recent Conversions:</h2>
<?php
// Display recent conversion logs
$logs = $logger->getConversionLogs(10);
echo "<ul>";
foreach ($logs as $log) {
    $id = $log['id'];
    $sourceCurrency = $log['source_currency'];
    $targetCurrency = $log['target_currency'];
    $amount = $log['amount'];
    $convertedAmount = $log['converted_amount'];
    echo "<li>" . htmlspecialchars($amount, ENT_QUOTES, 'UTF-8') . " " . htmlspecialchars($sourceCurrency, ENT_QUOTES, 'UTF-8') . " is equal to " . htmlspecialchars($convertedAmount, ENT_QUOTES, 'UTF-8') . " " . htmlspecialchars($targetCurrency, ENT_QUOTES, 'UTF-8') . "</li>";
}
echo "</ul>";
?>

<form method="post" action="">
    <input type="hidden" name="clear" value="true">
    <input type="submit" name="clearBtn" value="Clear History">
</form>

<form method="post" action="">
    <input type="submit" name="downloadBtn" value="Download History">
</form>

</body>
</html>
