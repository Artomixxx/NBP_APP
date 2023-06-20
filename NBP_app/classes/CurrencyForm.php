<?php
require_once 'CurrencyConverter.php';

/**
 * Class CurrencyForm
 * Handles the submission and processing of the currency conversion form.
 */
class CurrencyForm {
    private $converter;

    /**
     * CurrencyForm constructor.
     * Initializes the CurrencyConverter instance.
     */
    public function __construct() {
        $this->converter = new CurrencyConverter();
    }

    /**
     * Handles the submission of the currency conversion form.
     * Validates and processes the input data.
     */
    public function handleFormSubmission() {
        if (isset($_POST['submit'])) {
            $amount = $this->sanitizeInput($_POST['amount']);
            $sourceCurrency = $this->sanitizeInput($_POST['source']);
            $targetCurrency = $this->sanitizeInput($_POST['target']);

            if ($this->validateInput($amount)) {
                $convertedAmount = $this->converter->convertCurrency($amount, $sourceCurrency, $targetCurrency);

                if ($convertedAmount !== null) {
                    $formattedAmount = number_format($convertedAmount, 2);
                    echo "<p>" . htmlspecialchars($amount, ENT_QUOTES, 'UTF-8') . " " . htmlspecialchars($sourceCurrency, ENT_QUOTES, 'UTF-8') . " is equal to " . htmlspecialchars($formattedAmount, ENT_QUOTES, 'UTF-8') . " " . htmlspecialchars($targetCurrency, ENT_QUOTES, 'UTF-8') . "</p>";
                } else {
                    echo 'Currency conversion failed.';
                }
            } else {
                echo 'Invalid input.';
            }
        }
    }

    /**
     *
     * @param string $input The input data to sanitize.
     * @return string The sanitized input.
     */
    private function sanitizeInput($input) {

        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Validates the input data for currency conversion.
     *
     * @param float $amount The amount to convert.
     */
    private function validateInput($amount) {

        if (!is_numeric($amount) || $amount <= 0) {
            return false;
        }


        return true;
    }
}
?>
