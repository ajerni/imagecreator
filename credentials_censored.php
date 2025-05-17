<?php
// Set headers to prevent caching and specify JSON content
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

// Credentials for n8n webhook
$credentials = [
    'username' => 'your_n8n_username',
    'password' => 'your_n8n_password'
];

// Return credentials as JSON
echo json_encode($credentials);
?> 