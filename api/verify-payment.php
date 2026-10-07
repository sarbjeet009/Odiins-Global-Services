<?php
/**
 * Razorpay Standard Checkout - Verify Payment Signature Endpoint
 * Endpoint: POST /api/verify-payment (or /api/verify-payment.php)
 * Algorithm: HMAC-SHA256(order_id + "|" + payment_id, KEY_SECRET)
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

$orderId = isset($input['razorpay_order_id']) ? trim($input['razorpay_order_id']) : (isset($input['order_id']) ? trim($input['order_id']) : '');
$paymentId = isset($input['razorpay_payment_id']) ? trim($input['razorpay_payment_id']) : (isset($input['payment_id']) ? trim($input['payment_id']) : '');
$signature = isset($input['razorpay_signature']) ? trim($input['razorpay_signature']) : (isset($input['signature']) ? trim($input['signature']) : '');

// Validate required fields
if (empty($orderId) || empty($paymentId) || empty($signature)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Missing required payment verification fields (order_id, payment_id, signature).'
    ]);
    exit;
}

// Retrieve Secret from environment or .env
$keySecret = getenv('RAZORPAY_KEY_SECRET');

if (!$keySecret && file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        $parts = explode('=', $line, 2);
        if (count($parts) === 2 && trim($parts[0]) === 'RAZORPAY_KEY_SECRET') {
            $keySecret = trim($parts[1]);
            break;
        }
    }
}

if (!$keySecret) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'RAZORPAY_KEY_SECRET is not configured on server.'
    ]);
    exit;
}

// Algorithm: HMAC-SHA256(order_id + "|" + payment_id, KEY_SECRET)
$expectedSignature = hash_hmac('sha256', $orderId . '|' . $paymentId, $keySecret);

if (hash_equals($expectedSignature, $signature)) {
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Payment signature verified successfully.',
        'order_id' => $orderId,
        'payment_id' => $paymentId
    ]);
} else {
    // Signature mismatch: return 400, do NOT mark as paid
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Payment signature verification failed. Signature mismatch.'
    ]);
}
