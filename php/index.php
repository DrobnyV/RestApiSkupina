<?php
require 'db.php';

// Parse the request
$requestMethod = $_SERVER['REQUEST_METHOD'];
$path = explode('/', trim($_SERVER['PATH_INFO'], '/'));

// Handle Firms endpoints
if ($path[0] === 'firms') {
    require 'firms.php';
    exit;
}

// Handle Contacts endpoints
if ($path[0] === 'contacts') {
    require 'contacts.php';
    exit;
}

// Default response for unknown routes
http_response_code(404);
echo json_encode(['error' => 'Endpoint not found']);
?>
