<?php
/**
 * ODIINS GLOBAL SERVICES - TELEGRAM WEBHOOK RELAY
 * Endpoint: https://www.odiins.in/telegram-webhook.php
 * 
 * Solves the Google Apps Script 302 redirect limitation by receiving Telegram's webhook,
 * forwarding it to Google Apps Script with CURLOPT_FOLLOWLOCATION, and returning HTTP 200 OK.
 */

// Respond immediately with 200 OK to Telegram
header('Content-Type: application/json');

$rawInput = file_get_contents('php://input');

if (empty($rawInput)) {
    http_response_code(200);
    echo json_encode([
        "ok" => true,
        "message" => "Odiins Telegram AI Webhook Relay is Active and Healthy"
    ]);
    exit;
}

$gasUrl = 'https://script.google.com/macros/s/AKfycbxDILgSywLAoCkiHEs2s2GpBLPINg5kIEHKurjwMy60gJrckHlRIGrvwr5aJJOfd0je/exec';

$ch = curl_init($gasUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $rawInput);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

$gasResponse = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

http_response_code(200);
echo json_encode([
    "ok" => true,
    "forwarded_status" => $httpCode
]);
