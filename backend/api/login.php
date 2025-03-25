<?php
require_once '../orm/rb.php';
ORM::init();
session_start();

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $user = R::findOne('users', 'email = ?', [$data['email']]);
    
    if ($user && password_verify($data['password'], $user->password_hash)) {
        if ($user->status === 'blocked') {
            throw new Exception('Account blocked');
        }
        
        $_SESSION['user_id'] = $user->id;
        $user->last_login = R::isoDateTime();
        R::store($user);
        
        echo json_encode(['success' => true]);
    } else {
        throw new Exception('Invalid credentials');
    }
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['error' => $e->getMessage()]);
}
?>