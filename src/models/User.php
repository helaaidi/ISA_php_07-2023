<?php


class User
{
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getUser(string $username): array | string
    {
        $sql = "SELECT * FROM init__login WHERE username = '$username'";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        $stmt->closeCursor();

        if (!$result) {
            http_response_code(400);

            return "{}";
        }

        return $result;
    }

    public function insertUser(array $data): int
    {
        $full_name = $data["fullname"];
        $username = $data["username"];
        $password = $data["password"];
        $email = $data["email"];

        $sql = "INSERT INTO init__login (username, fullname, email, password) VALUES ('$username', '$fullname', '$email', '$password')";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $stmt->closeCursor();

        return $stmt->rowCount();
    }
}
