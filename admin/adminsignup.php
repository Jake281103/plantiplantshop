<?php

require_once "./../dbconnect.php";

if (!isset($_SESSION)) {
    session_start();
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

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['amsignup'])) {
    $name = htmlspecialchars($_POST['amname']);
    $email = htmlspecialchars($_POST['amemail']);
    $password = htmlspecialchars($_POST['ampassword']);
    $phonenumber = htmlspecialchars($_POST['amphonenumber']);
    $hashcode = null;

    try {
        $conn = connect();
        $sql = "select * from admin where amemail = ?";
        $stmt = $conn->prepare($sql);
        $status = $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            // echo "has be registered";
            $_SESSION['admin_email_exact'] = "Your email has already been registered";
            header("Location:adminlogin&signup.php");
        } else {
            $sql = "select * from admin where amphonenumber = ?";
            $stmt = $conn->prepare($sql);
            $status = $stmt->execute([$phonenumber]);
            if ($stmt->rowCount() > 0) {
                $_SESSION['admin_phonenumber_exact'] = "Your phonenumber has already been registered";
                header("Location:adminlogin&signup.php");
            } else {
                if (preg_match("/^09[0-9]{7}$/", $phonenumber) || preg_match("/^09[0-9]{9}$/", $phonenumber)) {
                    // echo "new Customer";
                    if (ispasswordstrong($password)) {
                        $hascode = password_hash($password, PASSWORD_DEFAULT);
                        $status = 1;
                        $stmt = $conn->prepare("INSERT INTO admin (amname,amemail,ampassword,amphonenumber,status) VALUES(:name,:email,:password,:phno,:status)");
                        $stmt->bindValue(':name', $name);
                        $stmt->bindValue(':email', $email);
                        $stmt->bindValue(':password', $hascode);
                        $stmt->bindValue(':phno', $phonenumber);
                        $stmt->bindValue(':status', $status);
                        $stmt->execute();
                        $_SESSION['admin_email'] = $email;
                        $_SESSION['admin_signup_success'] = "You have successfully registered";
                        header("Location:admin.php");
                    } else {
                        $_SESSION['password_not_strong'] = "Your password is not strong enough";
                        header("Location:adminlogin&signup.php");
                    }
                }else{
                    $_SESSION['phonenumber_not_strong'] = "Your phonenumber must start with 09 and contain 9 numbers or 12 number";
                    header("Location:adminlogin&signup.php");
                }
            }
        }
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
