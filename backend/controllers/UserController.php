<?php

require_once __DIR__ . "/../repositories/user/UserRepository.php";

class UserController
{
    private $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function signup()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $username = trim($data['username']);
        $email = trim($data['email']);
        $password = trim($data['password']);
        $confirmPassword = trim($data['confirmPassword']);

        //check if values are empty
        if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "All Fields Are Required"
            ]);
            exit();
        }
        //check if email is already registered
        if ($this->userRepository->isEmailTaken($email)) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Email already Exists"
            ]);
            exit();
        }
        //check if passwords match
        if ($password !== $confirmPassword) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Passwords Do Not Match"
            ]);
            exit();
        }

        //signup the user
        $IsRegistered = $this->userRepository->createUser($data);

        if ($IsRegistered) {
            echo json_encode([
                'status' => 'success',
                'message' => 'User Registered Successfully'
            ]);
            exit();
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Error During Registration'
            ]);
        }


    }
}