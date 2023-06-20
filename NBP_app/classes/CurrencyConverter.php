<?php
require_once 'db/db_connect.php';
require_once 'CurrencyRateDatabase.php';
require_once 'CurrencyConversionLogger.php';

/**
 * Class CurrencyConverter
 * Handles currency conversion operations.
 */
class CurrencyConverter {
    private $db;
    private $logger;

    /**
     * CurrencyConverter constructor.
     * Initializes the currency rate database and conversion logger.
     */
    public function __construct() {
        $database = DatabaseConnection::getInstance();
        $this->db = new CurrencyRateDatabase($database);
        $this->logger = new CurrencyConversionLogger($database);
    }

    /**
     * Converts an amount from the source currency to the target currency.
     *
     * @param float $amount The amount to convert.
     * @param string $sourceCurrency The source currency code.
     * @param string $targetCurrency The target currency code.
     * @return float|null The converted amount or null if conversion is not possible.
     */
    public function convertCurrency($amount, $sourceCurrency, $targetCurrency) {
        if ($this->validateInput($amount)) {
            $sourceRate = $this->db->getCurrencyRate($sourceCurrency);
            $targetRate = $this->db->getCurrencyRate($targetCurrency);

            if ($sourceRate !== null && $targetRate !== null) {
                $convertedAmount = ($sourceRate / $targetRate) * $amount;

                // Log the conversion result in the database
                $this->logger->logConversion($amount, $sourceCurrency, $targetCurrency, $convertedAmount);

                return $convertedAmount;
            }
        }

        return null;
    }

    /**
     * Validates the input data for currency conversion.
     *
     * @param float $amount The amount to convert.
     * @return bool True if the input is valid, false otherwise.
     */
    private function validateInput($amount) {
        // Check if the amount is numeric and greater than zero
        if (!is_numeric($amount) || $amount <= 0) {
            return false;
        }

        return true;
    }
}
?>
