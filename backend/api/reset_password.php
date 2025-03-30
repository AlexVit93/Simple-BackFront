<?php
require_once '../orm/rb.php';
ORM::init();
session_start();

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (empty($data['email']) || empty($data['password'])) {
        throw new Exception('Все поля обязательны для заполнения');
    }
    
    $user = R::findOne('users', 'email = ?', [$data['email']]);
    if (!$user) {
        throw new Exception('Пользователь с таким email не найден');
    }
    
    $user->password_hash = password_hash($data['password'], PASSWORD_DEFAULT);
    R::store($user);
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
?>