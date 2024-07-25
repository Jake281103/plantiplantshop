<?php

require_once "dbconnect.php";
if(!isset($_SESSION)){
    session_start();
}

function updatestatus($email){
    try{
        $conn = connect();
        $sql = "update customer set status = 1 where cemail = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);
        $conn = null;
    }catch(PDOException $e){
        echo $e->getMessage();
    }
}

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['login'])){
    $email = htmlspecialchars($_POST['singinemail']);
    $password = htmlspecialchars($_POST['singinpassword']);

    try{
        $conn = connect();
        $sql = "select * from customer where cemail = ?";
        $stmt = $conn->prepare($sql);
        $status = $stmt->execute([$email]);
        $customer = $stmt->fetch();
        if($stmt->rowCount() > 0){
            if(password_verify($password, $customer['password'])){
                $_SESSION['customerlogin_success'] = "You have successfully logged in";
                $_SESSION['email'] = $email;
                updatestatus($email);
                header("Location: customerhomepage.php");
            }else{
                $_SESSION['loginerror'] = "Your Password might be incorrect";
                header("Location:index.php");
            }
        }else{
            $_SESSION['loginerror'] = "Your email might be incorrect";
            header("Location:index.php");
        }

        $conn = null;

    }catch(PDOException $e){
        echo $e->getMessage();
    }


}



?>