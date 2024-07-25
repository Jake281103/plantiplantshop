<?php

require_once "dbconnect.php";

if (!isset($_SESSION)) {
    session_start(); // to create succes if not exist
}

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

function userdata($email)
{
    try {
        $conn = connect();
        $sql = "select * from customer where cemail = ?";
        $stmt = $conn->prepare($sql);
        $status = $stmt->execute([$email]);
        $customer = $stmt->fetch();
        return $customer;
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
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

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $userdatas = userdata($email);
    $cid = $userdatas['cid'];
    $getcartcounts = getcartcount($cid);
    $cartcount = $getcartcounts['count'];
}



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
    <link href="./css/aboutus.css" rel="stylesheet" type="text/css">

</head>

<body>

    <!-- Start Back to Top Us Section -->
    <div class="fixed-bottom">
        <a href="./privacyandpolicy.php" class="btn-backtotops"><i class="fas fa-arrow-up"></i></a>
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
                    <?php if (isset($_SESSION['email'])) { ?>
                        <li class="nav-item">
                            <div class="dropdown mx-2 menuitems" style="margin-top: 7px;">
                                <button class="dropbtn"><i class="far fa-user px-2"></i>Account</button>
                                <div class="dropdown-content">
                                    <a href="./useraccount.php" class="nav-link">Account</a>
                                    <a href="./logout.php" class="nav-link">Logout</a>
                                </div>
                            </div>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item"><a href="javascript:void(0);" class="nav-link mx-2 menuitems" data-bs-toggle="modal" data-bs-target="#signin-modal"><i class="far fa-user px-2"></i>
                                Login</a></li>

                    <?php } ?>
                </ul>
            </div>
        </nav>
        <!-- End header top-->

        <!-- Start Header bottom-->
        <nav class="headerbottoms">
            <div class="d-flex justify-content-center align-items-center">
                <?php if (isset($_SESSION['email'])) { ?>
                    <a href="./customerhomepage.php" class="logo">
                        <img src="./assets/img/fav/logo2.png" alt="logo" />
                        <h1>Planti</h1>
                    </a>
                <?php } else { ?>
                    <a href="./index.php" class="logo">
                        <img src="./assets/img/fav/logo2.png" alt="logo" />
                        <h1>Planti</h1>
                    </a>
                <?php } ?>
                <ul>
                    <li>
                        <a href="./index.php">Home</a>
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
                    <?php if (isset($_SESSION['email'])) { ?>
                        <a href="./cart.php" class="shoppingcarts" role="button"><i class="fas fa-shopping-cart"></i><span class="cartcounts"><?php echo $cartcount ?></span></a>
                    <?php } else { ?>
                        <a href="javascript:void(0);" class="shoppingcarts" role="button"><i class="fas fa-shopping-cart"></i><span class="cartcounts">0</span></a>
                    <?php } ?> <div class="dropdown-content">
                        <a href="#">Flower Seeds</a>
                        <a href="#">Vegetable Seeds</a>
                        <a href="#">Fruit Seeds</a>
                    </div>
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
                            <input type="hidden" name="cid" value="search" />
                            <input type="search" name="searchstring" class="searchinputs" placeholder="Search product ...." required />
                            <button class="searchbtns" name="searchbtn" type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                </li>
                <li>
                    <?php if (isset($_SESSION['email'])) { ?>
                        <a href="./customerhomepage.php">Home</a>
                    <?php } else { ?>
                        <a href="./index.php">Home</a>
                    <?php } ?>
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

    </header>
    <!-- End Header Section -->

    <!-- Start Cart Section -->
    <main class="prodcut-section">

        <!-- start cart page header -->
        <div class="text-center" style="background-image: url('./assets/img/banner/banner2.jpg');background-size:cover;">
            <div class="container-fluid py-5 product-page-headers">
                <h1 class="text-uppercase">Privacy And Policy</h1>
                <p class="h5">Planti Plant Shop</p>
            </div>
        </div>
        <!-- end cart page header -->

        <!-- start .breadcrumb-nav -->
        <nav aria-label="breadcrumb" class="breadcrumb-nav mt-4 mb- pb-2 border-bottom">
            <div class="container-fluid">
                <ol class="breadcrumb">
                    <?php if (isset($_SESSION['email'])) { ?>
                        <li class="breadcrumb-item text-muted"><a href="customerhomepage.php" class="nav-link">Home</a></li>
                    <?php } else { ?>
                        <li class="breadcrumb-item text-muted"><a href="index.php" class="nav-link">Home</a></li>
                    <?php } ?>
                    <li class="breadcrumb-item" aria-current="page">Privacy and policy</li>
                </ol>
            </div><!-- End .container-fluid -->
        </nav><!-- End .breadcrumb-nav -->
        <!-- end .breadcrumb-nav -->

        <!-- start cart page content -->
        <div class="page-content mt-5 mb-5">
            <div class="container-fluid mt-5">
                <div class="row">

                    <!-- Privacy Policy Section -->
                    <div class="container mt-5">
                        <h1 class="text-center mb-4 mb-5">Privacy Policy</h1>
                        <div class="row">
                            <div class="col-md-10 offset-md-1">
                                <div class="card">
                                    <div class="card-body p-5">
                                        <h5 class="card-title mb-3">Introduction</h5>
                                        <p class="card-text mb-5">Planti Plant Shop values your privacy. This Privacy Policy describes how we collect, use, and protect your personal information when you use our website and services.</p>

                                        <h5 class="card-title mb-3">Information We Collect</h5>
                                        <p class="card-text">We collect various types of information to provide and improve our services, including:</p>
                                        <ul class="card-text mb-5">
                                            <li><strong>Personal Information:</strong> Name, email address, phone number, shipping address, and payment information.</li>
                                            <li><strong>Usage Data:</strong> Information on how you use our website, including IP address, browser type, and pages visited.</li>
                                        </ul>

                                        <h5 class="card-title mb-3">How We Use Your Information</h5>
                                        <p class="card-text">We use your information for the following purposes:</p>
                                        <ul class="card-text mb-5">
                                            <li>To process and fulfill your orders.</li>
                                            <li>To communicate with you about your orders, inquiries, and other requests.</li>
                                            <li>To improve our website and services.</li>
                                            <li>To send you promotional offers and updates, with your consent.</li>
                                        </ul>

                                        <h5 class="card-title mb-3">Sharing Your Information</h5>
                                        <p class="card-text">We do not sell your personal information to third parties. We may share your information with:</p>
                                        <ul class="card-text mb-5">
                                            <li>Service providers who assist us in operating our website and fulfilling orders.</li>
                                            <li>Legal authorities if required by law or to protect our rights.</li>
                                        </ul>

                                        <h5 class="card-title mb-3">Security of Your Information</h5>
                                        <p class="card-text mb-5">We implement appropriate security measures to protect your personal information from unauthorized access, alteration, disclosure, or destruction.</p>

                                        <h5 class="card-title mb-3">Your Rights</h5>
                                        <p class="card-text">You have the right to:</p>
                                        <ul class="card-text mb-5">
                                            <li>Access and review your personal information.</li>
                                            <li>Request corrections to any inaccurate or incomplete information.</li>
                                            <li>Request the deletion of your personal information, subject to certain exceptions.</li>
                                            <li>Opt-out of receiving promotional communications from us.</li>
                                        </ul>

                                        <h5 class="card-title mb-3">Cookies</h5>
                                        <p class="card-text mb-5">We use cookies to enhance your experience on our website. You can set your browser to refuse all cookies or to indicate when a cookie is being sent. However, some features of our website may not function properly without cookies.</p>

                                        <h5 class="card-title mb-3">Changes to This Privacy Policy</h5>
                                        <p class="card-text mb-5">We may update this Privacy Policy from time to time. Any changes will be posted on this page, and the updated policy will take effect immediately upon posting.</p>

                                        <h5 class="card-title mb-3">Contact Us</h5>
                                        <p class="card-text mb-5">If you have any questions about this Privacy Policy, please contact us at <a href="mailto:info@plantiplantshop.com" class="text-success">info@plantiplantshop.com</a> or call us at (123) 456-7890. We are here to help!</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- end cart page content -->

    </main>
    <!-- End Cart Section -->

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

    <!-- Sign in / Register Modal -->
    <div class="modal fade" id="signin-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content bg-light">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body mx-2">

                    <div class="tab-class shadow-none">
                        <div class="text-center">
                            <ul class="nav d-inline-flex">
                                <li class="nav-item formlinks activeforms signin-btn">
                                    <a class="d-flex m-2 py-2 text-decoration-none">
                                        <span style="width: 170px;">Sign In</span>
                                    </a>
                                </li>
                                <li class="nav-item formlinks register-btn">
                                    <a class="d-flex m-2 py-2 text-decoration-none">
                                        <span style="width: 170px;">Register</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="formcontainers py-4">
                            <div class="signin">
                                <form action="./login.php" method="post">
                                    <div class="form-groups">
                                        <label for="singin-email">Username or email address *</label>
                                        <input type="email" class="form-controls" id="singin-email" name="singinemail" required>
                                    </div><!-- End .form-group -->

                                    <div class="form-groups">
                                        <label for="singin-password">Password *</label>
                                        <input type="password" class="form-controls" id="singin-password" name="singinpassword" required>
                                    </div><!-- End .form-group -->

                                    <div class="form-footer d-flex align-items-center justify-content-between pt-2 pb-4">
                                        <button type="submit" class="btn-login" name="login">LOGIN IN</button>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="signincheckbox" required>
                                            <label class="form-check-label" for="signincheckbox"> Remember Me</label>
                                        </div>

                                        <a href="javascript:void(0);" class="forgot-link">Forgot Your Password?</a>
                                    </div><!-- End .form-footer -->
                                </form>
                                <div style="width: 100%;" class="text-center pb-1 errormessage" id="loginerror">
                                    <!-- password or email incorrect alert message -->
                                    <?php
                                    if (isset($_SESSION['loginerror'])) {
                                    ?>
                                        <span class="small text-danger">**<?php echo $_SESSION['loginerror'] ?>**</span>
                                    <?php
                                    } ?>
                                </div>
                                <div class="border-top">
                                    <p class="text-center pt-3 small">or sign in with</p>
                                    <div class="row pt-4">
                                        <div class="col-sm-6">
                                            <a href="javascript:void(0);" class="google-btn">
                                                <i class="fab fa-google pe-1"></i>
                                                Login With Google
                                            </a>
                                        </div><!-- End .col-6 -->
                                        <div class="col-sm-6">
                                            <a href="javascript:void(0);" class="facebook-btn">
                                                <i class="fab fa-facebook-f pe-1"></i>
                                                Login With Facebook
                                            </a>
                                        </div><!-- End .col-6 -->
                                    </div><!-- End .row -->
                                </div><!-- End .form-choice -->
                            </div>
                            <div class="register">
                                <form action="./register.php" method="post">
                                    <div class="form-groups">
                                        <label for="register-email">Your email address *</label>
                                        <input type="email" class="form-controls" id="register-email" name="registeremail" required>
                                    </div><!-- End .form-group -->

                                    <div class="form-groups">
                                        <label for="register-password">Password *</label>
                                        <input type="password" class="form-controls" id="register-password" name="registerpasword" required>
                                    </div><!-- End .form-group -->

                                    <div class="form-footer d-flex align-items-center justify-content-between pt-2 pb-4">
                                        <button type="submit" class="btn-login" name="signup">SIGN UP</button>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="registercheckbox" required>
                                            <label class="form-check-label" for="registercheckbox"> I agree to the </label>
                                            <a href="javascript:void(0);" class="policy-link">privacy policy *</a>
                                        </div>
                                    </div><!-- End .form-footer -->
                                </form>
                                <div style="width: 100%;" class="text-center pb-1 errormessage" id="signuperror">
                                    <!-- email exit alert message -->
                                    <?php
                                    if (isset($_SESSION['email_exit'])) {
                                    ?>
                                        <span class="small text-danger">**<?php echo $_SESSION['email_exit'] ?>**</span>
                                    <?php

                                    } ?>

                                    <!-- password not strong alert message -->
                                    <?php
                                    if (isset($_SESSION['password_not_strong'])) {
                                    ?>
                                        <span class="small text-danger">**<?php echo $_SESSION['password_not_strong'] ?>**</span>
                                    <?php

                                    } ?>
                                </div>
                                <div class="border-top">
                                    <p class="text-center pt-3 small">or sign in with</p>
                                    <div class="row pt-4">
                                        <div class="col-sm-6">
                                            <a href="javascript:void(0);" class="google-btn">
                                                <i class="fab fa-google pe-1"></i>
                                                Login With Google
                                            </a>
                                        </div><!-- End .col-6 -->
                                        <div class="col-sm-6">
                                            <a href="javascript:void(0);" class="facebook-btn">
                                                <i class="fab fa-facebook-f pe-1"></i>
                                                Login With Facebook
                                            </a>
                                        </div><!-- End .col-6 -->
                                    </div><!-- End .row -->
                                </div><!-- End .form-choice -->
                            </div>
                        </div>
                    </div>
                </div><!-- End .modal-body -->
            </div><!-- End .modal-content -->
        </div><!-- End .modal-dialog -->
    </div><!-- End .modal -->


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
    if (isset($_SESSION['email_exit']) || isset($_SESSION['password_not_strong'])) {
    ?>

        <script type="text/javascript">
            $(window).on('load', function() {
                $('#signin-modal').modal('show');

                $(".register-btn").addClass('activeforms').siblings().removeClass('activeforms');
                $('.register').show();
                $('.signin').hide();
            });
        </script>

    <?php
        unset($_SESSION['email_exit']);
        unset($_SESSION['password_not_strong']);
    } elseif (isset($_SESSION['loginerror'])) { ?>
        <script type="text/javascript">
            $(window).on('load', function() {
                $('#signin-modal').modal('show');

                $(".signin-btn").addClass('activeforms').siblings().removeClass('activeforms');
                $('.signin').show();
                $('.register').hide();
            });
        </script>
    <?php
        unset($_SESSION['loginerror']);
    }  ?>
    </script>

</body>

</html>