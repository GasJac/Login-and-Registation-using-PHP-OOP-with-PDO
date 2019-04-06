<?php
session_start();
  //provide connection to the database
   define('DB_SERVER', 'localhost');
   define('DB_USERNAME', 'root');
   define('DB_PASSWORD', '');
   define('DB_DATABASE', 'yourdatabase');
   $db_server = DB_SERVER;
   $db_name = DB_DATABASE;
   $db_user = DB_USERNAME;
   $db_pass = DB_PASSWORD;
    
    try {
      $connect = new pdo("mysql:host=$db_server; dbname=$db_name; charset=utf8", $db_user, $db_pass);
      $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  
//create a Userclass object based on the constructor of said class
include_once 'userclass.php';
$user = new Userclass($connect);
  
?>