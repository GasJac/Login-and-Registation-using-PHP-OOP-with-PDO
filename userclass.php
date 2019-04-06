<?php

class Userclass {

private $db;

function __construct($connect){
  $this->db = $connect;
}

//function for users registration
public function userRegistration($username,$email,$password){

  try{
    //check if username or email is already used
    $check = $this->db->prepare("SELECT id FROM users WHERE username=:username OR email=:email");
    $check->bindParam("username", $username);
    $check->bindParam("email", $email);
    $check->execute();
    $count=$check->rowCount();

    if($count>0){
      return false;
    }  
    else{
      //hashing the password first
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
      //insert users inputs in database 
      $stmt = $this->db->prepare("INSERT INTO users(username,email,password) VALUES (:username,:email,:password)");
      $stmt->bindparam(":username",$username);
      $stmt->bindparam(":email",$email);
      $stmt->bindparam(":password",$hashedPassword);
      $stmt->execute();
      return true;
    }
  }
  catch (PDOException $e){
    echo $e->getMessage();
    return false;
  }
}

//function for users login
public function login($username,$email,$password){
  //check if username or email is stored on database and verify if password match the hashed password on database
  try{
    $stmt = $this->db->prepare("SELECT * FROM users WHERE username=:username OR email=:email LIMIT 1");
    $stmt->execute(array(':username'=>$username, ':email'=>$email));
    $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
    if($stmt->rowCount() > 0){
      if(password_verify($password, $userRow['password'])){
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

//function to set users session  
public function isLogged(){
  if(isset($_SESSION['user_session'])){
    return true;
  }
 }

//function to unset users session  
public function logout(){
  session_destroy();
  unset($_SESSION['user_session']);
  return true;
  }
}

//logoff on form submit
function logoffAction($user){
  if (isset($_POST['logOff'])){
    $user->logout();
    header("Location: index.php");
  }
}

?>