<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Form</title>
</head>
<body>
<h1>Test Transaction Form</h1>
<form action="{{ route('transactions.store') }}" method="POST">

    <label for="transaction_id">Transaction ID:</label>
    <input type="text" id="transaction_id" name="transaction_id" value="1" required><br><br>

    <label for="payment_method">Payment Method:</label>
    <input type="text" id="payment_method" name="payment_method" value="credit_card" required><br><br>

    <label for="amount">Amount:</label>
    <input type="number" id="amount" name="amount" value="150" step="0.01" required><br><br>

    <label for="country">Country:</label>
    <input type="text" id="country" name="country" value="FRA" required><br><br>

    <label for="currency">Currency:</label>
    <input type="text" id="currency" name="currency" value="USD" required><br><br>

    <label for="description">Description:</label>
    <input type="text" id="description" name="description" value="Payment name" required><br><br>

    <label for="success_redirect_url">Success Redirect URL:</label>
    <input type="url" id="success_redirect_url" name="success_redirect_url" value="https://example.com" required><br><br>

    <label for="fail_redirect_url">Fail Redirect URL:</label>
    <input type="url" id="fail_redirect_url" name="fail_redirect_url" value="https://example.com" required><br><br>

    <label for="type_of_calculation">Type of Calculation:</label>
    <input type="text" id="type_of_calculation" name="type_of_calculation" value="forward_with_fee" required><br><br>

    <label for="transaction_type">Transaction Type:</label>
    <input type="text" id="transaction_type" name="transaction_type" value="link" required><br><br>

    <button type="submit">Submit</button>
</form>
</body>
</html>
