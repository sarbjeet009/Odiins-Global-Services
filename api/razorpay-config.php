<?php
/**
 * Razorpay Public Configuration
 * Endpoint: GET /api/razorpay-config (or /api/razorpay-config.php)
 * Only returns the public key_id, NEVER the secret.
 */
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Load from environment or local .env fallback
$keyId = getenv('RAZORPAY_KEY_ID');

if (!$keyId && file_exists(__DIR__ . '/../.env')) {
    $envLines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($envLines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($k, $v) = explode('=', $line, 2) + [null, null];
        if (trim($k) === 'RAZORPAY_KEY_ID') {
            $keyId = trim($v);
            break;
        }
    }
}

echo json_encode([
    'key_id' => $keyId ?: 'rzp_test_Tkzq8gOUgUbjmb'
]);
