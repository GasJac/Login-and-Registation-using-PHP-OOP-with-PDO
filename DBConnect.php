<?php

require_once 'config.php';

class DBConnect
{
    private $connect;

    // Provide connection to the database
    public function __construct()
    {
        try {
            $this->connect = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_DATABASE . ";charset=utf8", DB_USERNAME, DB_PASSWORD);
            $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getConnection()
    {
        return $this->connect;
    }
}

?>
