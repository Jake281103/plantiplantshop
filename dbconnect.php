<?php

    function connect(){
        $server = "localhost";
        $user = "root";
        $password = "";
        try{
            $conn = new PDO("mysql:host=$server;port=3306;dbname=plantiplantshop", $user, $password);
            $conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // echo "connected";
            return $conn;
        }catch(PDOException $e){
            echo $e;
        }
    }

?>