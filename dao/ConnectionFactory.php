<?php

class ConnectionFactory {

    public static function connect() {

        global $config;
        $dbname = $config['dbname'];
        $dbhost = $config['dbhost'];
        $dbuser = $config['dbuser'];
        $dbpassword = $config['dbpassword'];
        try {
            $connection = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpassword);                        
        }
        catch(PDOException $e) {
            die($e->getMessage());
        }
        return $connection;
    }

    public static function disconnect($conn) {
        $this->connection = $conn;
    }
    
}
