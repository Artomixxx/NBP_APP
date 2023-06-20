# NBP_APP
NBP Currency Rates
NBP Currency Rates is an application that allows users to convert currencies based on the current exchange rates provided by the National Bank of Poland (NBP).

Description
The application enables users to convert an amount from one currency to another using the current exchange rates. Users can select the source and target currencies and enter the amount, and they will receive the converted value.
The application retrieves currency rate data from the NBP API and stores it in a local database. The exchange rates are automatically updated upon application startup.
Additionally, the application keeps a history of conversions, which users can browse. The conversion history can also be cleared upon user request.

Features
Currency conversion based on the current NBP rates
Automatic update of currency exchange rates on application startup
Browsing conversion history
Clearing conversion history
Downloading conversion history as a file


System Requirements
PHP 7.0 or higher
MySQL database or any compatible database
Internet access to retrieve currency rate data from the NBP API

Installation

Set up the database connection by editing the db/db_connect.php file and adjusting the connection parameters accordingly.

Import the database structure by running the db_import/nbp_currency_app.sql script, which will create the necessary tables.

Run the application on a PHP-enabled server.

The application is now ready to use. Open it in a web browser by entering the server's URL.

Author
The NBP Currency Rates application was created by Cezary Żak Contact: cezary.zakk78@gmail.com.

