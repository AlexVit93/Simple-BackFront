<?php
require_once '../orm/rb.php';
require_once 'auth_check.php';
ORM::init();
header('Content-Type: application/json');
try {
    $methodHandlers = [
        'GET' => function() {
            $users = R::findAll('users', 'ORDER BY last_login DESC');
            echo json_encode(R::exportAll($users));
        },        
        'POST' => function() {
            $data = json_decode(file_get_contents("php://input"), true);
            $actionHandlers = [
                'block' => function($user) { $user->status = 'blocked'; },
                'unblock' => function($user) { $user->status = 'active'; },
                'delete' => function($user) { R::trash($user); }
            ];
            $usersToStore = [];
            foreach ($data['ids'] as $id) {
                if ($user = R::load('users', $id)) {
                    if (isset($actionHandlers[$data['action']])) {
                        $actionHandlers[$data['action']]($user);
                        $usersToStore[] = $user;
                    }
                }
            }          
            R::storeAll($usersToStore);
            echo json_encode(['message' => 'Action completed']);
        }
    ];
    $method = $_SERVER['REQUEST_METHOD'];
    if (isset($methodHandlers[$method])) {
        $methodHandlers[$method]();
    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>