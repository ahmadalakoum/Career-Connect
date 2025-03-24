<?php
require_once __DIR__ . '/connection/connection.php';
require_once __DIR__ . "/repositories/user/UserRepository.php";


// Define your base directory 
$base_dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove the base directory from the request if present
if (strpos($request, $base_dir) === 0) {
    $request = substr($request, strlen($base_dir));
}

// Ensure the request is at least '/'
if ($request == '') {
    $request = '/';
}

$apis = [
    '/signup' => ['controller' => 'UserController', 'method' => 'signup', 'repository' => 'UserRepository'],
    '/login' => ['controller' => 'UserController', 'method' => 'login', 'repository' => 'UserRepository'],
    '/user/update' => ['controller' => 'UserController', 'method' => 'update', 'repository' => 'UserRepository'],
    '/user/update-password' => ['controller' => 'UserController', 'method' => 'updatePassword', 'repository' => 'UserRepository'],
];

if (isset($apis[$request])) {
    $controllerName = $apis[$request]['controller'];
    $method = $apis[$request]['method'];
    $repositoryName = $apis[$request]['repository'];


    require_once __DIR__ . "/controllers/{$controllerName}.php";
    require_once __DIR__ . "/repositories/user/{$repositoryName}.php";

    $repository = new $repositoryName($pdo);
    $controller = new $controllerName($repository);
    if (method_exists($controller, $method)) {
        $controller->$method();
    } else {
        echo "Error: Method {$method} not found in {$controllerName}.";
    }
} else {
    echo "404 Not Found";
}