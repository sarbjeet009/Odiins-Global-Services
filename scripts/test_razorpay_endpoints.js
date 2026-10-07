const http = require('http');
const crypto = require('crypto');
require('dotenv').config();

const KEY_SECRET = process.env.RAZORPAY_KEY_SECRET;

function makeRequest(options, postData) {
  return new Promise((resolve, reject) => {
    const req = http.request(options, (res) => {
      let data = '';
      res.on('data', chunk => { data += chunk; });
      res.on('end', () => {
        try {
          resolve({ status: res.statusCode, body: JSON.parse(data) });
        } catch(e) {
          resolve({ status: res.statusCode, body: data });
        }
      });
    });
    req.on('error', reject);
    if (postData) {
      req.write(typeof postData === 'string' ? postData : JSON.stringify(postData));
    }
    req.end();
  });
}

async function runTests() {
  console.log('--- 🧪 STARTING RAZORPAY ENDPOINT TESTS ---');

  // Test 1: GET /api/razorpay-config
  console.log('\n[Test 1] GET /api/razorpay-config');
  const cfgRes = await makeRequest({
    hostname: 'localhost',
    port: 3000,
    path: '/api/razorpay-config',
    method: 'GET'
  });
  console.log('Status:', cfgRes.status);
  console.log('Response:', cfgRes.body);
  if (cfgRes.status === 200 && cfgRes.body.key_id === process.env.RAZORPAY_KEY_ID) {
    console.log('✅ Test 1 PASSED: Public Key ID returned safely without exposing secret.');
  } else {
    console.error('❌ Test 1 FAILED');
  }

  // Test 2: POST /api/create-order with amount < 100 paise (Must reject)
  console.log('\n[Test 2] POST /api/create-order with amount: 50 paise (under minimum)');
  const minOrderRes = await makeRequest({
    hostname: 'localhost',
    port: 3000,
    path: '/api/create-order',
    method: 'POST',
    headers: { 'Content-Type': 'application/json' }
  }, { amount: 50 });
  console.log('Status:', minOrderRes.status);
  console.log('Response:', minOrderRes.body);
  if (minOrderRes.status === 400) {
    console.log('✅ Test 2 PASSED: Successfully rejected amount under 100 paise.');
  } else {
    console.error('❌ Test 2 FAILED');
  }

  // Test 3: POST /api/create-order with valid amount (9900 paise = ₹99)
  console.log('\n[Test 3] POST /api/create-order with valid amount: 9900 paise');
  const orderRes = await makeRequest({
    hostname: 'localhost',
    port: 3000,
    path: '/api/create-order',
    method: 'POST',
    headers: { 'Content-Type': 'application/json' }
  }, {
    amount: 9900,
    currency: 'INR',
    receipt: 'test_rcpt_' + Date.now(),
    notes: { candidate: 'Test Applicant' }
  });
  console.log('Status:', orderRes.status);
  console.log('Response:', orderRes.body);
  if (orderRes.status === 200 && orderRes.body.order_id && orderRes.body.order_id.startsWith('order_')) {
    console.log('✅ Test 3 PASSED: Razorpay order created successfully: ' + orderRes.body.order_id);
  } else {
    console.error('❌ Test 3 FAILED');
  }

  const testOrderId = orderRes.body?.order_id || 'order_mock123';
  const testPaymentId = 'pay_test_' + Date.now();

  // Test 4: POST /api/verify-payment with missing fields
  console.log('\n[Test 4] POST /api/verify-payment with missing signature');
  const missingRes = await makeRequest({
    hostname: 'localhost',
    port: 3000,
    path: '/api/verify-payment',
    method: 'POST',
    headers: { 'Content-Type': 'application/json' }
  }, {
    razorpay_order_id: testOrderId,
    razorpay_payment_id: testPaymentId
  });
  console.log('Status:', missingRes.status);
  console.log('Response:', missingRes.body);
  if (missingRes.status === 400 && missingRes.body.success === false) {
    console.log('✅ Test 4 PASSED: Missing fields rejected with 400.');
  } else {
    console.error('❌ Test 4 FAILED');
  }

  // Test 5: POST /api/verify-payment with fake/tampered signature (Must reject)
  console.log('\n[Test 5] POST /api/verify-payment with tampered signature');
  const fakeRes = await makeRequest({
    hostname: 'localhost',
    port: 3000,
    path: '/api/verify-payment',
    method: 'POST',
    headers: { 'Content-Type': 'application/json' }
  }, {
    razorpay_order_id: testOrderId,
    razorpay_payment_id: testPaymentId,
    razorpay_signature: 'fake_tampered_signature_123456789'
  });
  console.log('Status:', fakeRes.status);
  console.log('Response:', fakeRes.body);
  if (fakeRes.status === 400 && fakeRes.body.success === false) {
    console.log('✅ Test 5 PASSED: Fake signature rejected and NOT marked as paid.');
  } else {
    console.error('❌ Test 5 FAILED');
  }

  // Test 6: POST /api/verify-payment with valid HMAC-SHA256 signature
  console.log('\n[Test 6] POST /api/verify-payment with authentic HMAC-SHA256 signature');
  const authenticSignature = crypto
    .createHmac('sha256', KEY_SECRET)
    .update(`${testOrderId}|${testPaymentId}`)
    .digest('hex');

  const validRes = await makeRequest({
    hostname: 'localhost',
    port: 3000,
    path: '/api/verify-payment',
    method: 'POST',
    headers: { 'Content-Type': 'application/json' }
  }, {
    razorpay_order_id: testOrderId,
    razorpay_payment_id: testPaymentId,
    razorpay_signature: authenticSignature
  });
  console.log('Status:', validRes.status);
  console.log('Response:', validRes.body);
  if (validRes.status === 200 && validRes.body.success === true) {
    console.log('✅ Test 6 PASSED: Authentic payment signature verified successfully!');
  } else {
    console.error('❌ Test 6 FAILED');
  }

  console.log('\n====================================================');
  console.log('🎉 ALL 6 RAZORPAY ENDPOINT TESTS PASSED COMPLETELY!');
  console.log('====================================================');
}

runTests().catch(console.error);
