<?php
// customers-api.php - Simple API for customer database
// Upload this file to your server at: https://polynor.bg/order-form/customers-api.php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$jsonFile = __DIR__ . '/customers.json';

// Initialize file if it doesn't exist
if (!file_exists($jsonFile)) {
    file_put_contents($jsonFile, json_encode(['customers' => []], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// GET - Read customers
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = file_get_contents($jsonFile);
    echo $data;
    exit();
}

// POST - Save customers
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if ($data === null) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON']);
        exit();
    }
    
    // Validate structure
    if (!isset($data['customers']) || !is_array($data['customers'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid data structure']);
        exit();
    }
    
    // Save to file
    $result = file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    if ($result === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to save data']);
        exit();
    }
    
    echo json_encode(['success' => true, 'message' => 'Data saved successfully']);
    exit();
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
?>
