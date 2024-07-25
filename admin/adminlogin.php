<?php 

require_once "./../dbconnect.php";

if (!isset($_SESSION)) {
    session_start();
}


function updatestatus($email){
    try{
        $conn = connect();
        $sql = "update admin set status = 1 where amemail = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);
        $conn = null;
    }catch(PDOException $e){
        echo $e->getMessage();
    }
}

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['amsignin'])){
    $email = htmlspecialchars($_POST['amdemail']);
    $password = htmlspecialchars($_POST['ampassword']);

    try{
        $conn = connect();
        $sql = "select * from admin where amemail = ?";
        $stmt = $conn->prepare($sql);
        $status = $stmt->execute([$email]);
        $admin = $stmt->fetch();
        if($stmt->rowCount() > 0){
            if(password_verify($password, $admin['ampassword'])){
                $_SESSION['admin_login_success'] = "You have successfully logged in";
                $_SESSION['admin_email'] = $email;
                updatestatus($email);
                header("Location: admin.php");
            }else{
                $_SESSION['loginerror'] = "Your Password might be incorrect";
                header("Location:adminlogin&signup.php");
            }
        }else{
            $_SESSION['loginerror'] = "Your email might be incorrect";
            header("Location:adminlogin&signup.php");
        }

        $conn = null;

    }catch(PDOException $e){
        echo $e->getMessage();
    }


}



?>