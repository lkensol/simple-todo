<?php

require_once "db.php";

class User extends Database{ 
    
    public function __construct()
    {
        parent::__construct();
    }

    public function login($username, $pwd) {

        try {
            $stmt = $this->conn->prepare(
                "SELECT * FROM  users WHERE username = :username LIMIT 1"
            );

            $stmt->execute(array(':username'=>$username));
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

            if($stmt->rowCount() > 0) {
                if(password_verify($pwd, $userRow['password'])){
                    $_SESSION['user_session'] = $userRow['id'];
                    return true;
                } else {
                    return false;
                }
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

    }

    public function register($username, $pwd) {
        
        try {
            $stmt = $this->conn->prepare(
                "SELECT * FROM users WHERE username = :username LIMIT 1"
            );

            $stmt->execute(array(':username'=>$username));
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

            if($stmt->rowCount() == 0) {
                $new_pwd = password_hash($pwd, PASSWORD_DEFAULT);
                $stmt = $this->conn->prepare(
                    "INSERT INTO
                        users(username, password)
                    VALUES(:username, :password)"
                );
                
                $stmt->bindParam(":username", $username);
                $stmt->bindParam(":password", $new_pwd);
                $stmt->execute();
                return $stmt;
            } else {
                return false;
            }

            
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function is_loggedin() {
        if(isset($_SESSION['user_session']))
        {
           return true;
        }
    }

    public function logout() {
        session_destroy();
        unset($_SESSION['user_session']);
        return true;
    }

    public function redirect($url)
   {
       header("Location: $url");
   }

}