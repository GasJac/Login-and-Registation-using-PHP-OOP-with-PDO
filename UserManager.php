<?php

require_once 'User.php';
require_once 'DBConnect.php';

class UserManager extends User {

    function __construct($username, $email, $password){
        parent::__construct($username, $email, $password);
    }

    // Function for user registration
    public function userRegistration(){

        try{
            // Check if username or email is already used
            $dbConnect = new DBConnect(DB_SERVER, DB_DATABASE, DB_USERNAME, DB_PASSWORD);
            $connect = $dbConnect->getConnection();
            $check = $connect->prepare("SELECT id FROM users WHERE username=:username OR email=:email");
            $check->bindParam(":username", $this->getUsername());
            $check->bindParam(":email", $this->getEmail());
            $check->execute();
            $count = $check->rowCount();

            if($count > 0){
                return false;
            }  
            else{
                // Hash the password first
                $hashedPassword = password_hash($this->getPassword(), PASSWORD_DEFAULT);
                // Insert user's inputs into the database 
                $stmt = $connect->prepare("INSERT INTO users(username, email, password) VALUES (:username, :email, :password)");
                $stmt->bindParam(":username", $this->getUsername());
                $stmt->bindParam(":email", $this->getEmail());
                $stmt->bindParam(":password", $hashedPassword);
                $stmt->execute();

                $user = new UserManager($this->getUsername(), $this->getUsername(), $hashedPassword);

                return true;
            }
        }
        catch (PDOException $e){
            echo $e->getMessage();
            return false;
        }
    }

    // Function for user login
    public function login(){
        // Check if username or email is stored in the database and verify if password matches the hashed password in the database
        try{
            $dbConnect = new DBConnect(DB_SERVER, DB_DATABASE, DB_USERNAME, DB_PASSWORD);
            $connect = $dbConnect->getConnection();
            $stmt = $connect->prepare("SELECT * FROM users WHERE username=:username OR email=:email LIMIT 1");
            $stmt->bindParam(":username", $this->getUsername());
            $stmt->bindParam(":email", $this->getEmail());
            $stmt->execute();
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
            $user = new UserManager($this->getUsername(), $this->getUsername(), $this->getPassword());
            
            if($stmt->rowCount() > 0){
                if(password_verify($this->getPassword(), $userRow['password'])){
                    $_SESSION['user_session'] = $userRow['id'];
                    return true;
                }
                else{
                    return false;
                }
            }
        }
        catch(PDOException $e){
            echo $e->getMessage();
            return false;
        }
    }

    // Function to check if user is logged in
    public function isLogged(){
        if(isset($_SESSION['user_session'])){
            return true;
        }
        return false;
    }

    //logoff on form submit
    public function logoffAction($user){
    if (isset($_POST['logOff'])){
      $user->logout();
      header("Location: index.php");
    }
  }

    // Function to unset user session  
    public function logout(){
        session_destroy();
        unset($_SESSION['user_session']);
    }
}