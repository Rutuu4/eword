<?php
$serviceAccountPath = 'invoice-management-b8a26-7a23f4d58e54.json';

function getAccessToken($serviceAccountPath) {

    $serviceAccount = json_decode(file_get_contents($serviceAccountPath), true);
    $header = [
        'alg' => 'RS256',
        'typ' => 'JWT'
    ];
    $now = time();
    $claims = [
        'iss' => $serviceAccount['client_email'], 
        'scope' => 'https://www.googleapis.com/auth/firebase.messaging', 
        'aud' => 'https://oauth2.googleapis.com/token', 
        'exp' => $now + 3600, 
        'iat' => $now 
    ];
    $base64UrlHeader = base64UrlEncode(json_encode($header));
    $base64UrlClaims = base64UrlEncode(json_encode($claims));

    $unsignedJwt = "$base64UrlHeader.$base64UrlClaims";

    $privateKey = $serviceAccount['private_key'];
    $signature = '';
    openssl_sign($unsignedJwt, $signature, $privateKey, OPENSSL_ALGO_SHA256);

    $base64UrlSignature = base64UrlEncode($signature);

    $jwt = "$unsignedJwt.$base64UrlSignature";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
        'assertion' => $jwt
    ]));

    $response = curl_exec($ch);
    if ($response === FALSE) {
        die('Error fetching access token: ' . curl_error($ch));
    }
    curl_close($ch);

    $data = json_decode($response, true);
    return $data['access_token'];
}

function base64UrlEncode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

$accessToken = getAccessToken($serviceAccountPath);

$url = 'https://fcm.googleapis.com/v1/projects/invoice-management-b8a26/messages:send';

$message = $mesage;
$title =$title;
$img = $imageUrl;
$id = $last_idd;
$flag = 'custom';
$device_tokens = $divice_token_android;
$notification = [
    'title' => $title,
    'body' => $message,
    'image' => $img
];

foreach ($device_tokens as $device_token) {
    $payload = [
        'message' => [
            'token' => $device_token,
            'notification' => $notification,
            'data' => [
                'id' => (string)$id, 
                'flag' => (string)$flag,
                'title' => $title,
                'body' => $message,
                'image' => $img
            ]
        ]
    ];

    $headers = [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    $result = curl_exec($ch);

    //print_r($result);
}
?>
