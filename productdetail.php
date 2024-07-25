<?php

require_once "dbconnect.php";
$reviews = null;
if (!isset($_SESSION)) {
    session_start(); // to create succes if not exist
}

// if (isset($_SESSION['email'])) {
//     echo $_SESSION['email'];
// }


if (isset($_GET['pid'])) {
    $_SESSION['pid'] = $_GET['pid'];
}

if (!isset($_SESSION['pid'])) {
    header("Location:index.php");
}


if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $customer = userdata($email);
    $productid = $_SESSION['pid'];
    // echo $productid;
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['reviewsubmit'])) {
        $customerid = $customer['cid'];
        $name = htmlspecialchars($_POST['name']);
        $title = htmlspecialchars($_POST['title']);
        $review = htmlspecialchars($_POST['review']);
        $rating = $_POST['rating'];
        addreview($productid, $name, $title, $review, $rating, $customerid);
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

function addreview($productid, $name, $title, $review, $rating, $cid)
{
    try {


        $date = new DateTimeImmutable();
        $datetime = $date->format('Y-m-d');
        // echo $datetime;
        $conn = connect();

        //Get Total rating count From product review
        $sql = "SELECT COUNT(*) AS rate_count FROM productreview WHERE productid=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$productid]);
        $counts = $stmt->fetch();
        $count = $counts['rate_count'] + 1;
        // $_SESSION['count'] = $count;

        //Get Total rating From
        $sql = "SELECT SUM(rating) AS total_rating FROM productreview WHERE productid=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$productid]);
        $totalratings = $stmt->fetch();
        if ($stmt->rowCount() > 0) {
            $recentrating = $totalratings['total_rating'];
            $newrating = $rating;
            settype($recentrating, "integer");
            settype($newrating, "integer");
            $totalrating = $recentrating  + $newrating;
            // $_SESSION['total'] = $totalrating;
            // $_SESSION['array'] = $totalratings;
        } else {
            $totalrating = $rating;
            settype($totalrating, "integer");
            // $_SESSION['total'] = $totalrating;
        }

        $actualrating = $totalrating / $count;
        // $_SESSION['actual'] = $actualrating;

        //Update rating in product table
        $sql = "UPDATE product SET rating=? WHERE pid=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$actualrating, $productid]);


        $stmt = $conn->prepare("INSERT INTO productreview (productid,customerid,prcname,prtitle,prcontent,rating,date) VALUES (:pid, :cid, :cname, :rtitle, :content, :rating, :date)");
        $stmt->bindValue(':pid', $productid);
        $stmt->bindValue(':cid', $cid);
        $stmt->bindValue(':cname', $name);
        $stmt->bindValue(':rtitle', $title);
        $stmt->bindValue(':content', $review);
        $stmt->bindValue(':rating', $rating);
        $stmt->bindValue(':date', $datetime);
        $stmt->execute();
        $_SESSION["review_success"] = "Thank You For Your Review";
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function getproductreviews($pid)
{
    try {
        $conn = connect();
        $sql = "select * from productreview where productid = ?";
        $stmt = $conn->prepare($sql);
        $status = $stmt->execute([$pid]);
        $returnvalue = $stmt->fetchAll();
        $conn = null;
        return $returnvalue;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function getoneproductreview($prid)
{
    try {
        $conn = connect();
        $sql = "select * from productreview where prid = ?";
        $stmt = $conn->prepare($sql);
        $status = $stmt->execute([$prid]);
        $returnvalue = $stmt->fetchAll();
        $conn = null;
        return $returnvalue;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function addlike($reviewid, $likecount)
{
    try {
        $conn = connect();
        $sql = "UPDATE productreview SET `like` = :likecount WHERE prid = :reviewid";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':likecount', $likecount, PDO::PARAM_INT);
        $stmt->bindParam(':reviewid', $reviewid, PDO::PARAM_INT);
        $stmt->execute();
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function addunlike($reviewid, $unlikecount)
{
    try {
        $conn = connect();
        $sql = "UPDATE productreview SET `unlike` = :unlikecount WHERE prid = :reviewid";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':unlikecount', $unlikecount, PDO::PARAM_INT);
        $stmt->bindParam(':reviewid', $reviewid, PDO::PARAM_INT);
        $stmt->execute();
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function getproduct($pid)
{
    try {
        $conn = connect();
        $sql = "SELECT * FROM product WHERE pid=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$pid]);
        $product = $stmt->fetch();
        return $product;
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function getcategoryname($cid)
{
    try {
        $conn = connect();
        $sql = "SELECT ctgname FROM category WHERE ctgid = $cid";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $ctgname = $stmt->fetch();
        return $ctgname;
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function getrandomproduct($cid, $pid)
{
    try {
        $conn = connect();
        $sql = "SELECT * FROM product WHERE categoryid=? AND pid!=? ORDER BY RAND() LIMIT 4";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$cid, $pid]);
        $product = $stmt->fetchAll();
        return $product;
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

if (isset($_GET['reaction']) && $_GET['prid'] != null) {
    $reviewid = $_GET['prid'];
    // echo $reviewid;
    $onereview = getoneproductreview($reviewid);
    // echo gettype($onereview);
    // echo "<pre>".print_r($onereview,true)."</pre>";
    // echo $onereview[0]['like'];
    $reaction = $_GET['reaction'];
    if ($reaction == "like") {
        $likecount = $onereview[0]['like'];
        $likecount = $likecount + 1;
        addlike($reviewid, $likecount);
    } elseif ($reaction == "unlike") {
        $unlikecount = $onereview[0]['unlike'];
        $unlikecount = $unlikecount + 1;
        addunlike($reviewid, $unlikecount);
    }
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


$pid = $_SESSION['pid'];
$reviews = getproductreviews($pid);
$product = getproduct($pid);
$ctgname = getcategoryname($product['categoryid']);
$recommandproducts = getrandomproduct($product['categoryid'], $pid);

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
    <!-- flickety css1 js1 -->
    <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
    <!-- custom css -->
    <link href="./css/style.css" rel="stylesheet" type="text/css">
</head>

<body>

    <!-- Start Back to Top Us Section -->
    <div class="fixed-bottom">
        <a href="./productdetail.php" class="btn-backtotops"><i class="fas fa-arrow-up"></i></a>
    </div>
    <!-- If index.html is used, the webpage can be relaoded -->
    <!-- End Back to Top Us Section -->


    <!-- Start Header Section -->
    <header>
        <!-- Start header information section-->
        <div class="text-center py-1 informations">
            <small>Free Shipping above $200 | All Myanmar Deliver</small>
            <?php
            // echo $_SESSION['count'], $_SESSION['total'], $_SESSION['actual'];
            // var_dump($_SESSION['array']);
            ?>
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
                        <input type="hidden" name="cid" value="search" />
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

    <!-- Start Product Detail Section -->
    <main class="border-top">
        <!-- start .breadcrumb-nav -->
        <nav aria-label="breadcrumb" class="breadcrumb-nav mt-4 mb-5 ms-3 py-1">
            <div class="container-fluid">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item text-muted">Home</li>
                    <li class="breadcrumb-item text-muted">Porducts</li>
                    <li class="breadcrumb-item text-success"><?php echo ucfirst($ctgname['ctgname']) ?></li>
                </ol>
            </div><!-- End .container-fluid -->
        </nav><!-- End .breadcrumb-nav -->
        <!-- end .breadcrumb-nav -->

        <!-- start page content -->
        <div class="page-content">
            <div class="container">
                <!-- start product detail top -->
                <div class="mb-5 product-details-top">
                    <div class="row">
                        <?php
                        try {
                            $conn = connect();
                            $sql = "SELECT * FROM productimage WHERE productid = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute([$pid]);
                            $productimages = $stmt->fetchAll();
                            $conn = null;
                        } catch (PDOException $e) {
                            echo $e->getMessage();
                        }
                        ?>

                        <!-- start product images -->
                        <div class="col-lg-5 mb-5">
                            <div class="main-carousel" data-flickity='{ "wrapAround": true, "autoPlay": false, "pageDots": false, "prevNextButtons": true, "contain": true, "fullscreen": true,"imagesLoaded": true, "adaptiveHeight": true}'>
                                <?php foreach ($productimages as $productimage) { ?>
                                    <div class="carousel-cell border"><img src="<?php echo $productimage['url'] ?>" class="img-fluid main-image" style="object-fit: cover;" /></div>
                                <?php } ?>
                            </div>
                            <div class="nav-carousel mt-3" data-flickity='{"asNavFor": ".main-carousel", "pageDots": false, "prevNextButtons": false, "draggable": true, "freeScroll": true, "cellAlign": "left", "rightToLeft":false, "contain":true}'>
                                <?php foreach ($productimages as $productimage) { ?>
                                    <div class="carousel-cell"><img src="<?php echo $productimage['url'] ?>" class="img-thumbnail" /></div>
                                <?php } ?>
                            </div>
                        </div>
                        <!-- end product images  -->

                        <!-- start product info -->
                        <div class="col-lg-7">
                            <div class="text-center mt-1 product-detials">
                                <h2 class="productnames"><?php echo $product['pname'] ?></h2>
                                <p class="small text-muted text-uppercase categories"><?php echo $ctgname['ctgname'] ?></p>
                                <div class="d-flex justify-content-center align-items-centerr ratingscontainers py-2">
                                    <div class="ratings me-3">
                                        <?php
                                        $rating = round($product['rating']);
                                        if ($rating != 0) {
                                        ?>
                                            <?php if ($rating == 1) { ?>
                                                <div class="ratings-vals">
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-muted"></i>
                                                    <i class="fas fa-star text-muted"></i>
                                                    <i class="fas fa-star text-muted"></i>
                                                    <i class="fas fa-star text-muted"></i>
                                                </div>
                                            <?php } elseif ($rating == 2) { ?>
                                                <div class="ratings-vals">
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-muted"></i>
                                                    <i class="fas fa-star text-muted"></i>
                                                    <i class="fas fa-star text-muted"></i>
                                                </div>
                                            <?php } elseif ($rating == 3) { ?>
                                                <div class="ratings-vals">
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-muted"></i>
                                                    <i class="fas fa-star text-muted"></i>
                                                </div>
                                            <?php } elseif ($rating == 4) { ?>
                                                <div class="ratings-vals">
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-muted"></i>
                                                </div>
                                            <?php } elseif ($rating == 5) { ?>
                                                <div class="ratings-vals">
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-warning"></i>
                                                </div>
                                            <?php } ?>

                                        <?php } else { ?>
                                            <div class="ratings-vals">
                                                <i class="fas fa-star text-muted"></i>
                                                <i class="fas fa-star text-muted"></i>
                                                <i class="fas fa-star text-muted"></i>
                                                <i class="fas fa-star text-muted"></i>
                                                <i class="fas fa-star text-muted"></i>
                                            </div>
                                        <?php } ?>
                                        <!-- End .ratings-val -->
                                    </div><!-- End .ratings -->
                                    <span class="ratings-texts">( <?php echo sizeof($reviews) ?> Review )</span>
                                </div><!-- End .rating-container -->

                                <?php if ($product['discount'] == 1) { ?>
                                    <?php
                                    try {
                                        $conn = connect();
                                        $sql = "SELECT * FROM discount WHERE productid = $pid";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute();
                                        $discount = $stmt->fetch();
                                        $conn = null;
                                    } catch (PDOException $e) {
                                        echo $e->getMessage();
                                    }
                                    ?>
                                    <div class="product-price h4 mt-3">
                                        <del class="text-muted">$<?php echo $product['price'] ?></del>
                                        <span class="ps-1">$<?php echo $discount['discountprice'] ?></span>
                                        <span class="text-danger">(%<?php echo $discount['discountpercent'] ?> OFF)</span>
                                    </div> <!-- End product price -->
                                <?php } else { ?>
                                    <div class="product-price h4 mt-3">
                                        $<span><?php echo $product['price'] ?></span>
                                    </div> <!-- End product price -->
                                <?php } ?>

                                <div class="text-muted py-4 px-5 productcontents">
                                    The Delivered Product May Differ in Size, Color, Number of Flowers, Branches, and Will Come in a Plastic Pot, not The One Shown in The Picture.
                                </div> <!-- End product content -->

                                <div class="d-flex justify-content-center align-items-center productsizes">
                                    <span class="text-uppercaes h6 pe-4">Size: </span>
                                    <?php $sizes = explode('-', $product['size']) ?>
                                    <p class="h6 border border-success ms-5 p-2"><?php echo $sizes['0'] ?> - <?php echo $sizes['1'] ?></p>
                                </div>

                                <div class="product-detail-action">
                                    <form action="./cart.php" method="post">
                                        <input type="hidden" name="pid" value="<?php echo $product['pid'] ?>" />
                                        <div class="form-group d-flex justify-content-center align-items-center mt-4">
                                            <label for="qty" class="text-uppercase h6 pt-2 pe-4">Quantity: </label>
                                            <input type="number" name="quantity" id="qty" class="ms-4 me-5 formcontrols" value="1" min="0" max="50" step="1" data-decimals="0" required />
                                        </div>
                                        <div class="mt-5 mb-5 pt-4 text-center">
                                            <?php if (isset($_SESSION['email'])) { ?>
                                                <button type="submit" name="addtocart" class="btn border border-success border-2 rounded-0 text-uppercaes mb-3 addtocard-btns">
                                                    <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                                                </button>
                                            <?php } else { ?>
                                                <button type="button" name="addtocart" class="btn border border-success border-2 rounded-0 text-uppercaes mb-3 addtocard-btns">
                                                    <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                                                </button>
                                            <?php } ?>
                                            <a href="./wishlist.php?pid=<?php echo $product['pid'] ?>" class="text-decoration-none addtowishlist-text btn text-uppercase border-success border-2 rounded-0 mb-3 addtowishlist-btns">
                                                <i class="fas fa-heart me-2"></i> Add to Wishlist
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- end product info  -->
                    </div>
                </div>
                <!-- end product detail top -->

                <!-- start product detail description -->
                <div class="product-details-tab">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link text-dark active" id="nav-description-tab" data-bs-toggle="tab" data-bs-target="#nav-description" type="button" role="tab" aria-controls="nav-description" aria-selected="true">Description</button>
                            <button class="nav-link text-dark" id="nav-shippingreturn-tab" data-bs-toggle="tab" data-bs-target="#nav-shippingreturn" type="button" role="tab" aria-controls="nav-shippingreturn" aria-selected="false">Shipping & Returns</button>
                            <button class="nav-link text-dark" id="nav-review-tab" data-bs-toggle="tab" data-bs-target="#nav-review" type="button" role="tab" aria-controls="nav-review" aria-selected="false">Reviews (<?php echo sizeof($reviews) ?>)</button>
                            <?php if (isset($_SESSION['email'])) { ?>
                                <button class="nav-link text-dark" id="nav-reviewform-tab" data-bs-toggle="tab" data-bs-target="#nav-reviewform" type="button" role="tab" aria-controls="nav-reviewform" aria-selected="false">Review Form</button>
                            <?php } ?>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active px-5 py-5" id="nav-description" role="tabpanel" aria-labelledby="nav-description-tab" tabindex="0">
                            <h5>Product Bio</h5>
                            <p class="lh-lg small mt-4" style="text-align: justify;">
                                <?php echo $product['description'] ?>
                            </p>
                            <h5 class="mt-5">Additional Care Practices</h5>
                            <p class="lh-lg small mt-4" style="text-align: justify;">
                                <?php echo $product['additionalinfo'] ?>
                            </p>
                        </div>
                        <div class="tab-pane fade px-5 py-5" id="nav-shippingreturn" role="tabpanel" aria-labelledby="nav-shippingreturn-tab" tabindex="0">
                            <h5>Delivery & Returns</h5>
                            <p class="small mt-4" style="text-align: justify;">
                                We deliver to over 100 citie around the Country. For full details of the delivery options we offer, please view our
                                <a href="./deliveryinformation.php" class="h6 text-success">Delivery information.</a>
                            </p>
                            <p class="small" style="text-align: justify;">
                                We hope you’ll love every purchase, but if you ever need to return an item you can do so within a month of receipt. For full details of how to make a return, please view our
                                <a href="./returninfo.php" class="h6 text-success">Returns Information</a>
                            </p>
                        </div>
                        <div class="tab-pane fade px-5 py-5" id="nav-review" role="tabpanel" aria-labelledby="nav-review-tab" tabindex="0">
                            <h5 class="mb-4">Reviews (<?php echo sizeof($reviews) ?>)</h5>

                            <div class="mb-3 product-review-container">

                                <?php foreach ($reviews as $values) {
                                    $prid = $values['prid'];
                                ?>

                                    <div class="border-bottom mb-4 pb-4 product-reviews">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-3 col-sm-4">
                                                <h6><?php echo $values['prcname'] ?></h6>
                                                <div class="mt-2 product-review-ratings">
                                                    <?php $rate = $values['rating'] ?>
                                                    <?php for ($i = 0; $i < $rate; $i++) { ?>
                                                        <i class="fas fa-star text-warning"></i>
                                                    <?php } ?>
                                                    <?php for ($i = 0; $i < 5 - $rate; $i++) { ?>
                                                        <i class="fas fa-star text-secondary"></i>
                                                    <?php } ?>
                                                </div>
                                                <p class="text-muted product-review-dates"><?php echo $values['date'] ?></p>
                                            </div>
                                            <div class="col-lg-10 col-md-9 col-sm-8">
                                                <h6><?php echo $values['prtitle'] ?></h6>
                                                <p class="small" style="text-align: justify;"><?php echo $values['prcontent'] ?></p>
                                                <div class="d-flex review-reactions">
                                                    <a href="productdetail.php?reaction=like&prid=<?php echo $prid ?>" class="nav-link text-muted me-4"><i class="far fa-thumbs-up me-1"></i> Helpful (<?php echo $values['like'] ?>)</a>
                                                    <a href="productdetail.php?reaction=unlike&prid=<?php echo $prid ?>" class="nav-link text-muted"><i class="far fa-thumbs-down me-1 mb-2"></i> Unhelpful (<?php echo $values['unlike'] ?>)</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                                <?php if ($reviews == null) { ?>
                                    <div class="row">
                                        <div class="col-12 h4 text-center py-5">
                                            Review is not found
                                        </div>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                        <?php if (isset($_SESSION['email'])) { ?>
                            <div class="tab-pane fade px-5 py-5" id="nav-reviewform" role="tabpanel" aria-labelledby="nav-reviewform-tab" tabindex="0">
                                <h5>Review Form</h5>
                                <div class="row justify-content-center ps-3">
                                    <div class="col-lg-8">
                                        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                                            <table class="mt-4 mb-3">
                                                <tr>
                                                    <td class="h5 pe-4 py-3"><label for="name"><i class="fas fa-user"></i> Name:</label></td>
                                                    <td class="py-2"><input type="text" name="name" id="name" class="form-control" required /></td>
                                                </tr>
                                                <tr>
                                                    <td class="h5 pe-4 py-3"><label for="title"><i class="far fa-comment"></i> Title:</label></td>
                                                    <td class="py-2"><input type="text" name="title" id="title" class="form-control" required /></td>
                                                </tr>
                                                <tr>
                                                    <td class="h5 pe-4 py-3" style="vertical-align:top;"><label for="review"><i class="fas fa-comments"></i> Review:</label></td>
                                                    <td class="py-2"><textarea name="review" id="review" class="form-control" rows="5" cols="80" required></textarea></td>
                                                </tr>
                                                <tr>
                                                    <td class="h5 pe-4 py-3" style="vertical-align:top;"><label for="rating"><i class="fas fa-award"></i> Rating:</label></td>
                                                    <td class="py-2">
                                                        <div>
                                                            <input type="radio" name="rating" id="rating1" class="form-check-input me-2" value="5" required />
                                                            <label for="rating1" class="form-check-label">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                            </label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" name="rating" id="rating2" class="form-check-input me-2" value="4" required />
                                                            <label for="rating2" class="form-check-label">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                            </label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" name="rating" id="rating3" class="form-check-input me-2" value="3" required />
                                                            <label for="rating3" class="form-check-label">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                            </label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" name="rating" id="rating4" class="form-check-input me-2" value="2" required />
                                                            <label for="rating4" class="form-check-label">
                                                                <i class="fas fa-star text-warning"></i>
                                                                <i class="fas fa-star text-warning"></i>
                                                            </label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" name="rating" id="rating5" class="form-check-input me-2" value="2" required />
                                                            <label for="rating5" class="form-check-label">
                                                                <i class="fas fa-star text-warning"></i>
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="h6 pe-4 py-4"><button type="submit" class="btn btn-success px-4" name="reviewsubmit">Submit</button></td>
                                                    <td class="small py-2"><button type="reset" class="btn btn-danger">Cancel</button></td>
                                                </tr>
                                            </table>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <!-- end product detail description -->

                <!-- start product recommendation section -->
                <div class="my-5 py-5 product-recommendations">
                    <h3 class="text-center text-uppercase">You May Also Like</h3>
                    <div class="row mt-5 pb-5">
                        <?php foreach ($recommandproducts as $recommand) { ?>

                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="shadow product text-center">
                                    <figure class="product-media">
                                        <?php
                                        try {
                                            $pid = $recommand['pid'];
                                            $conn = connect();

                                            $sql = "SELECT * FROM productimage WHERE productid = ?";
                                            $stmt = $conn->prepare($sql);
                                            $stmt->execute([$pid]);
                                            $productimages = $stmt->fetchAll();

                                            $sql = "SELECT * FROM discount WHERE productid=?";
                                            $stmt = $conn->prepare($sql);
                                            $stmt->execute([$pid]);
                                            $discount = $stmt->fetch();

                                            $conn = null;
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
                                            <?php if ($recommand['newarrived'] == 1) { ?>
                                                <div class="bg-danger p-1 discounts">
                                                    <span class="text-white small"><?php echo $discount['discountpercent'] ?>% OFF</span>
                                                </div>
                                                <div class="bg-warning news">
                                                    <span class="text-white">New</span>
                                                </div>
                                            <?php } else { ?>
                                                <div class="bg-danger p-1 discounts">
                                                    <span class="text-white small"><?php echo $discount['discountpercent'] ?>% OFF</span>
                                                </div>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <?php if ($recommand['newarrived'] == 1) { ?>
                                                <div class="bg-warning newarriveds">
                                                    <span class="text-white">New</span>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                        <div class="product-action-vertical">
                                            <a href="javascript:" class="btn-wishlist"><i class="far fa-heart"></i></a>
                                            <a href="javascript:void(0);" class="btn-quickview" title="Quick view"><i class="fas fa-binoculars"></i></a>
                                        </div><!-- End .product-action-vertical -->

                                        <?php if (isset($_SESSION['email'])) { ?>
                                            <div class="product-action">
                                                <a href="./cart.php?php echo $pid ?>" class="btn-product btn-cart"><span>add to cart</span></a>
                                            </div><!-- End .product-action -->
                                        <?php  } ?>
                                    </figure><!-- End .product-media -->

                                    <div class="product-body px-3 pb-4">
                                        <h3 class="product-title"><a href="productdetail.php?pid=<?php echo $recommand['pid'] ?>"><?php echo $recommand['pname'] ?></a></h3>
                                        <!-- End .product-title -->
                                        <?php if ($discount != null) { ?>
                                            <div class="product-price">
                                                <del class="text-muted">$<?php echo $recommand['price'] ?></del>
                                                <spa class="ps-1">$<?php echo $discount['discountprice'] ?></spa>
                                            </div><!-- End .product-price -->
                                        <?php } else { ?>
                                            <div class="product-price">
                                                $<?php echo $recommand['price'] ?>
                                            </div><!-- End .product-price -->
                                        <?php } ?>
                                        <div class="ratings-container">
                                            <div class="ratings m-0">
                                                <?php
                                                $rating = round($recommand['rating']);
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
                                                    <?php } else if ($rating == 3) { ?>
                                                        <div class="ratings-val">
                                                            <i class="fas fa-star text-warning"></i>
                                                            <i class="fas fa-star text-warning"></i>
                                                            <i class="fas fa-star text-warning"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                        </div>
                                                    <?php } else if ($rating == 4) { ?>
                                                        <div class="ratings-val">
                                                            <i class="fas fa-star text-warning"></i>
                                                            <i class="fas fa-star text-warning"></i>
                                                            <i class="fas fa-star text-warning"></i>
                                                            <i class="fas fa-star text-warning"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                        </div>
                                                    <?php } else if ($rating == 5) { ?>
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
                    </div>
                </div>
                <!-- endt product recommendation section -->
            </div>
        </div>
        <!-- end page content -->
    </main>
    <!-- End Procut Detail Section -->

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

    <!-- Start Update Success Message Modal -->
    <?php
    $alertmessage = null;
    $gifpath = null;
    if (isset($_SESSION['review_success'])) {
        $alertmessage = $_SESSION['review_success'];
        $gifpath = "./assets/img/gif/thankyou.gif";
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
    <!-- flickety css1 js1 -->
    <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
    <!-- elfsight for viber chat -->
    <script src="https://static.elfsight.com/platform/platform.js" data-use-service-core defer></script>
    <!-- custom js -->
    <script src="./js/app.js" type="text/javascript"></script>

    <!-- start login modal -->
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

    <!-- end login modal -->

    <!--  start review success modal -->
    <?php
    if (isset($_SESSION['review_success'])) {
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
        unset($_SESSION['review_success']);
    } ?>

    <!--  end review success modal -->

</body>

</html>