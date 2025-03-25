<?php
require_once '../orm/rb.php';
require_once 'auth_check.php';
ORM::init();

header('Content-Type: application/json');

try {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $users = R::findAll('users', 'ORDER BY last_login DESC');
            echo json_encode(R::exportAll($users));
            break;

        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);
            
            foreach ($data['ids'] as $id) {
                $user = R::load('users', $id);
                if ($user->id !== 0) {
                    switch ($data['action']) {
                        case 'block': $user->status = 'blocked'; break;
                        case 'unblock': $user->status = 'active'; break;
                        case 'delete': R::trash($user); break;
                    }
                }
            }
            R::storeAll($users);
            echo json_encode(['message' => 'Action completed']);
            break;

        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>