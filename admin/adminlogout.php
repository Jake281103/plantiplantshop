<?php

require_once "./../dbconnect.php";

if(!isset($_SESSION)){
    session_start();
}

function updatestatus($email){  
    try{
        $conn = connect();
        $sql = "update admin set status = 0 where amemail = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);
        $conn = null;
    }catch(PDOException $e){
        echo $e->getMessage();
    }
}

if($_SESSION){
    $email = $_SESSION['admin_email'];
    updatestatus($email);
    session_destroy();
    header("Location: adminlogin&signup.php");
}
    
?>