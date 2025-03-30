<?php
require_once '../orm/rb.php';
ORM::init();
session_start();
header('Content-Type: application/json');
try {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (empty($data['email']) || empty($data['password'])) {
        throw new Exception('All fields are required');
    }
    $existing = R::findOne('users', 'email = ?', [$data['email']]);
    if ($existing) {
        throw new Exception('Email already exists');
    }
    $user = R::dispense('users');
    $user->name = $data['name'];
    $user->email = $data['email'];
    $user->password_hash = password_hash($data['password'], PASSWORD_DEFAULT);
    $user->registration_date = R::isoDateTime();
    R::store($user);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
?>