<?php

require_once "dbconnect.php";
$customer = null;

if (!isset($_SESSION)) {
    session_start(); // to create succes if not exist
}

if (!isset($_SESSION['email'])) {
    header("Location:index.php");
}

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['addressupdate'])) {
        $home = htmlspecialchars($_POST['homenumber']);
        $street = htmlspecialchars($_POST['street']);
        $ward = htmlspecialchars($_POST['ward']);
        $township = htmlspecialchars($_POST['township']);
        $region = htmlspecialchars($_POST['region']);
        updateaddress($home, $street, $ward, $township, $region, $email);
    } elseif ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['accountupdate'])) {
        $name = htmlspecialchars($_POST['name']);
        $phonenumber = htmlspecialchars($_POST['phone']);
        $profile = $_FILES['profile'];
        updateaccount($name, $phonenumber, $email, $profile);
    } elseif ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['changepassword'])) {
        $currentpassword = htmlspecialchars($_POST['currentpassword']);
        $newpassword = htmlspecialchars($_POST['newpassword']);
        $confirmpassword = htmlspecialchars($_POST['confirmpassword']);
        if (password_verify($currentpassword, $customer['password'])) {
            if ($newpassword == $confirmpassword) {
                if (ispasswordstrong($newpassword)) {
                    changepassword($newpassword, $email);
                } else {
                    $_SESSION['newpwd_not_strong'] = 'Password is not strong. Try again';
                }
            } else {
                $_SESSION['pwd_not_same'] = "New and Confirm Passwords are not the same";
            }
        } else {
            $_SESSION['currentpwd_fail'] = "Your current password is not correct";
        }
    }
}

