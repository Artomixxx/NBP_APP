<?php
require_once 'db/db_connect.php';

/**
 * Class CurrencyRatesUpdater
 * Updates the currency rates in the database based on API data.
 */
class CurrencyRatesUpdater {
    private $db;

    /**
     * CurrencyRatesUpdater constructor.
     * Initializes the CurrencyRateDatabase instance.
     */
    public function __construct() {
        $database = DatabaseConnection::getInstance();
        $this->db = new CurrencyRateDatabase($database);
    }

    /**
     * Updates the currency rates in the database.
     *
     * @param string $table The table name for API data.
     */
    public function updateCurrencyRates($table) {
        $url = "http://api.nbp.pl/api/exchangerates/tables/{$table}/";
        $response = file_get_contents($url);

        if ($response !== false) {
            $data = json_decode($response, true);

            if (is_array($data) && !empty($data)) {
                $rates = $data[0]['rates'];

                if (is_array($rates) && !empty($rates)) {
                    $this->db->clearCurrencyRates();
                    $this->db->insertCurrencyRate('Polski złoty', 'PLN', 1.0);

                    foreach ($rates as $rate) {
                        $currency = isset($rate['currency']) ? $this->sanitizeInput($rate['currency']) : '';
                        $code = isset($rate['code']) ? $this->sanitizeInput($rate['code']) : '';
                        $mid = isset($rate['mid']) ? floatval($rate['mid']) : 0.0;

                        if (!empty($currency) && !empty($code) && $mid > 0.0) {
                            $this->db->insertCurrencyRate($currency, $code, $mid);
                        } else {
                            echo 'Invalid currency rate data.';
                        }
                    }
                } else {
                    echo 'No currency rates available.';
                }
            } else {
                echo 'Invalid API response.';
            }
        } else {
            echo 'Failed to fetch currency rates.';
        }
    }

    /**
     * Sanitizes the input by removing unnecessary characters and applying appropriate filters.
     *
     * @param string $input The input to sanitize.
     * @return string The sanitized input.
     */
    private function sanitizeInput($input) {
        $input = trim($input); // Remove leading/trailing whitespace

   
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

        return $input;
    }
}
?>
