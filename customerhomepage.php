<?php

require_once "dbconnect.php";

if (!isset($_SESSION)) {
    session_start(); // to create succes if not exist
}

if (!isset($_SESSION['email'])) {
    header("Location:index.php");
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

function get6discountproduct()
{
    try {
        $conn = connect();
        $sql = "SELECT p.pid, p.pname, p.price, p.rating, d.discountpercent, d.discountprice FROM product p 
        JOIN discount d ON p.pid = d.productid ORDER BY RAND() LIMIT 6";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $product = $stmt->fetchAll();
        return $product;
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function getplantdiscount()
{
    try {
        $conn = connect();
        $sql = "SELECT p.pid, p.pname, p.price, p.rating, d.discountpercent, d.discountprice FROM product p 
        JOIN discount d ON p.pid = d.productid JOIN category c ON p.categoryid = c.ctgid WHERE c.description = 'plant'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $product = $stmt->fetchAll();
        return $product;
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function getseeddiscount()
{
    try {
        $conn = connect();
        $sql = "SELECT p.pid, p.pname, p.price, p.rating, d.discountpercent, d.discountprice FROM product p 
        JOIN discount d ON p.pid = d.productid JOIN category c ON p.categoryid = c.ctgid WHERE c.description = 'seed'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $product = $stmt->fetchAll();
        return $product;
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function getaccessorydiscount()
{
    try {
        $conn = connect();
        $sql = "SELECT p.pid, p.pname, p.price, p.rating, d.discountpercent, d.discountprice FROM product p 
        JOIN discount d ON p.pid = d.productid JOIN category c ON p.categoryid = c.ctgid WHERE c.description = 'accessory'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $product = $stmt->fetchAll();
        return $product;
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function get12newarrived()
{
    try {
        $conn = connect();
        $sql = "SELECT p.pid, p.pname, p.price, p.rating FROM product p 
        JOIN newarrived n ON p.pid = n.productid ORDER BY RAND() LIMIT 12";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $product = $stmt->fetchAll();
        return $product;
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function getplantnew()
{
    try {
        $conn = connect();
        $sql = "SELECT p.pid, p.pname, p.price, p.rating FROM product p 
        JOIN newarrived n ON p.pid = n.productid JOIN category c ON p.categoryid = c.ctgid WHERE c.description = 'plant' LIMIT 12";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $product = $stmt->fetchAll();
        return $product;
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function getseednew()
{
    try {
        $conn = connect();
        $sql = "SELECT p.pid, p.pname, p.price, p.rating FROM product p 
        JOIN newarrived n ON p.pid = n.productid JOIN category c ON p.categoryid = c.ctgid WHERE c.description = 'seed' LIMIT 12";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $product = $stmt->fetchAll();
        return $product;
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function getaccessorynew()
{
    try {
        $conn = connect();
        $sql = "SELECT p.pid, p.pname, p.price, p.rating FROM product p 
        JOIN newarrived n ON p.pid = n.productid JOIN category c ON p.categoryid = c.ctgid WHERE c.description = 'accessory' LIMIT 12";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $product = $stmt->fetchAll();
        return $product;
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
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

$catgories = getcategory();
$discounts = get6discountproduct();
$displants = getplantdiscount();
$disseeds = getseeddiscount();
$disaccessories = getaccessorydiscount();

$newarriveds = get12newarrived();
$newplants = getplantnew();
$newseeds = getseednew();
$newaccessories = getaccessorynew();

$email = $_SESSION['email'];
$userdatas = userdata($email);
$cid = $userdatas['cid'];
$getcartcounts = getcartcount($cid);
$cartcount = $getcartcounts['count'];

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
        <a href="./customerhomepage.php" class="btn-backtotops"><i class="fas fa-arrow-up"></i></a>
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


    <!-- Start Adv Section-->
    <section>
        <div class="px-3">
            <div class="row">
                <div class="col-lg-6 py-3">
                    <div class="cardlarges1 text-center text-uppercase">
                        <h5>New Collection</h5>
                        <h1>House Plants</h1>
                        <a href="./productpage.php?cid=newarrived" class="btn rounded-0 discoverbtns">Discover Now</a>
                    </div>
                </div>

                <div class="col-lg-6 py-3">
                    <div class="cardlarges2 text-center text-uppercase">
                        <h5>New Arrival</h5>
                        <h1>Seed Packets</h1>
                        <a href="./productpage.php?cid=newarrived" type="button" class="btn rounded-0 discoverbtns">Discover Now</a>
                    </div>
                </div>

                <div class="col-12">
                    <div class="row justify-content-center">

                        <div class="col-lg-4 col-sm-6">
                            <div class="card smallcards text-center text-uppercase">
                                <img src="./assets/img/adv/adv3.jpg" alt="adv3" />
                                <div class="cardinfos">
                                    <h5>Sale - 30% Off</h5>
                                    <h1>Mother Day</h1>
                                    <a href="./productpage.php?cid=discount" class="btn rounded-0 discoverbtns">Shop Now</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-6">
                            <div class="card smallcards text-center text-uppercase">
                                <img src="./assets/img/adv/adv8.jpg" alt="adv8" />
                                <div class="cardinfos">
                                    <h5>Get extra 10% off</h5>
                                    <h1>Promotion Item</h1>
                                    <a href="./productpage.php?cid=discount" class="btn rounded-0 discoverbtns">Shop Now</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-6 py-lg-0 py-sm-4">
                            <div class="card smallcards text-center text-uppercase">
                                <img src="./assets/img/adv/adv5.jpg" alt="adv5" />
                                <div class="cardinfos">
                                    <h5>Up to 20% off</h5>
                                    <h1>Organic Seeds</h1>
                                    <a href="./productpage.php?cid=discount" class="btn rounded-0 discoverbtns">Shop Now</a>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div col-12>
                    <div class="row justify-content-center pt-5 pb-4">
                        <div class="col-lg-3 col-sm-5 d-flex align-items-center">
                            <i class="fas fa-truck serviceicons"></i>
                            <div class="serviceinfos">
                                <h6>PAYMENT & DELIVERY</h6>
                                <p>Free shipping for orders over $200</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-5 d-flex align-items-center">
                            <i class="fas fa-undo serviceicons"></i>
                            <div class="serviceinfos">
                                <h6>RETURN & REFUND</h6>
                                <p>Free 100% money back guarantee</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-5 d-flex align-items-center">
                            <i class="fas fa-headphones-alt serviceicons"></i>
                            <div class="serviceinfos">
                                <h6>PAYMENT & DELIVERY</h6>
                                <p>Alway give online feedback 24/7</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- End Adv Section-->


    <!-- Start Featured Products Section-->
    <section class="feature-products">
        <div class="container-fluid">
            <div class="tab-class bg-light">
                <div class="row mt-5 pt-5 justify-content-center">
                    <div class="col-lg-12 text-start text-center titles">
                        <h3>FEATURED PRODUCTS</h3>
                    </div>
                    <div class="col-lg-12 col-md-6 col-sm-4 text-center">
                        <ul class="nav nav-pills d-inline-flex text-center menulists">
                            <li class="nav-item propertylists activeitems">
                                <a class="d-flex m-2 py-2 text-decoration-none active" data-bs-toggle="pill" href="#tab-1">
                                    <span class="" style="width: 150px;">All</span>
                                </a>
                            </li>
                            <li class="nav-item propertylists">
                                <a class="d-flex m-2 py-2 text-decoration-none" data-bs-toggle="pill" href="#tab-2">
                                    <span class="" style="width: 150px;">Plants</span>
                                </a>
                            </li>
                            <li class="nav-item propertylists">
                                <a class="d-flex m-2 py-2 text-decoration-none" data-bs-toggle="pill" href="#tab-3">
                                    <span class="" style="width: 150px;">Seeds</span>
                                </a>
                            </li>
                            <li class="nav-item propertylists">
                                <a class="d-flex m-2 py-2 text-decoration-none" data-bs-toggle="pill" href="#tab-4">
                                    <span class="" style="width: 150px;">Accessories</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="tab-content product-containers">
                    <div id="tab-1" class="tab-pane fade show px-3 py-3 active">
                        <div class="row g-4">
                            <?php foreach ($discounts as $discount) { ?>

                                <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2">
                                    <div class="product text-center">
                                        <figure class="product-media">

                                            <?php
                                            try {
                                                $pid = $discount['pid'];
                                                $conn = connect();
                                                $sql = "SELECT * from productimage WHERE productid = ?";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->execute([$pid]);
                                                $productimages = $stmt->fetchAll();
                                            } catch (PDOException $e) {
                                                echo $e->getMessage();
                                            }
                                            ?>

                                            <a href="productdetail.php?pid=<?php echo $pid ?>">
                                                <?php
                                                $imagecount = count($productimages);
                                                if ($imagecount == 1) {
                                                ?>
                                                    <img src="<?php echo $productimages[0]['url'] ?>" alt="Product<?php echo $pid ?>" class="product-image">
                                                    <img src="<?php echo $productimages[0]['url'] ?>" alt="Product<?php echo $pid ?>" class="product-image-hover">
                                                <?php } else { ?>
                                                    <img src="<?php echo $productimages[0]['url'] ?>" alt="Product<?php echo $pid ?>" class="product-image">
                                                    <img src="<?php echo $productimages[1]['url'] ?>" alt="Product<?php echo $pid ?>" class="product-image-hover">
                                                <?php } ?>
                                            </a>

                                            <div class="bg-danger p-1 discounts">
                                                <span class="text-white small"><?php echo $discount['discountpercent'] ?>% OFF</span>
                                            </div>

                                            <div class="product-action-vertical">
                                                <a href="./wishlist.php?pid=<?php echo $pid ?>" class="btn-wishlist"><i class="far fa-heart"></i></a>
                                                <a href="javascript:void(0);" class="btn-quickview" title="Quick view"><i class="fas fa-binoculars"></i></a>
                                            </div><!-- End .product-action-vertical -->

                                            <div class="product-action">
                                                <a href="./cart.php?pid=<?php echo $pid ?>" class="btn-product btn-cart"><span>add to cart</span></a>
                                            </div><!-- End .product-action -->
                                        </figure><!-- End .product-media -->

                                        <div class="product-body pb-4">
                                            <h3 class="product-title"><a href="productdetail.php?pid=<?php echo $discount['pid'] ?>"><?php echo $discount['pname'] ?></a></h3>
                                            <!-- End .product-title -->
                                            <div class="product-price">
                                                <del class="text-muted">$<?php echo $discount['price'] ?></del>
                                                <spa class="ps-1">$<?php echo $discount['discountprice'] ?></spa>
                                            </div><!-- End .product-price -->
                                            <div class="ratings-container">
                                                <div class="ratings m-0">
                                                    <?php
                                                    $rating = round($discount['rating']);
                                                    if ($rating != 0) {
                                                    ?>
                                                        <?php if ($rating == 1) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                            </div>
                                                        <?php } elseif ($rating == 2) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                            </div>
                                                        <?php } elseif ($rating == 3) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                            </div>
                                                        <?php } elseif ($rating == 4) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                            </div>
                                                        <?php } elseif ($rating == 5) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                            </div>
                                                        <?php } ?>

                                                    <?php } else { ?>
                                                        <div class="ratings-val">
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                        </div>
                                                    <?php } ?>

                                                </div><!-- End .ratings -->
                                                <?php
                                                try {
                                                    $conn = connect();
                                                    $sql = "SELECT COUNT(*) AS review_count FROM productreview WHERE productid=$pid";
                                                    $stmt = $conn->prepare($sql);
                                                    $stmt->execute();
                                                    $count = $stmt->fetch();
                                                    $conn = null;
                                                } catch (PDOException $e) {
                                                    echo $e->getMessage();
                                                }
                                                ?>
                                                <span class="ratings-text">( <?php echo $count['review_count'] ?> Review )</span>
                                            </div><!-- End .rating-container -->
                                        </div><!-- End .product-body -->
                                    </div><!-- End .product -->
                                </div>

                            <?php } ?>
                            <div class="d-flex justify-content-center">
                                <a href="./productpage.php?cid=discount" class="loadmore-btn">Load More <i class="fas fa-arrow-right loadmoreicons"></i></a>
                            </div>
                        </div>
                    </div>
                    <div id="tab-2" class="tab-pane fade show px-3 py-3">
                        <div class="row g-4">
                            <?php
                            $length = 0;
                            if (sizeof($displants) >= 6) {
                                $length = 6;
                            } else {
                                $length = sizeof($displants);
                            }
                            for ($i = 0; $i < $length; $i++) {
                                $discount = $displants[$i];
                            ?>

                                <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2">
                                    <div class="product text-center">
                                        <figure class="product-media">

                                            <?php
                                            try {
                                                $pid = $discount['pid'];
                                                $conn = connect();
                                                $sql = "SELECT * from productimage WHERE productid = ?";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->execute([$pid]);
                                                $productimages = $stmt->fetchAll();
                                            } catch (PDOException $e) {
                                                echo $e->getMessage();
                                            }
                                            ?>

                                            <a href="productdetail.php?pid=<?php echo $pid ?>">
                                                <?php
                                                $imagecount = count($productimages);
                                                if ($imagecount == 1) {
                                                ?>
                                                    <img src="<?php echo $productimages[0]['url'] ?>" alt="Product<?php echo $pid ?>" class="product-image">
                                                    <img src="<?php echo $productimages[0]['url'] ?>" alt="Product<?php echo $pid ?>" class="product-image-hover">
                                                <?php } else { ?>
                                                    <img src="<?php echo $productimages[0]['url'] ?>" alt="Product<?php echo $pid ?>" class="product-image">
                                                    <img src="<?php echo $productimages[1]['url'] ?>" alt="Product<?php echo $pid ?>" class="product-image-hover">
                                                <?php } ?>
                                            </a>

                                            <div class="bg-danger p-1 discounts">
                                                <span class="text-white small"><?php echo $discount['discountpercent'] ?>% OFF</span>
                                            </div>

                                            <div class="product-action-vertical">
                                                <a href="./wishlist.php?pid=<?php echo $pid ?>" class="btn-wishlist"><i class="far fa-heart"></i></a>
                                                <a href="javascript:void(0);" class="btn-quickview" title="Quick view"><i class="fas fa-binoculars"></i></a>
                                            </div><!-- End .product-action-vertical -->

                                            <div class="product-action">
                                                <a href="./cart.php?pid=<?php echo $pid ?>" class="btn-product btn-cart"><span>add to cart</span></a>
                                            </div><!-- End .product-action -->

                                        </figure><!-- End .product-media -->

                                        <div class="product-body px-3 pb-4">
                                            <h3 class="product-title"><a href="productdetail.php?pid=<?php echo $discount['pid'] ?>"><?php echo $discount['pname'] ?></a></h3>
                                            <!-- End .product-title -->
                                            <div class="product-price">
                                                <del class="text-muted">$<?php echo $discount['price'] ?></del>
                                                <spa class="ps-1">$<?php echo $discount['discountprice'] ?></spa>
                                            </div><!-- End .product-price -->
                                            <div class="ratings-container">
                                                <div class="ratings m-0">
                                                    <?php
                                                    $rating = round($discount['rating']);
                                                    // echo $rating;
                                                    if ($rating != 0) {
                                                    ?>
                                                        <?php if ($rating == 1) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                            </div>
                                                        <?php } elseif ($rating == 2) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                            </div>
                                                        <?php } elseif ($rating == 3) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                            </div>
                                                        <?php } elseif ($rating == 4) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                            </div>
                                                        <?php } elseif ($rating == 5) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                            </div>
                                                        <?php } ?>

                                                    <?php } else { ?>
                                                        <div class="ratings-val">
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                        </div>
                                                    <?php } ?>
                                                </div><!-- End .ratings -->
                                                <?php
                                                try {
                                                    $conn = connect();
                                                    $sql = "SELECT COUNT(*) AS review_count FROM productreview WHERE productid=$pid";
                                                    $stmt = $conn->prepare($sql);
                                                    $stmt->execute();
                                                    $count = $stmt->fetch();
                                                    $conn = null;
                                                } catch (PDOException $e) {
                                                    echo $e->getMessage();
                                                }
                                                ?>
                                                <span class="ratings-text">( <?php echo $count['review_count'] ?> Review )</span>
                                            </div><!-- End .rating-container -->
                                        </div><!-- End .product-body -->
                                    </div><!-- End .product -->
                                </div>

                            <?php } ?>
                            <div class="d-flex justify-content-center">
                                <a href="./productpage.php?cid=discount" class="loadmore-btn">Load More <i class="fas fa-arrow-right loadmoreicons"></i></a>
                            </div>
                        </div>
                    </div>
                    <div id="tab-3" class="tab-pane fade show px-3 py-3">
                        <div class="row g-4">
                            <?php
                            $length = 0;
                            if (sizeof($disseeds) >= 6) {
                                $length = 6;
                            } else {
                                $length = sizeof($disseeds);
                            }
                            for ($i = 0; $i < $length; $i++) {
                                $discount = $disseeds[$i];
                            ?>

                                <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2">
                                    <div class="product text-center">
                                        <figure class="product-media">

                                            <?php
                                            try {
                                                $pid = $discount['pid'];
                                                $conn = connect();
                                                $sql = "SELECT * from productimage WHERE productid = ?";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->execute([$pid]);
                                                $productimages = $stmt->fetchAll();
                                            } catch (PDOException $e) {
                                                echo $e->getMessage();
                                            }
                                            ?>

                                            <a href="productdetail.php?pid=<?php echo $pid ?>">
                                                <?php
                                                $imagecount = count($productimages);
                                                if ($imagecount == 1) {
                                                ?>
                                                    <img src="<?php echo $productimages[0]['url'] ?>" alt="Product<?php echo $pid ?>" class="product-image">
                                                    <img src="<?php echo $productimages[0]['url'] ?>" alt="Product<?php echo $pid ?>" class="product-image-hover">
                                                <?php } else { ?>
                                                    <img src="<?php echo $productimages[0]['url'] ?>" alt="Product<?php echo $pid ?>" class="product-image">
                                                    <img src="<?php echo $productimages[1]['url'] ?>" alt="Product<?php echo $pid ?>" class="product-image-hover">
                                                <?php } ?>
                                            </a>

                                            <div class="bg-danger p-1 discounts">
                                                <span class="text-white small"><?php echo $discount['discountpercent'] ?>% OFF</span>
                                            </div>

                                            <div class="product-action-vertical">
                                                <a href="./wishlist.php?pid=<?php echo $pid ?>" class="btn-wishlist"><i class="far fa-heart"></i></a>
                                                <a href="javascript:void(0);" class="btn-quickview" title="Quick view"><i class="fas fa-binoculars"></i></a>
                                            </div><!-- End .product-action-vertical -->

                                            <div class="product-action">
                                                <a href="./cart.php?pid=<?php echo $pid ?>" class="btn-product btn-cart"><span>add to cart</span></a>
                                            </div><!-- End .product-action -->

                                        </figure><!-- End .product-media -->

                                        <div class="product-body px-3 pb-4">
                                            <h3 class="product-title"><a href="productdetail.php?pid=<?php echo $discount['pid'] ?>"><?php echo $discount['pname'] ?></a></h3>
                                            <!-- End .product-title -->
                                            <div class="product-price">
                                                <del class="text-muted">$<?php echo $discount['price'] ?></del>
                                                <spa class="ps-1">$<?php echo $discount['discountprice'] ?></spa>
                                            </div><!-- End .product-price -->
                                            <div class="ratings-container">
                                                <div class="ratings m-0">
                                                    <?php
                                                    $rating = round($discount['rating']);
                                                    // echo $rating;
                                                    if ($rating != 0) {
                                                    ?>
                                                        <?php if ($rating == 1) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                            </div>
                                                        <?php } elseif ($rating == 2) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                            </div>
                                                        <?php } elseif ($rating == 3) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                            </div>
                                                        <?php } elseif ($rating == 4) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                            </div>
                                                        <?php } elseif ($rating == 5) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                            </div>
                                                        <?php } ?>

                                                    <?php } else { ?>
                                                        <div class="ratings-val">
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                        </div>
                                                    <?php } ?>
                                                </div><!-- End .ratings -->
                                                <?php
                                                try {
                                                    $conn = connect();
                                                    $sql = "SELECT COUNT(*) AS review_count FROM productreview WHERE productid=$pid";
                                                    $stmt = $conn->prepare($sql);
                                                    $stmt->execute();
                                                    $count = $stmt->fetch();
                                                    $conn = null;
                                                } catch (PDOException $e) {
                                                    echo $e->getMessage();
                                                }
                                                ?>
                                                <span class="ratings-text">( <?php echo $count['review_count'] ?> Review )</span>
                                            </div><!-- End .rating-container -->
                                        </div><!-- End .product-body -->
                                    </div><!-- End .product -->
                                </div>

                            <?php } ?>
                            <div class="d-flex justify-content-center">
                                <a href="./productpage.php?cid=discount" class="loadmore-btn">Load More <i class="fas fa-arrow-right loadmoreicons"></i></a>
                            </div>
                        </div>
                    </div>
                    <div id="tab-4" class="tab-pane fade show px-3 py-3">
                        <div class="row g-4">
                            <?php
                            $length = 0;
                            if (sizeof($disaccessories) >= 6) {
                                $length = 6;
                            } else {
                                $length = sizeof($disaccessories);
                            }
                            for ($i = 0; $i < $length; $i++) {
                                $discount = $disaccessories[$i];
                            ?>

                                <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2">
                                    <div class="product text-center">
                                        <figure class="product-media">

                                            <?php
                                            try {
                                                $pid = $discount['pid'];
                                                $conn = connect();
                                                $sql = "SELECT * from productimage WHERE productid = ?";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->execute([$pid]);
                                                $productimages = $stmt->fetchAll();
                                            } catch (PDOException $e) {
                                                echo $e->getMessage();
                                            }
                                            ?>

                                            <a href="productdetail.php?pid=<?php echo $pid ?>">
                                                <?php
                                                $imagecount = count($productimages);
                                                if ($imagecount == 1) {
                                                ?>
                                                    <img src="<?php echo $productimages[0]['url'] ?>" alt="Product<?php echo $pid ?>" class="product-image">
                                                    <img src="<?php echo $productimages[0]['url'] ?>" alt="Product<?php echo $pid ?>" class="product-image-hover">
                                                <?php } else { ?>
                                                    <img src="<?php echo $productimages[0]['url'] ?>" alt="Product<?php echo $pid ?>" class="product-image">
                                                    <img src="<?php echo $productimages[1]['url'] ?>" alt="Product<?php echo $pid ?>" class="product-image-hover">
                                                <?php } ?>
                                            </a>

                                            <div class="bg-danger p-1 discounts">
                                                <span class="text-white small"><?php echo $discount['discountpercent'] ?>% OFF</span>
                                            </div>

                                            <div class="product-action-vertical">
                                                <a href="./wishlist.php?pid=<?php echo $pid ?>" class="btn-wishlist"><i class="far fa-heart"></i></a>
                                                <a href="javascript:void(0);" class="btn-quickview" title="Quick view"><i class="fas fa-binoculars"></i></a>
                                            </div><!-- End .product-action-vertical -->

                                            <div class="product-action">
                                                <a href="./cart.php?pid=<?php echo $pid ?>" class="btn-product btn-cart"><span>add to cart</span></a>
                                            </div><!-- End .product-action -->

                                        </figure><!-- End .product-media -->

                                        <div class="product-body px-3 pb-4">
                                            <h3 class="product-title"><a href="productdetail.php?pid=<?php echo $discount['pid'] ?>"><?php echo $discount['pname'] ?></a></h3>
                                            <!-- End .product-title -->
                                            <div class="product-price">
                                                <del class="text-muted">$<?php echo $discount['price'] ?></del>
                                                <spa class="ps-1">$<?php echo $discount['discountprice'] ?></spa>
                                            </div><!-- End .product-price -->
                                            <div class="ratings-container">
                                                <div class="ratings m-0">
                                                    <?php
                                                    $rating = round($discount['rating']);
                                                    // echo $rating;
                                                    if ($rating != 0) {
                                                    ?>
                                                        <?php if ($rating == 1) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                            </div>
                                                        <?php } elseif ($rating == 2) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                            </div>
                                                        <?php } elseif ($rating == 3) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                            </div>
                                                        <?php } elseif ($rating == 4) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                            </div>
                                                        <?php } elseif ($rating == 5) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                            </div>
                                                        <?php } ?>

                                                    <?php } else { ?>
                                                        <div class="ratings-val">
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                        </div>
                                                    <?php } ?>
                                                </div><!-- End .ratings -->
                                                <?php
                                                try {
                                                    $conn = connect();
                                                    $sql = "SELECT COUNT(*) AS review_count FROM productreview WHERE productid=$pid";
                                                    $stmt = $conn->prepare($sql);
                                                    $stmt->execute();
                                                    $count = $stmt->fetch();
                                                    $conn = null;
                                                } catch (PDOException $e) {
                                                    echo $e->getMessage();
                                                }
                                                ?>
                                                <span class="ratings-text">( <?php echo $count['review_count'] ?> Review )</span>
                                            </div><!-- End .rating-container -->
                                        </div><!-- End .product-body -->
                                    </div><!-- End .product -->
                                </div>

                            <?php } ?>
                            <div class="d-flex justify-content-center">
                                <a href="./productpage.php?cid=discount" class="loadmore-btn">Load More <i class="fas fa-arrow-right loadmoreicons"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Featured Products Section-->


    <!-- Start New Arrivals Section-->
    <section class="bg-white">
        <div class="container-fluid bg-white">
            <div class="tab-class bg-white">
                <div class="row mt-5 justify-content-center">
                    <div class="col-lg-12 text-start text-center titles">
                        <h3>NEW ARRIVED</h3>
                    </div>
                    <div class="col-lg-12 col-md-6 col-sm-4 text-center">
                        <ul class="nav nav-pills d-inline-flex text-center menulists">
                            <li class="nav-item propertylists activeitems">
                                <a class="d-flex m-2 py-2 text-decoration-none active" data-bs-toggle="pill" href="#tab-5">
                                    <span class="" style="width: 150px;">All</span>
                                </a>
                            </li>
                            <li class="nav-item propertylists">
                                <a class="d-flex m-2 py-2 text-decoration-none" data-bs-toggle="pill" href="#tab-6">
                                    <span class="" style="width: 150px;">Plants</span>
                                </a>
                            </li>
                            <li class="nav-item propertylists">
                                <a class="d-flex m-2 py-2 text-decoration-none" data-bs-toggle="pill" href="#tab-7">
                                    <span class="" style="width: 150px;">Seeds</span>
                                </a>
                            </li>
                            <li class="nav-item propertylists">
                                <a class="d-flex m-2 py-2 text-decoration-none" data-bs-toggle="pill" href="#tab-8">
                                    <span class="" style="width: 150px;">Accessories</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="tab-content newarrivals">
                    <div id="tab-5" class="tab-pane fade show px-3 py-3 active">
                        <div class="row g-4">
                            <?php foreach ($newarriveds as $newarrived) { ?>

                                <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2">
                                    <div class="product text-center shadow">
                                        <figure class="product-media">

                                            <?php
                                            try {
                                                $pid = $newarrived['pid'];
                                                $conn = connect();

                                                $sql = "SELECT * FROM productimage WHERE productid = ?";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->execute([$pid]);
                                                $productimages = $stmt->fetchAll();

                                                $sql = "SELECT * FROM discount WHERE productid=?";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->execute([$pid]);
                                                $discount = $stmt->fetch();
                                            } catch (PDOException $e) {
                                                echo $e->getMessage();
                                            }
                                            ?>

                                            <a href="productdetail.php?pid=<?php echo $pid ?>">
                                                <?php
                                                $imagecount = count($productimages);
                                                if ($imagecount == 1) {
                                                ?>
                                                    <img src="<?php echo $productimages[0]['url'] ?>" alt="Product<?php echo $pid ?>" class="product-image">
                                                    <img src="<?php echo $productimages[0]['url'] ?>" alt="Product<?php echo $pid ?>" class="product-image-hover">
                                                <?php } else { ?>
                                                    <img src="<?php echo $productimages[0]['url'] ?>" alt="Product<?php echo $pid ?>" class="product-image">
                                                    <img src="<?php echo $productimages[1]['url'] ?>" alt="Product<?php echo $pid ?>" class="product-image-hover">
                                                <?php } ?>
                                            </a>

                                            <?php if ($discount != null) { ?>
                                                <div class="bg-danger p-1 discounts">
                                                    <span class="text-white small"><?php echo $discount['discountpercent'] ?>% OFF</span>
                                                </div>
                                                <div class="bg-warning news">
                                                    <span class="text-white">New</span>
                                                </div>
                                            <?php } else { ?>
                                                <div class="bg-warning newarriveds">
                                                    <span class="text-white">New</span>
                                                </div>
                                            <?php } ?>

                                            <div class="product-action-vertical">
                                                <a href="./wishlist.php?pid=<?php echo $pid ?>" class="btn-wishlist"><i class="far fa-heart"></i></a>
                                                <a href="javascript:void(0);" class="btn-quickview" title="Quick view"><i class="fas fa-binoculars"></i></a>
                                            </div><!-- End .product-action-vertical -->

                                            <div class="product-action">
                                                <a href="./cart.php?pid=<?php echo $pid ?>" class="btn-product btn-cart"><span>add to cart</span></a>
                                            </div><!-- End .product-action -->

                                        </figure><!-- End .product-media -->

                                        <div class="product-body pb-4">
                                            <h3 class="product-title"><a href="productdetail.php?pid=<?php echo $newarrived['pid'] ?>"><?php echo $newarrived['pname'] ?></a></h3>
                                            <!-- End .product-title -->
                                            <?php if ($discount != null) { ?>
                                                <div class="product-price">
                                                    <del class="text-muted">$<?php echo $newarrived['price'] ?></del>
                                                    <spa class="ps-1">$<?php echo $discount['discountprice'] ?></spa>
                                                </div><!-- End .product-price -->
                                            <?php } else { ?>
                                                <div class="product-price">
                                                    $<?php echo $newarrived['price'] ?>
                                                </div><!-- End .product-price -->
                                            <?php } ?>
                                            <div class="ratings-container">
                                                <div class="ratings m-0">
                                                    <?php
                                                    $rating = round($newarrived['rating']);
                                                    if ($rating != 0) {
                                                    ?>
                                                        <?php if ($rating == 1) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                            </div>
                                                        <?php } elseif ($rating == 2) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                            </div>
                                                        <?php } elseif ($rating == 3) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                            </div>
                                                        <?php } elseif ($rating == 4) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                            </div>
                                                        <?php } elseif ($rating == 5) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                            </div>
                                                        <?php } ?>

                                                    <?php } else { ?>
                                                        <div class="ratings-val">
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                        </div>
                                                    <?php } ?>

                                                </div><!-- End .ratings -->
                                                <?php
                                                try {
                                                    $conn = connect();
                                                    $sql = "SELECT COUNT(*) AS review_count FROM productreview WHERE productid=$pid";
                                                    $stmt = $conn->prepare($sql);
                                                    $stmt->execute();
                                                    $count = $stmt->fetch();
                                                    $conn = null;
                                                } catch (PDOException $e) {
                                                    echo $e->getMessage();
                                                }
                                                ?>
                                                <span class="ratings-text">( <?php echo $count['review_count'] ?> Review )</span>
                                            </div><!-- End .rating-container -->
                                        </div><!-- End .product-body -->
                                    </div><!-- End .product -->
                                </div>

                            <?php } ?>
                            <div class="d-flex justify-content-center">
                                <a href="./productpage.php?cid=newarrived" class="loadmore-btn">Load More <i class="fas fa-arrow-right loadmoreicons"></i></a>
                            </div>
                        </div>
                    </div>
                    <div id="tab-6" class="tab-pane fade show px-3 py-3">
                        <div class="row g-4">
                            <?php foreach ($newplants as $newarrived) { ?>

                                <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2">
                                    <div class="product text-center shadow">
                                        <figure class="product-media">

                                            <?php
                                            try {
                                                $pid = $newarrived['pid'];
                                                $conn = connect();

                                                $sql = "SELECT * FROM productimage WHERE productid = ?";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->execute([$pid]);
                                                $productimages = $stmt->fetchAll();

                                                $sql = "SELECT * FROM discount WHERE productid=?";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->execute([$pid]);
                                                $discount = $stmt->fetch();
                                            } catch (PDOException $e) {
                                                echo $e->getMessage();
                                            }
                                            ?>

                                            <a href="productdetail.php?pid=<?php echo $pid ?>">
                                                <?php
                                                $imagecount = count($productimages);
                                                if ($imagecount == 1) {
                                                ?>
                                                    <img src="<?php echo $productimages[0]['url'] ?>" alt="Product<?php echo $pid ?>" class="product-image">
                                                    <img src="<?php echo $productimages[0]['url'] ?>" alt="Product<?php echo $pid ?>" class="product-image-hover">
                                                <?php } else { ?>
                                                    <img src="<?php echo $productimages[0]['url'] ?>" alt="Product<?php echo $pid ?>" class="product-image">
                                                    <img src="<?php echo $productimages[1]['url'] ?>" alt="Product<?php echo $pid ?>" class="product-image-hover">
                                                <?php } ?>
                                            </a>

                                            <?php if ($discount != null) { ?>
                                                <div class="bg-danger p-1 discounts">
                                                    <span class="text-white small"><?php echo $discount['discountpercent'] ?>% OFF</span>
                                                </div>
                                                <div class="bg-warning news">
                                                    <span class="text-white">New</span>
                                                </div>
                                            <?php } else { ?>
                                                <div class="bg-warning newarriveds">
                                                    <span class="text-white">New</span>
                                                </div>
                                            <?php } ?>

                                            <div class="product-action-vertical">
                                                <a href="./wishlist.php?pid=<?php echo $pid ?>" class="btn-wishlist"><i class="far fa-heart"></i></a>
                                                <a href="javascript:void(0);" class="btn-quickview" title="Quick view"><i class="fas fa-binoculars"></i></a>
                                            </div><!-- End .product-action-vertical -->

                                            <div class="product-action">
                                                <a href="./cart.php?pid=<?php echo $pid ?>" class="btn-product btn-cart"><span>add to cart</span></a>
                                            </div><!-- End .product-action -->

                                        </figure><!-- End .product-media -->

                                        <div class="product-body pb-4">
                                            <h3 class="product-title"><a href="productdetail.php?pid=<?php echo $newarrived['pid'] ?>"><?php echo $newarrived['pname'] ?></a></h3>
                                            <!-- End .product-title -->
                                            <?php if ($discount != null) { ?>
                                                <div class="product-price">
                                                    <del class="text-muted">$<?php echo $newarrived['price'] ?></del>
                                                    <spa class="ps-1">$<?php echo $discount['discountprice'] ?></spa>
                                                </div><!-- End .product-price -->
                                            <?php } else { ?>
                                                <div class="product-price">
                                                    $<?php echo $newarrived['price'] ?>
                                                </div><!-- End .product-price -->
                                            <?php } ?>
                                            <div class="ratings-container">
                                                <div class="ratings m-0">
                                                    <?php
                                                    $rating = round($newarrived['rating']);
                                                    if ($rating != 0) {
                                                    ?>
                                                        <?php if ($rating == 1) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                            </div>
                                                        <?php } elseif ($rating == 2) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                            </div>
                                                        <?php } elseif ($rating == 3) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                            </div>
                                                        <?php } elseif ($rating == 4) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                            </div>
                                                        <?php } elseif ($rating == 5) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                            </div>
                                                        <?php } ?>

                                                    <?php } else { ?>
                                                        <div class="ratings-val">
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                        </div>
                                                    <?php } ?>

                                                </div><!-- End .ratings -->
                                                <?php
                                                try {
                                                    $conn = connect();
                                                    $sql = "SELECT COUNT(*) AS review_count FROM productreview WHERE productid=$pid";
                                                    $stmt = $conn->prepare($sql);
                                                    $stmt->execute();
                                                    $count = $stmt->fetch();
                                                    $conn = null;
                                                } catch (PDOException $e) {
                                                    echo $e->getMessage();
                                                }
                                                ?>
                                                <span class="ratings-text">( <?php echo $count['review_count'] ?> Review )</span>
                                            </div><!-- End .rating-container -->
                                        </div><!-- End .product-body -->
                                    </div><!-- End .product -->
                                </div>

                            <?php } ?>
                            <div class="d-flex justify-content-center">
                                <a href="./productpage.php?cid=newarrived" class="loadmore-btn">Load More <i class="fas fa-arrow-right loadmoreicons"></i></a>
                            </div>
                        </div>
                    </div>
                    <div id="tab-7" class="tab-pane fade show px-3 py-3">
                        <div class="row g-4">
                            <?php foreach ($newseeds as $newarrived) { ?>

                                <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2">
                                    <div class="product text-center shadow">
                                        <figure class="product-media">

                                            <?php
                                            try {
                                                $pid = $newarrived['pid'];
                                                $conn = connect();

                                                $sql = "SELECT * FROM productimage WHERE productid = ?";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->execute([$pid]);
                                                $productimages = $stmt->fetchAll();

                                                $sql = "SELECT * FROM discount WHERE productid=?";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->execute([$pid]);
                                                $discount = $stmt->fetch();
                                            } catch (PDOException $e) {
                                                echo $e->getMessage();
                                            }
                                            ?>

                                            <a href="productdetail.php?pid=<?php echo $pid ?>">
                                                <?php
                                                $imagecount = count($productimages);
                                                if ($imagecount == 1) {
                                                ?>
                                                    <img src="<?php echo $productimages[0]['url'] ?>" alt="Product<?php echo $pid ?>" class="product-image">
                                                    <img src="<?php echo $productimages[0]['url'] ?>" alt="Product<?php echo $pid ?>" class="product-image-hover">
                                                <?php } else { ?>
                                                    <img src="<?php echo $productimages[0]['url'] ?>" alt="Product<?php echo $pid ?>" class="product-image">
                                                    <img src="<?php echo $productimages[1]['url'] ?>" alt="Product<?php echo $pid ?>" class="product-image-hover">
                                                <?php } ?>
                                            </a>

                                            <?php if ($discount != null) { ?>
                                                <div class="bg-danger p-1 discounts">
                                                    <span class="text-white small"><?php echo $discount['discountpercent'] ?>% OFF</span>
                                                </div>
                                                <div class="bg-warning news">
                                                    <span class="text-white">New</span>
                                                </div>
                                            <?php } else { ?>
                                                <div class="bg-warning newarriveds">
                                                    <span class="text-white">New</span>
                                                </div>
                                            <?php } ?>

                                            <div class="product-action-vertical">
                                                <a href="./wishlist.php?pid=<?php echo $pid ?>" class="btn-wishlist"><i class="far fa-heart"></i></a>
                                                <a href="javascript:void(0);" class="btn-quickview" title="Quick view"><i class="fas fa-binoculars"></i></a>
                                            </div><!-- End .product-action-vertical -->

                                            <div class="product-action">
                                                <a href="./cart.php?pid=<?php echo $pid ?>" class="btn-product btn-cart"><span>add to cart</span></a>
                                            </div><!-- End .product-action -->

                                        </figure><!-- End .product-media -->

                                        <div class="product-body pb-4">
                                            <h3 class="product-title"><a href="productdetail.php?pid=<?php echo $newarrived['pid'] ?>"><?php echo $newarrived['pname'] ?></a></h3>
                                            <!-- End .product-title -->
                                            <?php if ($discount != null) { ?>
                                                <div class="product-price">
                                                    <del class="text-muted">$<?php echo $newarrived['price'] ?></del>
                                                    <spa class="ps-1">$<?php echo $discount['discountprice'] ?></spa>
                                                </div><!-- End .product-price -->
                                            <?php } else { ?>
                                                <div class="product-price">
                                                    $<?php echo $newarrived['price'] ?>
                                                </div><!-- End .product-price -->
                                            <?php } ?>
                                            <div class="ratings-container">
                                                <div class="ratings m-0">
                                                    <?php
                                                    $rating = round($newarrived['rating']);
                                                    if ($rating != 0) {
                                                    ?>
                                                        <?php if ($rating == 1) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                            </div>
                                                        <?php } elseif ($rating == 2) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                            </div>
                                                        <?php } elseif ($rating == 3) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                            </div>
                                                        <?php } elseif ($rating == 4) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                            </div>
                                                        <?php } elseif ($rating == 5) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                            </div>
                                                        <?php } ?>

                                                    <?php } else { ?>
                                                        <div class="ratings-val">
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                        </div>
                                                    <?php } ?>

                                                </div><!-- End .ratings -->
                                                <?php
                                                try {
                                                    $conn = connect();
                                                    $sql = "SELECT COUNT(*) AS review_count FROM productreview WHERE productid=$pid";
                                                    $stmt = $conn->prepare($sql);
                                                    $stmt->execute();
                                                    $count = $stmt->fetch();
                                                    $conn = null;
                                                } catch (PDOException $e) {
                                                    echo $e->getMessage();
                                                }
                                                ?>
                                                <span class="ratings-text">( <?php echo $count['review_count'] ?> Review )</span>
                                            </div><!-- End .rating-container -->
                                        </div><!-- End .product-body -->
                                    </div><!-- End .product -->
                                </div>

                            <?php } ?>
                            <div class="d-flex justify-content-center">
                                <a href="./productpage.php?cid=newarrived" class="loadmore-btn">Load More <i class="fas fa-arrow-right loadmoreicons"></i></a>
                            </div>
                        </div>
                    </div>
                    <div id="tab-8" class="tab-pane fade show px-3 py-3">
                        <div class="row g-4">
                            <?php foreach ($newaccessories as $newarrived) { ?>

                                <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2">
                                    <div class="product text-center shadow">
                                        <figure class="product-media">

                                            <?php
                                            try {
                                                $pid = $newarrived['pid'];
                                                $conn = connect();

                                                $sql = "SELECT * FROM productimage WHERE productid = ?";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->execute([$pid]);
                                                $productimages = $stmt->fetchAll();

                                                $sql = "SELECT * FROM discount WHERE productid=?";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->execute([$pid]);
                                                $discount = $stmt->fetch();
                                            } catch (PDOException $e) {
                                                echo $e->getMessage();
                                            }
                                            ?>

                                            <a href="productdetail.php?pid=<?php echo $pid ?>">
                                                <?php
                                                $imagecount = count($productimages);
                                                if ($imagecount == 1) {
                                                ?>
                                                    <img src="<?php echo $productimages[0]['url'] ?>" alt="Product<?php echo $pid ?>" class="product-image">
                                                    <img src="<?php echo $productimages[0]['url'] ?>" alt="Product<?php echo $pid ?>" class="product-image-hover">
                                                <?php } else { ?>
                                                    <img src="<?php echo $productimages[0]['url'] ?>" alt="Product<?php echo $pid ?>" class="product-image">
                                                    <img src="<?php echo $productimages[1]['url'] ?>" alt="Product<?php echo $pid ?>" class="product-image-hover">
                                                <?php } ?>
                                            </a>

                                            <?php if ($discount != null) { ?>
                                                <div class="bg-danger p-1 discounts">
                                                    <span class="text-white small"><?php echo $discount['discountpercent'] ?>% OFF</span>
                                                </div>
                                                <div class="bg-warning news">
                                                    <span class="text-white">New</span>
                                                </div>
                                            <?php } else { ?>
                                                <div class="bg-warning newarriveds">
                                                    <span class="text-white">New</span>
                                                </div>
                                            <?php } ?>

                                            <div class="product-action-vertical">
                                                <a href="./wishlist.php?pid=<?php echo $pid ?>" class="btn-wishlist"><i class="far fa-heart"></i></a>
                                                <a href="javascript:void(0);" class="btn-quickview" title="Quick view"><i class="fas fa-binoculars"></i></a>
                                            </div><!-- End .product-action-vertical -->

                                            <div class="product-action">
                                                <a href="./cart.php?pid=<?php echo $pid ?>" class="btn-product btn-cart"><span>add to cart</span></a>
                                            </div><!-- End .product-action -->

                                        </figure><!-- End .product-media -->

                                        <div class="product-body pb-4">
                                            <h3 class="product-title"><a href="productdetail.php?pid=<?php echo $newarrived['pid'] ?>"><?php echo $newarrived['pname'] ?></a></h3>
                                            <!-- End .product-title -->
                                            <?php if ($discount != null) { ?>
                                                <div class="product-price">
                                                    <del class="text-muted">$<?php echo $newarrived['price'] ?></del>
                                                    <spa class="ps-1">$<?php echo $discount['discountprice'] ?></spa>
                                                </div><!-- End .product-price -->
                                            <?php } else { ?>
                                                <div class="product-price">
                                                    $<?php echo $newarrived['price'] ?>
                                                </div><!-- End .product-price -->
                                            <?php } ?>
                                            <div class="ratings-container">
                                                <div class="ratings m-0">
                                                    <?php
                                                    $rating = round($newarrived['rating']);
                                                    if ($rating != 0) {
                                                    ?>
                                                        <?php if ($rating == 1) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                            </div>
                                                        <?php } elseif ($rating == 2) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                            </div>
                                                        <?php } elseif ($rating == 3) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                            </div>
                                                        <?php } elseif ($rating == 4) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-muted"></i>
                                                            </div>
                                                        <?php } elseif ($rating == 5) { ?>
                                                            <div class="ratings-val">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                            </div>
                                                        <?php } ?>

                                                    <?php } else { ?>
                                                        <div class="ratings-val">
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                        </div>
                                                    <?php } ?>

                                                </div><!-- End .ratings -->
                                                <?php
                                                try {
                                                    $conn = connect();
                                                    $sql = "SELECT COUNT(*) AS review_count FROM productreview WHERE productid=$pid";
                                                    $stmt = $conn->prepare($sql);
                                                    $stmt->execute();
                                                    $count = $stmt->fetch();
                                                    $conn = null;
                                                } catch (PDOException $e) {
                                                    echo $e->getMessage();
                                                }
                                                ?>
                                                <span class="ratings-text">( <?php echo $count['review_count'] ?> Review )</span>
                                            </div><!-- End .rating-container -->
                                        </div><!-- End .product-body -->
                                    </div><!-- End .product -->
                                </div>

                            <?php } ?>
                            <div class="d-flex justify-content-center">
                                <a href="./productpage.php?cid=newarrived" class="loadmore-btn">Load More <i class="fas fa-arrow-right loadmoreicons"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Start New Arrivals Section-->


    <!-- Start Carousel Section-->
    <section class="">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">

                    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                        </div>
                        <div class="carousel-inner">
                            <div class="carousel-item active" data-bs-interval="4000">
                                <div class="advcarousel2 text-center">
                                    <img src="./assets/img/banner/banner6.jpg" />
                                    <div class="advcarousel-content">
                                        <h1>Mother's Day Collection</h1>
                                        <p>Shop our collection of vibrant and blooming house plants</p>
                                        <a href="./productpage.php?cid=discount" class="advcarousel-btn">Shop Now</a>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item" data-bs-interval="4000">
                                <div class="advcarousel1 text-center">
                                    <img src="./assets/img/banner/banner4.jpg" />
                                    <div class="advcarousel-content">
                                        <h1>Father's Day Collection</h1>
                                        <p>Shop our collection of vibrant and blooming house plants</p>
                                        <a href="./productpage.php?cid=discount" class="advcarousel-btn">Shop Now</a>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item" data-bs-interval="4000">
                                <div class="advcarousel3 text-center">
                                    <img src="./assets/img/banner/banner5.jpg" />
                                    <div class="advcarousel-content">
                                        <h1>Sepcial Day Collection</h1>
                                        <p>Shop our collection of vibrant and blooming house plants</p>
                                        <a href="./productpage.php?cid=discount" class="advcarousel-btn">Shop Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>

            </div>
    </section>
    <!-- End Carousel Section-->


    <!-- Start Blog Section-->
    <section class="py-5 text-center bg-white">

        <div class="container-fluid">

            <!-- start title -->
            <div class="text-center my-5">
                <h3 class="blogtitles">FROM OUR BLOG</h3>
            </div>
            <!-- end title -->

            <div class="row">

                <div class="col-lg-3 col-md-4 col-sm-6 px-2">
                    <div class="blog-card">
                        <div class="blog-card-img">
                            <a href="javascript:void(0);"><img src="./assets/img/blog/blog1.png" alt="blog1"></a>
                        </div>
                        <div class="blog-card-content text-uppercase">
                            <h5>12 Indoor Cactus Types</h5>
                            <a href="./blog.php">Read More</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="blog-card">
                        <div class="blog-card-img">
                            <a href="javascript:void(0);"><img src="./assets/img/blog/blog2.png" alt="blog1"></a>
                        </div>
                        <div class="blog-card-content text-uppercase">
                            <h5>Common Problems Faced by the Money Tree</h5>
                            <a href="./blog.php">Read More</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="blog-card">
                        <div class="blog-card-img">
                            <a href="javascript:void(0);"><img src="./assets/img/blog/blog3.png" alt="blog1"></a>
                        </div>
                        <div class="blog-card-content text-uppercase">
                            <h5>Growing and Caring for the Song of India Plant</h5>
                            <a href="./blog.php">Read More</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="blog-card">
                        <div class="blog-card-img">
                            <a href="javascript:void(0);"><img src="./assets/img/blog/blog4.jpg" alt="blog1"></a>
                        </div>
                        <div class="blog-card-content text-uppercase">
                            <h5>Get Creative with These Small Garden Ideas!</h5>
                            <a href="./blog.php">Read More</a>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </section>
    <!-- Start Blog Section-->

    <!-- Start Review Section -->
    <section class="py-4 reviews">
        <div class="container-fluid">

            <!-- start title -->
            <div class="text-center text-uppercase my-5">
                <div class="col">
                    <h3 class="titles">What Customers Say?</h3>
                </div>
            </div>
            <!-- end title -->

            <div class="row">
                <div class="col-md-8 mx-auto">
                    <!-- slide is used to fix all browser -->
                    <div id="customercarousels" class="carousel slide" data-bs-ride="carousel">

                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#customercarousels" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#customercarousels" data-bs-slide-to="1" aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#customercarousels" data-bs-slide-to="2" aria-label="Slide 3"></button>
                        </div>

                        <div class="carousel-inner">

                            <div class="carousel-item text-center active">
                                <img src="./assets/img/user/user1.jpg" class="rounded-circle" alt="user1" />
                                <blockquote class="reviewletters">
                                    <p>"I recently purchased several houseplants from Planti Plant Shop, and I couldn't be 
                                        happier with my experience. The selection is vast, with a wide variety of plants to
                                        choose from, including some rare finds. The quality of the plants is outstanding; they arrived healthy and well-packaged.""
                                    </p>
                                </blockquote>
                                <h5 class="text-uppercase fw-bold usernames mb-3">MS.July</h5>
                                <ul class="list-inline mb-5">
                                    <li class="list-inline-item"><i class="fas fa-star text-warning"></i></li>
                                    <li class="list-inline-item"><i class="fas fa-star text-warning"></i></li>
                                    <li class="list-inline-item"><i class="fas fa-star text-warning"></i></li>
                                    <li class="list-inline-item"><i class="fas fa-star text-warning"></i></li>
                                    <li class="list-inline-item"><i class="fas fa-star text-warning"></i></li>
                                </ul>
                            </div>

                            <div class="carousel-item text-center">
                                <img src="./assets/img/user/user2.jpg" class="rounded-circle" alt="user2" />
                                <blockquote class="reviewletters">
                                    <p>"Planti Plant Shop is my go-to place for all things green! Not only do they have an impressive
                                         assortment of plants, but their customer service is also top-notch. I had a few questions about
                                          caring for my new fiddle leaf fig, and the staff provided detailed and helpful advice."</p>
                                </blockquote>
                                <h5 class="text-uppercase fw-bold usernames mb-3">Mr.Anton</h5>
                                <ul class="list-inline mb-5">
                                    <li class="list-inline-item"><i class="fas fa-star text-warning"></i></li>
                                    <li class="list-inline-item"><i class="fas fa-star text-warning"></i></li>
                                    <li class="list-inline-item"><i class="fas fa-star text-warning"></i></li>
                                    <li class="list-inline-item"><i class="fas fa-star text-warning"></i></li>
                                    <li class="list-inline-item"><i class="fas fa-star text-warning"></i></li>
                                </ul>
                            </div>

                            <div class="carousel-item text-center">
                                <img src="./assets/img/user/user3.jpg" class="rounded-circle" alt="user3" />
                                <blockquote class="reviewletters">
                                    <p>"I ordered a few succulents and a monstera from Planti Plant Shop, and they arrived quickly and in perfect condition. 
                                        The plants were securely packaged, with no damage during transit. They look beautiful and have already become the
                                         centerpiece of my living room."</p>
                                </blockquote>
                                <h5 class="text-uppercase fw-bold usernames mb-3">Ms.Yoon</h5>
                                <ul class="list-inline mb-5">
                                    <li class="list-inline-item"><i class="fas fa-star text-warning"></i></li>
                                    <li class="list-inline-item"><i class="fas fa-star text-warning"></i></li>
                                    <li class="list-inline-item"><i class="fas fa-star text-warning"></i></li>
                                    <li class="list-inline-item"><i class="fas fa-star text-warning"></i></li>
                                    <li class="list-inline-item"><i class="fas fa-star text-warning"></i></li>
                                </ul>
                            </div>

                            <button class="carousel-control-prev text-dark" type="button" data-bs-target="#customercarousels" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#customercarousels" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- End Review Section-->


    <!-- Start Delivery Section -->
    <section class="py-4">

        <div class="container">

            <!-- start title -->
            <div class="text-center my-5">
                <div class="col">
                    <h3 class="delititles">"WE GUARANTEE HEALTHY DELIVERTY"</h3>
                </div>
            </div>
            <!-- end title -->

            <div class="row deliverys">

                <div class="col-md-2 col-sm-4">
                    <img src="./assets/img/delivery/deli1.png" alt="deli1" />
                </div>

                <div class="col-md-2 col-sm-4">
                    <img src="./assets/img/delivery/deli2.png" alt="deli2" />
                </div>

                <div class="col-md-2 col-sm-3">
                    <img src="./assets/img/delivery/deli3.png" alt="deli3" />
                </div>

                <div class="col-md-2 col-sm-4">
                    <img src="./assets/img/delivery/deli4.png" alt="deli4" />
                </div>

                <div class="col-md-2 col-sm-4">
                    <img src="./assets/img/delivery/deli5.png" alt="deli5" />
                </div>

                <div class="col-md-2 col-sm-4">
                    <img src="./assets/img/delivery/deli7.png" alt="deli6" />
                </div>

            </div>

        </div>

    </section>
    <!-- End Delivery Section -->

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
                        <?php if (!isset($_SESSION['email'])) { ?>
                            <li><a href="javascript:volid(0);" data-bs-toggle="modal" data-bs-target="#signin-modal" class="footerlinks">Sign In</a></li>
                        <?php } else { ?>
                            <li><a href="./useraccount.php" class="footerlinks">Account</a></li>
                        <?php } ?>
                        <?php if (isset($_SESSION['email'])) { ?>
                            <li><a href="./cart.php" class="footerlinks">View Cart</a></li>
                        <?php } else { ?>
                            <li><a href="javascript:volid(0);" class="footerlinks">View Cart</a></li>
                        <?php } ?>
                        <li><a href="./wishlist.php" class="footerlinks">My Wishlist</a></li>
                        <?php if (isset($_SESSION['email'])) { ?>
                            <li><a href="./orderrecord.php" class="footerlinks">Track My Order</a></li>
                        <?php } else { ?>
                            <li><a href="javascript:volid(0);" class="footerlinks">Track My Order</a></li>
                        <?php } ?>
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

    <!-- Start Alert Message Section -->
    <div class="modal fade" id="onload" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-column justify-content-center align-items-center">
                        <img src="./assets/img/gif/success.gif" width="150px" />
                        <!-- register success message -->
                        <?php
                        if (isset($_SESSION['customersignup_success'])) {
                        ?>
                            <h5 class="mt-4"><?php echo $_SESSION['customersignup_success'] ?></h5>
                        <?php
                        } ?>
                        <!-- login success message -->
                        <?php
                        if (isset($_SESSION['customerlogin_success'])) {
                        ?>
                            <h5 class="mt-4"><?php echo $_SESSION['customerlogin_success'] ?></h5>
                        <?php
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Alert Message Section -->

    <!-- Start Message Modal -->
    <?php
    $alertmessage = null;
    $gifpath = null;


    if (isset($_SESSION['payment_success'])) {
        $alertmessage = $_SESSION['payment_success'];
        $gifpath = "./assets/img/gif/success.gif";
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
    <!-- Start Message Modal -->


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
    if (isset($_SESSION['customersignup_success']) || isset($_SESSION['customerlogin_success'])) {
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
        unset($_SESSION['customersignup_success']);
        unset($_SESSION['customerlogin_success']);
    } ?>


    <?php
    if (isset($_SESSION['payment_success'])) {
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
        unset($_SESSION['payment_success']);
    } ?>

</body>

</html>