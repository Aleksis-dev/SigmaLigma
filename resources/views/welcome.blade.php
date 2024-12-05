<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/app.css">
</head>

<body>
    <div class="calc">
        <label for="currency">EUR Converter</label><br>
        <br>
        <label for="value1">EUR: </label>
        <input type="text" name="value1" id="value1"><br><br>
        <select name="currency" id="currency">
            <option value="USD 1.0530">🇺🇸 USD</option>
            <option value="GBP 0.8276">🏴 GBP</option>
            <option value="CHF 0.9314">🇨🇭 CHF</option>
        </select><br><br>
        <button id="calcBttn">Calculate</button>
        <p id="output">Currency value: NaN</p>
    </div>

    <script>
        let output = document.getElementById("output")
        let clickButton = document.getElementById("calcBttn")

        clickButton.addEventListener("click", handleClick)

        function handleClick() {
    let value1 = document.getElementById("value1");
    let currency = document.getElementById("currency");
    let calculation = currency.value.slice(3);  // The conversion rate

    let eurAmount = parseFloat(value1.value);  // Convert the input to a float
    let currencyCode = currency.value.slice(0, 3);  // Extract the currency code (USD, GBP, CHF)
    let conversionRate = parseFloat(calculation);  // The conversion rate

    if (isNaN(eurAmount)) {
        alert('Please enter a valid EUR amount');
        return; // Exit if the EUR amount is invalid
    }

    // Update the output on the page
    returnValueToPage(eurAmount, currencyCode, conversionRate);

    // Send data to PHP backend to store in SQLite
    fetch('convert.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `eur_amount=${eurAmount}&currency_code=${currencyCode}&conversion_rate=${conversionRate}`
    })
    .then(response => response.text())
    .then(data => {
        console.log(data);  // Optionally log success or errors
    })
    .catch(error => console.error('Error:', error));
}

function returnValueToPage(x, y, z) {
    let convertedValue = (x * z).toFixed(2);  // Fix to two decimal places
    output.innerHTML = `Currency value: ${convertedValue} ${y}`;
}
    </script>

</body>

</html>

