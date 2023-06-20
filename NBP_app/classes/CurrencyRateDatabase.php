<?php
require_once 'db/db_connect.php';

/**
 * Class CurrencyRateDatabase
 * Handles the retrieval and manipulation of currency rates from the database.
 */
class CurrencyRateDatabase {
    private $conn;

    /**
     * CurrencyRateDatabase constructor.
     * Initializes the database connection.
     */
    public function __construct() {
        $database = DatabaseConnection::getInstance();
        $this->conn = $database->getConnection();
    }

    /**
     * Retrieves the currency rate for the specified currency code.
     *
     * @param string $currencyCode The currency code.
     * @return float|null The currency rate or null if not found.
     */
    public function getCurrencyRate($currencyCode) {
        $query = "SELECT mid FROM currency_rates WHERE code = '$currencyCode'";
        $result = $this->conn->query($query);

        if ($result->num_rows > 0) {
            $rate = $result->fetch_assoc()['mid'];
            return $rate;
        }

        return null;
    }

    /**
     * Clears all currency rates from the database.
     */
    public function clearCurrencyRates() {
        $truncateQuery = "TRUNCATE TABLE currency_rates";
        $this->conn->query($truncateQuery);
    }

    /**
     * Inserts a new currency rate into the database.
     *
     * @param string $currency The currency name.
     * @param string $code The currency code.
     * @param float $mid The currency rate.
     */
    public function insertCurrencyRate($currency, $code, $mid) {
        $insertQuery = "INSERT INTO currency_rates (currency, code, mid) VALUES ('$currency', '$code', $mid)";
        $this->conn->query($insertQuery);
    }

    /**
     * Closes the database connection.
     */
    public function closeConnection() {
        $this->conn->close();
    }
}
?>
