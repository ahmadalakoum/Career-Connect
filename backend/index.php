<?php
require_once __DIR__ . '/connection/connection.php';
require_once __DIR__ . "/repositories/user/UserRepository.php";
require_once __DIR__ . "/repositories/job/JobRepository.php";

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
    '/user/profile' => ['controller' => 'UserController', 'method' => 'getPersonalInformation', 'repository' => 'UserRepository'],
    '/add-job' => ['controller' => 'JobController', 'method' => 'addJob', 'repository' => 'JobRepository'],
    '/job' => ['controller' => 'JobController', 'method' => 'getJob', 'repository' => 'JobRepository'],
    '/employer-jobs' => ['controller' => 'JobController', 'method' => 'getJobsEmployer', 'repository' => 'JobRepository'],
    '/update' => ['controller' => 'JobController', 'method' => 'updateJob', 'repository' => 'JobRepository'],
    '/delete' => ['controller' => 'JobController', 'method' => 'deleteJob', 'repository' => 'JobRepository'],
    '/jobs' => ['controller' => 'JobController', 'method' => 'getAllJobs', 'repository' => 'JobRepository'],
    '/search' => ['controller' => 'JobController', 'method' => 'filter', 'repository' => 'JobRepository'],
];

if (isset($apis[$request])) {
    $controllerName = $apis[$request]['controller'];
    $method = $apis[$request]['method'];
    $repositoryName = $apis[$request]['repository'];
    $repositoryPaths = [
        'UserRepository' => __DIR__ . "/repositories/user/UserRepository.php",
        'JobRepository' => __DIR__ . "/repositories/job/JobRepository.php"
    ];


    require_once __DIR__ . "/controllers/{$controllerName}.php";
    require_once $repositoryPaths[$repositoryName];
    if ($repositoryName === 'JobRepository') {
        $jobRepository = new JobRepository($pdo);
        $userRepository = new UserRepository($pdo);
        $controller = new JobController($jobRepository, $userRepository);
    } else {
        $repository = new $repositoryName($pdo);
        $controller = new $controllerName($repository);
    }
    if (method_exists($controller, $method)) {
        $controller->$method();
    } else {
        echo "Error: Method {$method} not found in {$controllerName}.";
    }
} else {
    echo "404 Not Found";
}