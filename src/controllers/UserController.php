<?php

require __DIR__ . "/../models/User.php";


class UserController
{
    public function __construct(private User $user)
    {
    }

    public function processRequest(string $handler): void
    {
        $method = $_SERVER["REQUEST_METHOD"];

        switch ($handler) {
            case "login":
                $this->userLogin($method);
                break;
            case "signup":
                $this->userSignup($method);
                break;
        }
    }

    private function userLogin(string $method): void
    {
        switch ($method) {
            case "GET":
                $username = explode("/", $_SERVER['REQUEST_URI'])[6] ?? null;
                echo json_encode($this->user->getUser($username));
                break;

            case "POST":
                // code goes here in case of_num POST Request...
                break;
        }
    }

    private function userSignup(string $method): void
    {
        switch ($method) {
            case "GET":
                // code goes here in case of_num GET Request...
                break;

            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $rows = $this->user->insertUser($data);

                echo json_encode($rows ? [
                    "message" => "Inscription reussie"
                ] : "{}");
                break;
        }
    }
}
