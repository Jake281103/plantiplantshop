<?php

require_once "dbconnect.php";

if (!isset($_SESSION)) {
    session_start(); // to create session if not exist
}

function ispasswordstrong($password)
{
    if (strlen($password) < 8) {
        return false;
    } elseif (isstrong($password)) {
        return true;
    } else {
        return false;
    }
}

function isstrong($password)
{
    $digitcount = 0;
    $capitalcount = 0;
    $speccount = 0;
    $lowercount = 0;
    foreach (str_split($password) as $char) {
        if (is_numeric($char)) {
            $digitcount++;
        } elseif (ctype_upper($char)) {
            $capitalcount++;
        } elseif (ctype_lower($char)) {
            $lowercount++;
        } elseif (ctype_punct($char)) {
            $speccount++;
        }
    }

    if ($digitcount >= 1 && $capitalcount >= 1 && $speccount >= 1) {
        return true;
    } else {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['signup'])) {

    $email = htmlspecialchars($_POST['registeremail']);
    $password = htmlspecialchars($_POST['registerpasword']);
    $hascode = null;

    try {
        $conn = connect();
        $sql = "select * from customer where cemail = ?";
        $stmt = $conn->prepare($sql);
        $status = $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            // echo "has be registered";
            $_SESSION['email_exit'] = "Your email has already been registered";
            header("Location:index.php");
        } else {
            // echo "new Customer";
            if (ispasswordstrong($password)) {
                $status = 1;
                $hascode = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO customer (cemail,password,status) VALUES(:email, :password, :status)");
                $stmt->bindValue(':email', $email);
                $stmt->bindValue(':password', $hascode);
                $stmt->bindValue(':status', $status);
                $stmt->execute();
                $_SESSION['email'] = $email;
                $_SESSION['customersignup_success'] = "You have successfully registered";
                header("Location:customerhomepage.php");
            }else{
                $_SESSION['password_not_strong'] = "Your password is not strong enough";
                header("Location:index.php");
            }
        }
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

?>
