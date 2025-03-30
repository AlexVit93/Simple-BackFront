<?php
require_once '../orm/rb.php';
ORM::init();
session_start();
header('Content-Type: application/json');
try {
    $data = json_decode(file_get_contents("php://input"), true);
    $user = R::findOne('users', 'email = ?', [$data['email']]);
    $validationPipeline = [
        'credentials' => fn() => $user && password_verify($data['password'], $user->password_hash),
        'account_status' => fn() => $user->status !== 'blocked'
    ];
    $errorMessages = [
        'credentials' => 'Invalid credentials',
        'account_status' => 'Account blocked'
    ];
    foreach ($validationPipeline as $checkName => $validator) {
        if (!$validator()) {
            throw new Exception($errorMessages[$checkName]);
        }
    }
    $_SESSION['user_id'] = $user->id;
    $user->last_login = R::isoDateTime();
    R::store($user);   
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['error' => $e->getMessage()]);
}
?>