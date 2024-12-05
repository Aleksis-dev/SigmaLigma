<?php
// Set up the SQLite database connection
$db = new SQLite3('currency_conversions.db');

// Create table if it doesn't exist
$db->exec('CREATE TABLE IF NOT EXISTS conversions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    eur_amount FLOAT,
    currency_code TEXT,
    conversion_rate FLOAT,
    converted_amount FLOAT,
    conversion_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)');

// Sanitize and get the data from the front-end (e.g., via AJAX or form submission)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Use filter_input to sanitize the inputs
    $eurAmount = filter_input(INPUT_POST, 'eur_amount', FILTER_VALIDATE_FLOAT);
    $currencyCode = filter_input(INPUT_POST, 'currency_code', FILTER_SANITIZE_STRING);
    $conversionRate = filter_input(INPUT_POST, 'conversion_rate', FILTER_VALIDATE_FLOAT);

    if ($eurAmount === false || $currencyCode === false || $conversionRate === false) {
        echo 'Invalid input data';
        exit;  // Exit if any input is invalid
    }

    // Calculate the converted amount
    $convertedAmount = $eurAmount * $conversionRate;

    // Prepare the SQL statement to insert the conversion result into the database
    $stmt = $db->prepare('INSERT INTO conversions (eur_amount, currency_code, conversion_rate, converted_amount) VALUES (:eur_amount, :currency_code, :conversion_rate, :converted_amount)');
    $stmt->bindValue(':eur_amount', $eurAmount, SQLITE3_FLOAT);
    $stmt->bindValue(':currency_code', $currencyCode, SQLITE3_TEXT);
    $stmt->bindValue(':conversion_rate', $conversionRate, SQLITE3_FLOAT);
    $stmt->bindValue(':converted_amount', $convertedAmount, SQLITE3_FLOAT);

    // Execute the query and check if the insertion was successful
    if ($stmt->execute()) {
        echo 'Conversion saved successfully!';
    } else {
        echo 'Error saving conversion!';
    }
} else {
    echo 'Invalid request method!';
}
?>