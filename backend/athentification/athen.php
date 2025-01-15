<?php

require_once '../../database/connexion.php';

class registre extends Database {

    public function registration($name, $email, $password, $role){
        $conn = $this->getConnection();

        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return 10;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $query = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':role', $role);

        if ($stmt->execute()) {
            return 1; 
        } else {
            return 500; 
        }
    }
}

class login extends Database {

    public function login ($name, $password){

        $conn = $this->getConnection();
        $query = "select * from users where name = :name";
        $stmt = $conn->prepare($query);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            $row = $stmt->fetch();
            
            if(password_verify($password, $row['password'])){
                session_start();
                $_SESSION['name'] = $row['name'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['password'] = $row['password'];
                $_SESSION['role'] = $row['role'];

                switch ($row['role']){
                    case 'Student':
                        header('location: ..........');
                        break;
                    case 'Teacher':
                    header('location: ..........');
                    break;
                    case 'Administrator':
                    header('location: ..........');
                    break;    
        }
        exit;
            }else{
                return 10;
            }
        }else{
            return 100;
        }
    }
}
?>
