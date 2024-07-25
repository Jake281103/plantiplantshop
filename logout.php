<?php

require_once "dbconnect.php";

if(!isset($_SESSION)){
    session_start();
}


function updatestatus($email){  
    try{
        $conn = connect();
        $sql = "update customer set status = 0 where cemail = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);
        $conn = null;
    }catch(PDOException $e){
        echo $e->getMessage();
    }
}

if($_SESSION){
    $email = $_SESSION['email'];
    updatestatus($email);
    session_destroy();
    header("Location: index.php");
}
    
?>