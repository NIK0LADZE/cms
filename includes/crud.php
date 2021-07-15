<?php
//   $servername = "localhost";
//   $username = "root";
//   $password = "";
  
//   try {
//     $conn = new PDO("mysql:host=$servername;dbname=cms", $username, $password);
//     // set the PDO error mode to exception
//     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//     $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
//   } catch(PDOException $e) {
//     echo "Connection failed: " . $e->getMessage();
//     return 0;
//   }

class Crud {
    protected $conn;
    protected $servername = "localhost";
    protected $username = "root";
    protected $password = "";

    function __construct() {
        try {
            $this->conn = new PDO("mysql:host=$this->servername;dbname=cms", $this->username, $this->password);
            // set the PDO error mode to exception
            $this-conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this-conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return 0;
        }
    }
}

class Read extends Crud {
    function __construct($table) {
        // try {
        //     $this->conn = new PDO("mysql:host=$this->servername;dbname=cms", $this->username, $this->password);
        //     // set the PDO error mode to exception
        //     $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //     $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        // } catch(PDOException $e) {
        //     echo "Connection failed: " . $e->getMessage();
        //     return 0;
        // }
        $sql = "SELECT * FROM ".$table;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        var_dump($result);
    }

    function __destruct() {
        $conn = 0;
    }
}