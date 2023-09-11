<?php

    // $serverName = "localhost";
    // $userName = "root";
    // $password = "";
    // $dbName = "react-crud";

    // //create connection

    // $con = mysqli_connect($serverName, $userName, $password, $dbName);
    // if(mysqli_connect_errno()){
    //     echo "Failed to connect";
    //     exit();
    // }
    // echo "Connection Success..!";

    class DbConnect {
        private $serverName = "localhost";
        private $userName = "root";
        private $password = "";
        private $dbName = "react-crud";
        private $conn;

        public function connect() {
            try {
                $this->conn = new PDO("mysql:host=".$this->serverName.";dbname=".$this->dbName, $this->userName, $this->password);

                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $this->conn;
                echo "Connection Success";
            }   
            catch(PDOException $e){
                echo "Error in connection".$e->getMessage();
            }
        }
    }

?>