<?php 

if (!isset($_SESSION)) {
    session_start(); // to create succes if not exist
}

$errormessage = null;

if(isset($_SESSION['admin_email_exact'])){
    $errormessage = "**".$_SESSION['admin_email_exact']."**";
}elseif(isset($_SESSION['password_not_strong'])){
    $errormessage = "**".$_SESSION['password_not_strong']."**";
}elseif(isset($_SESSION['admin_phonenumber_exact'])){
    $errormessage = $_SESSION['admin_phonenumber_exact'];
}elseif(isset($_SESSION['phonenumber_not_strong'])){
    $errormessage = $_SESSION['phonenumber_not_strong'];
}elseif(isset($_SESSION['loginerror'])){
    $errormessage = $_SESSION['loginerror'];
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sing in | Sing Up</title>
    <!-- fav icon -->
    <link href="./../assets/img/fav/logo2.png rel="icon" type="image/png" sizes="16X16" />
    <link rel="stylesheet" href="./css/login.css">
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
</head>

<body>
    <div>
        <h3 style="color:red; padding-bottom: 20px;"><?php echo $errormessage ?></h3>
        <?php 
            if(isset($_SESSION['admin_email_exact'])){
                unset($_SESSION['admin_email_exact']);
            }elseif(isset($_SESSION['password_not_strong'])) {
                unset($_SESSION['password_not_strong']);
            }elseif(isset($_SESSION['admin_phonenumber_exact'])){
                unset($_SESSION['admin_phonenumber_exact']);
            }elseif(isset($_SESSION['phonenumber_not_strong'])){
                unset($_SESSION['phonenumber_not_strong']);
            }elseif(isset($_SESSION['loginerror'])){
                unset($_SESSION['loginerror']);
            }
            
        ?>
    </div>
    <div class="container" id="container">
        <div class="form-container sign-up">
            <form action="./adminsignup.php" method="post">
                <h1>Create Account</h1>
                <div class="social-icons">
                    <a href="javascript:void(0);" class="icon"><i class="bx bxl-facebook"></i></a>
                    <a href="javascript:void(0);" class="icon"><i class="bx bxl-google"></i></a>
                    <a href="javascript:void(0);" class="icon"><i class="bx bxl-instagram"></i></a>
                    <a href="javascript:void(0);" class="icon"><i class="bx bxl-twitter"></i></a>
                </div>
                <samp>or use your email for registration</samp>
                <input type="text" name="amname" id="signupusername" placeholder="Username" required/>
                <input type="email" name="amemail" id="signupemail" placeholder="Email" required/>
                <input type="password" name="ampassword" id="signuppassword" placeholder="Password" required />
                <input type="text" name="amphonenumber" id="signupphonenumber" minlength="9" maxlength="11" placeholder="Phonenumber" required/>
                <button type="submit" name="amsignup">Sign Up</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form action="./adminlogin.php" method="post">
                <h1>Sign In</h1>
                <div class="social-icons">
                    <a href="javascript:void(0);" class="icon"><i class="bx bxl-facebook"></i></a>
                    <a href="javascript:void(0);" class="icon"><i class="bx bxl-google"></i></a>
                    <a href="javascript:void(0);" class="icon"><i class="bx bxl-instagram"></i></a>
                    <a href="javascript:void(0);" class="icon"><i class="bx bxl-twitter"></i></a>
                </div>
                <samp>or use your Email & Password</samp>
                <input type="email" name="amdemail" id="signinemail" placeholder="Email" required/>
                <input type="password" name="ampassword" id="signinpassword" placeholder="Password" required/>
                <a href="#" class="fp">Forgot your password?</a>
                <button type="submit" name="amsignin">Sign In</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all of site features</p>
                    <button type="button" class="hidden" id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Admin!</h1>
                    <p>Register with the admin detials to use all of site features</p>
                    <button type="button" class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <!-- java script -->
    <script src="./js/login.js" type="text/javascript"></script>
</body>

</html>