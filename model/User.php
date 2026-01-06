<?php
class User {
    private $conn;

    public function __construct($conn){
        $this->conn = $conn;
    }

    public function findByLogin($login){
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $sql = "SELECT u.id,u.name,u.password,u.role,u.status
                    FROM users u WHERE u.email = ?";
        } else {
            $sql = "SELECT u.id,u.name,u.password,u.role,u.status
                    FROM users u
                    JOIN patients p ON p.user_id = u.id
                    WHERE p.health_card_no = ?";
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $res  = $stmt->get_result();
        $user = $res->fetch_assoc();
        $stmt->close();
        return $user ?: null;
    }
}
