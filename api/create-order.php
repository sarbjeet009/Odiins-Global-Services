<?php
/**
 * Razorpay Standard Checkout - Create Order Endpoint
 * Endpoint: POST /api/create-order (or /api/create-order.php)
 */
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed. Must be POST.']);
    exit;
}

// Read and parse JSON request body
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON request payload.']);
    exit;
}

$amount = isset($input['amount']) ? (int)$input['amount'] : 0;
$currency = isset($input['currency']) ? strtoupper(trim($input['currency'])) : 'INR';
$receipt = isset($input['receipt']) ? trim($input['receipt']) : 'rcpt_' . time();
$notes = isset($input['notes']) && is_array($input['notes']) ? $input['notes'] : [];

// Validate amount >= 100 paise (₹1)
if ($amount < 100) {
    http_response_code(400);
    echo json_encode(['error' => 'Amount must be at least 100 paise (₹1).']);
    exit;
}

// Retrieve Credentials
$keyId = getenv('RAZORPAY_KEY_ID');
$keySecret = getenv('RAZORPAY_KEY_SECRET');

if ((!$keyId || !$keySecret) && file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $k = trim($parts[0]);
            $v = trim($parts[1]);
            if ($k === 'RAZORPAY_KEY_ID') $keyId = $v;
            if ($k === 'RAZORPAY_KEY_SECRET') $keySecret = $v;
        }
    }
}

if (!$keyId || !$keySecret) {
    http_response_code(500);
    echo json_encode(['error' => 'Razorpay credentials not configured on server.']);
    exit;
}

// Call Razorpay API: POST https://api.razorpay.com/v1/orders
$payload = json_encode([
    'amount' => $amount,
    'currency' => $currency,
    'receipt' => $receipt,
    'notes' => $notes
]);

$ch = curl_init('https://api.razorpay.com/v1/orders');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, $keyId . ':' . $keySecret);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

$response = curl_exec($ch);
$httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlErr = curl_error($ch);
curl_close($ch);

if ($curlErr) {
    http_response_code(500);
    echo json_encode(['error' => 'cURL error communicating with Razorpay: ' . $curlErr]);
    exit;
}

$respData = json_decode($response, true);

if ($httpStatus === 401) {
    http_response_code(401);
    echo json_encode(['error' => 'Razorpay authentication failed. Verify API credentials.']);
    exit;
}

if ($httpStatus >= 400 || empty($respData['id'])) {
    http_response_code(500);
    $desc = isset($respData['error']['description']) ? $respData['error']['description'] : 'Failed to create Razorpay order.';
    echo json_encode(['error' => $desc]);
    exit;
}

// Return: { order_id, amount, currency }
http_response_code(200);
echo json_encode([
    'order_id' => $respData['id'],
    'amount' => $respData['amount'],
    'currency' => $respData['currency']
]);
