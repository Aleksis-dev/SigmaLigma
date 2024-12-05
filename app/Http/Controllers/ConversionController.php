<?php

// app/Http/Controllers/ConversionController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SQLite3;

class ConversionController extends Controller
{
    public function convert(Request $request)
    {
        // Get data from the front-end
        $eurAmount = $request->input('eur_amount');
        $currencyCode = $request->input('currency_code');
        $conversionRate = $request->input('conversion_rate');

        // Calculate the converted amount
        $convertedAmount = $eurAmount * $conversionRate;

        // Set up the SQLite database connection
        $db = new SQLite3(storage_path('app/database/currency_conversions.db'));

        // Insert the conversion result into the database
        $stmt = $db->prepare('INSERT INTO conversions (eur_amount, currency_code, conversion_rate, converted_amount) VALUES (:eur_amount, :currency_code, :conversion_rate, :converted_amount)');
        $stmt->bindValue(':eur_amount', $eurAmount, SQLITE3_FLOAT);
        $stmt->bindValue(':currency_code', $currencyCode, SQLITE3_TEXT);
        $stmt->bindValue(':conversion_rate', $conversionRate, SQLITE3_FLOAT);
        $stmt->bindValue(':converted_amount', $convertedAmount, SQLITE3_FLOAT);

        if ($stmt->execute()) {
            return response()->json(['message' => 'Conversion saved successfully!']);
        } else {
            return response()->json(['message' => 'Error saving conversion!'], 500);
        }
    }
}
