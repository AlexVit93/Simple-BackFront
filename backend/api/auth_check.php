<?php
require_once '../orm/rb.php';
ORM::init();
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die(json_encode(['error' => 'Unauthorized']));
}

$user = R::load('users', $_SESSION['user_id']);
if ($user->id === 0 || $user->status === 'blocked') {
    session_destroy();
    http_response_code(403);
    die(json_encode(['error' => 'Account blocked']));
}
?>