<?php
require_once 'db/db_connect.php';

/**
 * Class CurrencyConversionLogger
 * Handles logging currency conversion data to the database.
 */
class CurrencyConversionLogger {
    private $conn;

    /**
     * CurrencyConversionLogger constructor.
     * Initializes the database connection.
     */
    public function __construct() {
        $database = DatabaseConnection::getInstance();
        $this->conn = $database->getConnection();
    }

    /**
     * Logs a currency conversion to the database.
     *
     * @param float $amount The original amount to convert.
     * @param string $sourceCurrency The source currency code.
     * @param string $targetCurrency The target currency code.
     * @param float $convertedAmount The converted amount.
     * @throws InvalidArgumentException If the input data is invalid.
     */
    public function logConversion($amount, $sourceCurrency, $targetCurrency, $convertedAmount) {
        // Input data validation
        if (!is_numeric($amount) || !is_string($sourceCurrency) || !is_string($targetCurrency) || !is_numeric($convertedAmount)) {
            throw new InvalidArgumentException('Invalid input data');
        }

        // SQL Injection protection
        $amount = $this->conn->real_escape_string($amount);
        $sourceCurrency = $this->conn->real_escape_string($sourceCurrency);
        $targetCurrency = $this->conn->real_escape_string($targetCurrency);
        $convertedAmount = $this->conn->real_escape_string($convertedAmount);

        $insertQuery = "INSERT INTO conversion_logs (source_currency, target_currency, amount, converted_amount) VALUES ('$sourceCurrency', '$targetCurrency', $amount, $convertedAmount)";
        $this->conn->query($insertQuery);
    }

    /**
     * Retrieves the latest conversion logs from the database.
     *
     * @param int $limit The maximum number of logs to retrieve.
     * @return array An array of conversion log data.
     */
    public function getConversionLogs($limit) {
        $selectQuery = "SELECT * FROM conversion_logs ORDER BY id DESC LIMIT $limit";
        $result = $this->conn->query($selectQuery);
        $logs = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $log = [
                    'id' => $row['id'],
                    'source_currency' => $row['source_currency'],
                    'target_currency' => $row['target_currency'],
                    'amount' => $row['amount'],
                    'converted_amount' => $row['converted_amount']
                ];

                $logs[] = $log;
            }
        }

        return $logs;
    }

    /**
     * Retrieves all conversion logs from the database.
     *
     * @return array An array of conversion log data.
     */
    public function getAllConversionLogs() {
        $query = "SELECT * FROM conversion_logs ORDER BY id DESC";
        $result = $this->conn->query($query);

        $logs = [];
        while ($row = $result->fetch_assoc()) {
            $logs[] = $row;
        }

        return $logs;
    }

    /**
     * Clears all conversion logs from the database.
     */
    public function clearConversionLogs() {
        $query = "TRUNCATE TABLE conversion_logs";
        $this->conn->query($query);
    }

    /**
     * Closes the database connection.
     */
    public function closeConnection() {
        $this->conn->close();
    }
}
?>