function userdata($email)
{
    try {
        $conn = connect();
        $sql = "select * from customer where cemail = ?";
        $stmt = $conn->prepare($sql);
        $status = $stmt->execute([$email]);
        $customer = $stmt->fetch();
        $conn = null;
        return $customer;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function updateaddress($home, $street, $ward, $township, $region, $email)
{
    try {
        $conn = connect();
        $sql = "UPDATE customer SET homenumber=?, street=?, ward=?, township=?, region=? where cemail=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$home, $street, $ward, $township, $region, $email]);
        $_SESSION['updateaddress'] = "Update Address Successfully";
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function updateaccount($name, $phonenumber, $email, $profile)
{
    $prevprofileurl = checkprofileurl($email);
    if ($prevprofileurl != null) {
        unlink($prevprofileurl);
    }
    $date = new DateTimeImmutable();
    $datetime = $date->format('l-jS-F-Y-');
    $rdm = rand();
    $filename = $profile['name'];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    // $uploadpath = $location . $datetime . $rdm . "." . $ext;
    $uploadpath = "assets/img/profile/" . $datetime . $rdm . "." . $ext;
    if (move_uploaded_file($profile['tmp_name'], $uploadpath)) {
        try {
            $conn = connect();
            $sql = "UPDATE customer SET cname=?, phonenumber=?, profile=? where cemail=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$name, $phonenumber, $uploadpath, $email]);
            $_SESSION['updateaccount'] = "Update Account Info Successfully";
            $conn = null;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}

function checkprofileurl($email)
{
    try {
        $conn = connect();
        $sql = "SELECT profile FROM customer WHERE cemail=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);
        $url = $stmt->fetch();
        $profileurl = $url['profile'];
        return $profileurl;
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function changepassword($password, $email)
{
    try {
        $conn = connect();
        $hashcode = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE customer SET password=? where cemail=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$hashcode, $email]);
        $_SESSION['changepassword'] = "Password has been change successfully";
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
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

function getcartcount($cid)
{
    try {
        $conn = connect();
        $sql = "SELECT COUNT(*) AS count FROM cart WHERE customerid=? ";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$cid]);
        $productcounts = $stmt->fetch();
        return $productcounts;
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

$customer = userdata($email);

$cid = $customer['cid'];
$getcartcounts = getcartcount($cid);
$cartcount = $getcartcounts['count'];

function getcategory()
{
    try {
        $conn = connect();
        $sql = "SELECT * FROM category";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $category = $stmt->fetchAll();
        return $category;
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

$catgories = getcategory();

?>

<!DOCTYPE html>
<html>

<head>
    <title>Planti Plant Shop</title>
    <!-- fav icon -->
    <link href="./assets/img/fav/logo2.png" rel="icon" type="image/png" sizes="16X16" />
    <!-- bootstrap css1 js1 -->
    <link href="./assets/libs/bootstrap-5.3.2-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- fontawesome css1 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- jquery ui css1 js1 -->
    <link href="./assets/libs/jquery-ui-1.13.2/jquery-ui.min.css" rel="stylesheet" type="text/css" />
    <!-- custom css -->
    <link href="./css/style.css" rel="stylesheet" type="text/css">
</head>

<body>


    <!-- Start Back to Top Us Section -->
    <div class="fixed-bottom">
        <a href="./editaccount.php" class="btn-backtotops"><i class="fas fa-arrow-up"></i></a>
    </div>
    <!-- If index.html is used, the webpage can be relaoded -->
    <!-- End Back to Top Us Section -->

    <!-- Start Header Section -->
    <header>
        <!-- Start header information section-->
        <div class="text-center py-1 informations">
            <small>Free Shipping above $200 | All Myanmar Deliver</small>
        </div>
        <!-- End header information section-->

        <!-- Start header top-->
        <nav class="navbar navbar-expand-lg headertops">

            <div class="d-flex justify-content-center mx-2">
                <div class="dropdown">
                    <button class="dropbtn">Language <i class="fas fa-caret-down"></i></button>
                    <div class="dropdown-content">
                        <a href="#">ENG</a>
                        <a href="#">MYAN</a>
                    </div>
                </div>

                <div class="dropdown">
                    <button class="dropbtn">Pricing <i class="fas fa-caret-down"></i></button>
                    <div class="dropdown-content">
                        <a href="#">Dollar</a>
                        <a href="#">Kyat</a>
                    </div>
                </div>
            </div>

            <button type="button" class="navbar-toggler togglers" data-bs-toggle="collapse" data-bs-target="#nav"> LINKS
                <i class="fas fa-caret-down"></i></button>

            <div id="nav" class="navbar-collapse collapse text-center justify-content-end">
                <ul class="navbar-nav">
                    <li class="nav-item"><a href="javascript:void(0);" class="nav-link mx-2 menuitems"><i class="fas fa-phone-alt px-2"></i> Call: +0123 456 789</a></li>
                    <?php if (isset($_SESSION['wishlist'])) { ?>
                        <li class="nav-item"><a href="./wishlist.php" class="nav-link mx-2 menuitems"><i class="far fa-heart px-2"></i> My Wishlist <span>(<?php echo sizeof($_SESSION['wishlist']) ?>)</span></a></li>
                    <?php } else { ?>
                        <li class="nav-item"><a href="./wishlist.php" class="nav-link mx-2 menuitems"><i class="far fa-heart px-2"></i> My Wishlist <span>(0)</span></a></li>
                    <?php } ?>
                    <li class="nav-item"><a href="./aboutus.php" class="nav-link mx-2 menuitems">About Us</a>
                    </li>
                    <li class="nav-item"><a href="./aboutus.php" class="nav-link mx-2 menuitems">Contact
                            Us</a></li>
                    <li class="nav-item">
                        <div class="dropdown mx-2 menuitems" style="margin-top: 7px;">
                            <button class="dropbtn"><i class="far fa-user px-2"></i>Account</button>
                            <div class="dropdown-content">
                                <a href="./useraccount.php" class="nav-link">Account</a>
                                <a href="./logout.php" class="nav-link">Logout</a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- End header top-->

        <!-- Start Header bottom -->
        <nav class="headerbottoms">
            <div class="d-flex justify-content-center align-items-center">
                <a href="./customerhomepage.php" class="logo">
                    <img src="./assets/img/fav/logo2.png" alt="logo" />
                    <h1 class="">Planti</h1>
                </a>
                <ul>
                    <li>
                        <a href="./customerhomepage.php">Home</a>
                    </li>
                    <li>
                        <div class="dropdown">
                            <button class="dropbtn">Plants <i class="fas fa-caret-down"></i></button>
                            <div class="dropdown-content">
                                <?php foreach ($catgories as $category) { ?>
                                    <?php if ($category['description'] == "plant") { ?>
                                        <a href="productpage.php?cid=<?php echo $category['ctgid'] ?>"><?php echo $category['ctgname'] ?></a>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="dropdown">
                            <button class="dropbtn">Seeds <i class="fas fa-caret-down"></i></button>
                            <div class="dropdown-content">
                                <?php foreach ($catgories as $category) { ?>
                                    <?php if ($category['description'] == "seed") { ?>
                                        <a href="productpage.php?cid=<?php echo $category['ctgid'] ?>"><?php echo $category['ctgname'] ?></a>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                    </li>
                    <li>
                        <a href="./productpage.php?cid=accessory">Accessories</a>
                    </li>
                    <li>
                        <a href="./productpage.php?cid=discount">Offers</a>
                    </li>
                    <li>
                        <a href="./blog.php">Blog</a>
                    </li>
                </ul>
            </div>

            <div class="d-flex justify-content-center align-items-center">
                <div class="searchboxs">
                    <form action="./productpage.php" method="post">
                        <input type="hidden" name="cid" value="search"/>
                        <input type="search" name="searchstring" class="searchinputs" placeholder="Search product ...." required />
                        <button class="searchbtns" name="searchbtn" type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>

                <div class="card-dropdowns">
                    <a href="./cart.php" class="shoppingcarts" role="button"><i class="fas fa-shopping-cart"></i><span class="cartcounts"><?php echo $cartcount ?></span></a>
                </div>

                <div class="hamburger">
                    <span class="line"></span>
                    <span class="line"></span>
                    <span class="line"></span>
                </div>

            </div>
        </nav>
        <div class="menubar">
            <ul>
                <li>
                    <div class="searchboxs">
                        <form action="./productpage.php" method="post">
                            <input type="hidden" name="cid" value="search"/>
                            <input type="search" name="searchstring" class="searchinputs" placeholder="Search product ...." required />
                            <button class="searchbtns" name="searchbtn" type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                </li>
                <li>
                    <a href="./customerhomepage.php">Home</a>
                </li>
                <li>
                    <div class="dropdown">
                        <button class="dropbtn">Plants <i class="fas fa-caret-down"></i></button>
                        <div class="text-uppercase dropdown-content">
                            <?php foreach ($catgories as $category) { ?>
                                <?php if ($category['description'] == "plant") { ?>
                                    <a href="productpage.php?cid=<?php echo $category['ctgid'] ?>"><?php echo $category['ctgname'] ?></a>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="dropdown">
                        <button class="dropbtn">Seeds <i class="fas fa-caret-down"></i></button>
                        <div class="text-uppercase dropdown-content">
                            <?php foreach ($catgories as $category) { ?>
                                <?php if ($category['description'] == "seed") { ?>
                                    <a href="productpage.php?cid=<?php echo $category['ctgid'] ?>"><?php echo $category['ctgname'] ?></a>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                </li>
                <li>
                    <a href="./productpage.php?cid=accessory">Accessories</a>
                </li>
                <li>
                    <a href="./productpage.php?cid=discount">Offers</a>
                </li>
                <li>
                    <a href="./blog.php">Blog</a>
                </li>
            </ul>
        </div>

        <!-- End Header bottom-->

    </header>
    <!-- End Header Section -->

    <!-- Start Edit Account Section -->
    <section class="py-1">
        <div class="container-fluid">
            <div class="row">

                <!-- start leftside bar -->
                <div class="col-lg-3 col-md-4 border userleftsidebars">

                    <div class="row">
                        <div class="col-12 border-bottom py-3 ps-5">
                            <a href="./profile.php" class="nav-link">
                                <i class="fas fa-user h4 me-3 text-center"></i>
                                <span class="h5 text-dark">Profile</span>
                            </a>
                        </div>
                        <div class="col-12 border-bottom py-3 ps-5">
                            <a href="./orderrecord.php" class="nav-link">
                                <i class="fas fa-box-open h4 me-3 text-center"></i>
                                <span class="h5 text-dark">My Order</span>
                            </a>
                        </div>
                        <div class="col-12 border-bottom py-3 ps-5">
                            <a href="./address.php" class="nav-link">
                                <i class="fas fa-map-marker-alt h4 me-3 text-center"></i>
                                <span class="h5 text-dark">Address</span>
                            </a>
                        </div>
                        <div class="col-12 border-bottom py-3 ps-5 bordersuccesses">
                            <a href="./editaccount.php" class="nav-link text-success">
                                <i class="fas fa-user-edit h4 me-3 text-center"></i>
                                <span class="h5">Edit Account</span>
                            </a>
                        </div>
                        <div class="col-12 border-bottom py-3 ps-5">
                            <a href="./review.php" class="nav-link">
                                <i class="fas fa-comment-dots h4 me-3 text-center"></i>
                                <span class="h5 text-dark">Review</span>
                            </a>
                        </div>
                    </div>

                </div>
                <!-- start leftside bar -->

                <!-- start contact area -->
                <div class="col-lg-9 col-md-8 col-sm-11 mx-auto my-4">

                    <div class="row">

                        <div class="col-lg-10 col-md-11 mx-auto">

                            <div class="row">
                                <div class="col-12 p-0 py-1">
                                    <p class="text-success">Home > <span class="fw-bold">Edit Account</span></p>
                                </div>

                                <div class="col-12 p-0 py-3">
                                    <h2>Edit Account</h2>
                                </div>

                                <div class="card shadow">
                                    <div class="card-header bg-white">
                                        <div class="d-flex justify-content-between align-items-center py-2">
                                            <?php if ($customer['profile'] != null) { ?>
                                                <img src="<?php echo $customer['profile'] ?>" class="rounded-circle" width="60" alt="user<?php echo $customer['cid'] ?>" />
                                            <?php } else { ?>
                                                <img src="./assets/img/user/avator.png" class="rounded-circle" width="60" alt="defaultprofile" />
                                            <?php } ?>
                                            <a href="./useraccount.php" class="small text-dark text-uppercase link-underline-dark">go to dashboard</a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row justify-content-center ps-3">
                                            <div class="col-lg-6">
                                                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                                                    <table class="mt-4 mb-3">
                                                        <th class="pb-3">Edit Address</th>
                                                        <tr>
                                                            <td class="h6 pe-4 py-3"><label for="homenumber"><i class="fas fa-home"></i> Home:</label></td>
                                                            <td class="small py-2"><input type="text" name="homenumber" id="homenumber" class="form-control" required /></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="h6 pe-4 py-3"><label for="street"><i class="fas fa-road"></i> Street:</label></td>
                                                            <td class="small py-2"><input type="text" name="street" id="street" class="form-control" required /></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="h6 pe-4 py-3"><label for="ward"><i class="fas fa-building"></i> Ward:</label></td>
                                                            <td class="small py-2"><input type="text" name="ward" id="ward" class="form-control" required /></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="h6 pe-4 py-3"><label for="township"><i class="fas fa-city"></i> Township:</label></td>
                                                            <td class="small py-2"><input type="text" name="township" id="township" class="form-control" required /></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="h6 pe-4 py-3"><label for="region"><i class="fas fa-globe-europe"></i> Region:</label></td>
                                                            <td class="small py-2"><input type="text" name="region" id="region" class="form-control" required /></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="h6 pe-4 py-3"><button type="submit" class="btn btn-success px-4" name="addressupdate">Edit</button></td>
                                                            <td class="small py-2"><button type="reset" class="btn btn-danger">Cancel</button></td>
                                                        </tr>
                                                    </table>
                                                </form>
                                            </div>
                                            <div class="col-lg-6">
                                                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                                                    <table class="mt-4 mb-3">
                                                        <th class="pb-3">Change Password</th>
                                                        <tr>
                                                            <td class="h6 pe-4 py-3"><label for="currentpassword"><i class="fas fa-key"></i> Current:</label></td>
                                                            <td class="small py-2"><input type="password" name="currentpassword" id="currentpassword" class="form-control" required /></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="h6 pe-4 py-3"><label for="newpassword"><i class="fas fa-unlock-alt"></i> New:</label></td>
                                                            <td class="small py-2"><input type="password" name="newpassword" id="newpassword" class="form-control" required /></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="h6 pe-4 py-3"><label for="confirmpassword"><i class="fas fa-unlock-alt"></i> Confirm:</label></td>
                                                            <td class="small py-2"><input type="password" name="confirmpassword" id="confirmpassword" class="form-control" required /></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="h6 pe-4 py-3"><button type="submit" class="btn btn-success px-4" name="changepassword">Edit</button></td>
                                                            <td class="small py-2"><button type="reset" class="btn btn-danger">Cancel</button></td>
                                                        </tr>
                                                    </table>
                                                </form>
                                            </div>
                                            <div class="col-lg-6">
                                                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                                                    <table class="mt-4 mb-3">
                                                        <th class="pb-3">Edit Account</th>
                                                        <tr>
                                                            <td class="h6 pe-4 py-3"><label for="name"><i class="fas fa-user"></i> Name:</label></td>
                                                            <td class="small py-2"><input type="text" name="name" id="name" class="form-control" required /></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="h6 pe-4 py-3"><label for="phone"><i class="fas fa-phone-alt"></i> Phone:</label></td>
                                                            <td class="small py-2"><input type="text" name="phone" id="phone" class="form-control" minlength="9" maxlength="11" required /></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="h6 pe-4 py-3"><label for="profile"><i class="fas fa-images"></i> Profile:</label></td>
                                                            <td class="small py-2"><input type="file" name="profile" id="profile" class="form-control" accept="image/png, image/jpeg, image/jpg" placeholder="Only png, jpeg and jpg file" required /></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="h6 pe-4 py-3"><button type="submit" class="btn btn-success px-4" name="accountupdate">Edit</button></td>
                                                            <td class="small py-2"><button type="reset" class="btn btn-danger">Cancel</button></td>
                                                        </tr>
                                                    </table>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white py-4">
                                        <a href="./logout.php" class="small text-dark text-uppercase link-underline-dark">Log out</a>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
                <!-- end contact area -->


            </div>
        </div>
    </section>
    <!-- Start Edit Account Section -->

    <!-- elfsight for telegram chat -->
    <div class="elfsight-app-c42bdffe-41f6-412d-8c61-0a216b2ddefc" data-elfsight-app-lazy></div>
    <!-- elfsight for telegram chat -->

    <!-- Start Footer Section-->
    <footer class="px-3 footerbgs" style="position:relative;">
        <div class="container-fluid">

            <div class="row text-white pt-5 pb-2">

                <div class="col-md-2 col-sm-6">
                    <h5 class="mb-3 text-uppercase">Userful Links</h5>
                    <ul class="list-unstyled"> <!-- list-unstyled = list-style:none, padding:0; , margin:0;-->
                        <li><a href="./aboutus.php" class="footerlinks">About Planti</a></li>
                        <li><a href="./productpage.php" class="footerlinks">How to shop on Planti</a></li>
                        <li><a href="./termandcondition.php" class="footerlinks">FAQ</a></li>
                        <li><a href="./aboutus.php" class="footerlinks">About us</a></li>
                        <li><a href="javascript:volid(0);" data-bs-toggle="modal" data-bs-target="#signin-modal" class="footerlinks">Login in</a></li>
                    </ul>
                </div>

                <div class="col-md-3 col-sm-6">
                    <h5 class="mb-3 text-uppercase">Customer Services</h5>
                    <ul class="list-unstyled"> <!-- list-unstyled = list-style:none, padding:0; , margin:0;-->
                        <li><a href="javascript:volid(0);" class="footerlinks">Payment Methods</a></li>
                        <li><a href="javascript:volid(0);" class="footerlinks">Money-back guarantee!</a></li>
                        <li><a href="./returninfo.php" class="footerlinks">Returns</a></li>
                        <li><a href="./deliveryinformation.php" class="footerlinks">Shipping</a></li>
                        <li><a href="./termandcondition.php" class="footerlinks">Terms and conditions</a></li>
                        <li><a href="./privacyandpolicy.php" class="footerlinks">Privacy Policy</a></li>
                    </ul>
                </div>

                <div class="col-md-2 col-sm-6">
                    <h5 class="mb-3 text-uppercase">My Account</h5>
                    <ul class="list-unstyled"> <!-- list-unstyled = list-style:none, padding:0; , margin:0;-->
                        <?php if(!isset($_SESSION['email'])){ ?>
                            <li><a href="javascript:volid(0);" data-bs-toggle="modal" data-bs-target="#signin-modal" class="footerlinks">Sign In</a></li>
                        <?php }else{ ?>
                            <li><a href="./useraccount.php" class="footerlinks">Account</a></li>
                        <?php }?>
                        <?php if(isset($_SESSION['email'])){ ?>
                            <li><a href="./cart.php" class="footerlinks">View Cart</a></li>
                        <?php }else{ ?>
                            <li><a href="javascript:volid(0);" class="footerlinks">View Cart</a></li>
                        <?php }?>
                        <li><a href="./wishlist.php" class="footerlinks">My Wishlist</a></li>
                        <?php if(isset($_SESSION['email'])){ ?>
                            <li><a href="./orderrecord.php" class="footerlinks">Track My Order</a></li>
                        <?php }else{ ?>
                            <li><a href="javascript:volid(0);" class="footerlinks">Track My Order</a></li>
                        <?php }?>
                        <li><a href="javascript:volid(0);" class="footerlinks">Help</a></li>
                    </ul>
                </div>

                <div class="col-md-2 col-sm-6">
                    <div class="row">
                        <div class="col12">
                            <h5 class="mb-3 text-uppercase">GET IN TOUCH</h5>
                            <ul class="list-unstyled"> <!-- list-unstyled = list-style:none, padding:0; , margin:0;-->
                                <li><a href="javascript:volid(0);" class="footerlinks">Call : +95-9129-9129-91</a></li>
                                <li><a href="javascript:volid(0);" class="footerlinks">Email : ir@imu.edu.mm</a></li>
                            </ul>
                        </div>
                        <div class="col12">
                            <h5 class="mb-3 text-uppercase">SOCIAL MEDIA</h5>
                            <ul class="list-unstyled d-flex">
                                <!-- list-unstyled = list-style:none, padding:0; , margin:0;-->
                                <li><a href="javascript:volid(0);" class="nav-link"><i class="fab fa-twitter"></i></a>
                                </li>
                                <li class="ms-3"><a href="https://imu.edu.mm/" class="nav-link"><i class="fab fa-instagram"></i></a></li>
                                <li class="ms-3"><a href="https://imu.edu.mm/" class="nav-link"><i class="fab fa-facebook"></i></a></li>
                                <li class="ms-3"><a href="https://imu.edu.mm/" class="nav-link"><i class="fab fa-youtube"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <h5 class="mb-3 text-uppercase">SIGN UP TO NEWSLETTER</h5>
                    <p class="pb-2">For plant care tips, our featured plant of the week, exclusive offers and discounts
                    </p>
                    <form action="" method="">
                        <input type="email" name="newsubscribeemail" id="newsubscribeemail" class="subscribeemailinput" placeholder="Enter email address" />
                        <button type="submit" class="subscribeemail-btn"><i class="fas fa-arrow-right"></i></button>
                    </form>
                </div>

                <div class="text-light d-flex justify-content-between border-top pt-4 mt-5">
                    <div class="d-flex">
                        <p class="me-2"> &copy; <span id="getyear">2000</span> Copyright. Planti,All rights reserved.
                        </p>
                        <a href="./termandcondition.php" class="text-light">Terms of Use</a>
                        <span class="px-2"> | </span>
                        <a href="./privacyandpolicy.php" class="text-light">Privacy Policy</a>
                    </div>
                    <ul class="list-unstyled d-flex"> <!-- list-unstyled = list-style:none, padding:0; , margin:0;-->
                        <li><img src="./assets/img/card/visa.jpg" width="50px" /></li>
                        <li class="ms-2"><img src="./assets/img/card/kpay.png" width="30px" /></li>
                        <li class="ms-2"><img src="./assets/img/card/cb.png" width="30px" /></li>
                        <li class="ms-2"><img src="./assets/img/card/wave.png" width="30px" /></li>
                    </ul>
                </div>

            </div>

        </div>
    </footer>
    <!-- Start Footer Section-->

    <!-- Start Update Success Message Modal -->
    <?php
    $alertmessage = null;
    $gifpath = null;
    if (isset($_SESSION['updateaddress'])) {
        $alertmessage = $_SESSION['updateaddress'];
        $gifpath = "./assets/img/gif/success.gif";
    } elseif (isset($_SESSION['updateaccount'])) {
        $alertmessage = $_SESSION['updateaccount'];
        $gifpath = "./assets/img/gif/success.gif";
    } elseif (isset($_SESSION['changepassword'])) {
        $alertmessage = $_SESSION['changepassword'];
        $gifpath = "./assets/img/gif/success.gif";
    } elseif (isset($_SESSION['currentpwd_fail'])) {
        $alertmessage = $_SESSION['currentpwd_fail'];
        $gifpath = "./assets/img/gif/fail1.gif";
    } elseif (isset($_SESSION['pwd_not_same'])) {
        $alertmessage = $_SESSION['pwd_not_same'];
        $gifpath = "./assets/img/gif/fail1.gif";
    } elseif (isset($_SESSION['newpwd_not_strong'])) {
        $alertmessage = $_SESSION['newpwd_not_strong'];
        $gifpath = "./assets/img/gif/fail1.gif";
    }

    ?>
    <div class="modal fade" id="onload" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-column justify-content-center align-items-center">
                        <img src="<?php echo $gifpath ?>" width="150px" />
                        <h5 class="mt-4"><?php echo $alertmessage ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Start Update Message Modal -->


    <!-- bootstrap css1 js1 -->
    <script src="./assets/libs/bootstrap-5.3.2-dist/js/bootstrap.bundle.min.js" type="text/javascript"></script>
    <!-- jquery js1 -->
    <script src="./assets/libs/jquery/jquery-3.7.1.min.js" type="text/javascript"></script>
    <!-- jquery ui css1 js1 -->
    <script src="./assets/libs/jquery-ui-1.13.2/jquery-ui.min.js" type="text/javascript"></script>
    <!-- elfsight for viber chat -->
    <script src="https://static.elfsight.com/platform/platform.js" data-use-service-core defer></script>
    <!-- custom js -->
    <script src="./js/app.js" type="text/javascript"></script>

    <?php
    if (
        isset($_SESSION['updateaddress']) || isset($_SESSION['updateaccount']) || isset($_SESSION['changepassword']) ||
        isset($_SESSION['currentpwd_fail']) || isset($_SESSION['pwd_not_same']) || isset($_SESSION['newpwd_not_strong'])
    ) {
    ?>
        <script type="text/javascript">
            window.onload = () => {
                $('#onload').modal('show');
            }
            setTimeout(function() {
                $('#onload').modal('hide')
            }, 5000);
        </script>

    <?php
        unset($_SESSION['updateaddress']);
        unset($_SESSION['updateaccount']);
        unset($_SESSION['changepassword']);
        unset($_SESSION['currentpwd_fail']);
        unset($_SESSION['pwd_not_same']);
        unset($_SESSION['newpwd_not_strong']);
    } ?>

</body>

</html>