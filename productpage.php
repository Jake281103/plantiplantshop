<?php

use function PHPSTORM_META\type;

require_once "dbconnect.php";
$conn = connect();
$productsql = null;
$total = null;
$size = null;
$price = null;
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


if(isset($_SESSION['email'])){
    $email = $_SESSION['email'];
    $userdatas = userdata($email);
    $cid = $userdatas['cid'];
    $getcartcounts = getcartcount($cid);
    $cartcount = $getcartcounts['count'];
}


// start pagination

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['cid'])) {
    $_SESSION['cid'] = $_GET['cid'];
    unset($_SESSION['filter']);
    unset($_SESSION['plantsize']);
    unset($_SESSION['price']);
} else if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['productfilter'])) {
    $_SESSION['cid'] = $_POST['category'];
    $_SESSION['filter'] = "filter";
    if (isset($_POST['plantsize'])) {
        $_SESSION['plantsize'] = $_POST['plantsize'];
    }else{
        unset($_SESSION['plantsize']);
    }
    if ($_POST['price'] != $_POST['midvalue']) {
        $_SESSION['price'] = $_POST['price'];
    }else{
        unset($_SESSION['price']);
    }
}elseif($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['searchbtn'])){
    $_SESSION['cid'] = $_POST['cid'];
    $_SESSION['searchstring'] = $_POST['searchstring'];
}

if(isset($_SESSION['plantsize'])){
    $size = $_SESSION['plantsize'];
}

if(isset($_SESSION['price'])){
    $price = $_SESSION['price'];
}

if (!isset($_SESSION['cid'])) {
    if (!isset($_SESSION['email'])) {
        header("Location:index.php");
    } else {
        header("Location:customerhomepage.php");
    }
}


if ($_SESSION['cid'] == "accessory") {
    $productsql = "SELECT p.pid, p.pname, p.price, p.discount, p.newarrived, p.size, p.rating FROM product p JOIN category c ON p.categoryid = c.ctgid WHERE c.description = 'accessory'";
    $total = "FROM product p JOIN category c ON p.categoryid = c.ctgid WHERE c.description = 'accessory'";
} elseif ($_SESSION['cid'] == "discount") {
    $productsql = "SELECT p.pid, p.pname, p.price, p.rating, p.discount, p.newarrived, p.size, d.discountpercent, d.discountprice FROM product p JOIN discount d ON p.pid = d.productid";
    $total = "FROM product p JOIN discount d ON p.pid = d.productid";
} elseif ($_SESSION['cid'] == "newarrived") {
    $productsql = "SELECT p.pid, p.pname, p.price, p.discount, p.newarrived, p.size, p.rating FROM product p JOIN newarrived n ON p.pid = n.productid";
    $total = "FROM product p JOIN newarrived n ON p.pid = n.productid";
} elseif ($_SESSION['cid'] == "all") {

    if( isset($_SESSION['plantsize']) &&  !isset($_SESSION['price'])){
        $productsql = "SELECT p.pid, p.pname, p.price, p.discount, p.newarrived, p.size, p.rating FROM product p WHERE p.size=?";
        $total = "FROM product WHERE size=?";
    }else if( !isset($_SESSION['plantsize']) &&  isset($_SESSION['price'])){
        $productsql = "SELECT p.pid, p.pname, p.price, p.discount, p.newarrived, p.size, p.rating FROM product p WHERE p.price > 0 AND p.price<=$price";
        $total = "FROM product p WHERE p.price > 0 AND p.price <= $price";
    }else if( isset($_SESSION['plantsize']) &&  isset($_SESSION['price'])){
        $productsql = "SELECT p.pid, p.pname, p.price, p.discount, p.newarrived, p.size, p.rating FROM product p WHERE p.price > 0 AND p.price<=$price AND p.size=?";
        $total = "FROM product p WHERE p.price > 0 AND p.price <= $price AND p.size=?";
    }else{
        $productsql = "SELECT p.pid, p.pname, p.price, p.discount, p.newarrived, p.size, p.rating FROM product p";
        $total = "FROM product";
    }

} elseif ($_SESSION['cid'] == "search") {
    $keyword = trim($_SESSION['searchstring']);
    $productsql = "SELECT p.pid, p.pname, p.price, p.discount, p.newarrived, p.size, p.rating FROM product p WHERE p.pname LIKE '%$keyword%'";
    $total = "FROM product p WHERE p.pname LIKE '%$keyword%'";
} else {
    $cid = $_SESSION['cid'];
    if( isset($_SESSION['plantsize']) &&  !isset($_SESSION['price'])){
        $productsql = "SELECT p.pid, p.pname, p.price, p.discount, p.newarrived, p.size, p.rating FROM product p WHERE p.categoryid = {$cid} AND p.size=?";
        $total = "FROM product p WHERE p.categoryid = {$cid} AND p.size=?";
    }else if( !isset($_SESSION['plantsize']) &&  isset($_SESSION['price'])){
        $productsql = "SELECT p.pid, p.pname, p.price, p.discount, p.newarrived, p.size, p.rating FROM product p WHERE p.categoryid = {$cid} AND p.price > 0 AND p.price<=$price";
        $total = "FROM product p WHERE p.categoryid = {$cid} AND p.price > 0 AND p.price<=$price";
    }else if( isset($_SESSION['plantsize']) &&  isset($_SESSION['price'])){
        $productsql = "SELECT p.pid, p.pname, p.price, p.discount, p.newarrived, p.size, p.rating FROM product p WHERE p.categoryid = {$cid} AND p.price > 0 AND p.price<=$price AND p.size=?";
        $total = "FROM product p WHERE p.categoryid = {$cid} AND p.price > 0 AND p.price<=$price AND p.size=?";
    }else{
        $productsql = "SELECT p.pid, p.pname, p.price, p.discount, p.newarrived, p.size, p.rating FROM product p WHERE p.categoryid = {$cid}";
        $total = "FROM product p WHERE p.categoryid = {$cid}";
    }
}


// Define how many products to display per page
$productsPerPage = 12; // Adjust this value as needed

// Get the current page number from the URL parameter, default to page 1
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Calculate the offset for the SQL query
$offset = ($page - 1) * $productsPerPage;

// Query to fetch products from the database for the current page
// $sql = "SELECT * FROM product LIMIT $offset, $productsPerPage";
if($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['sort'])){
    if($_GET['sort'] == "price"){
        $_SESSION['sort'] = "ORDER BY p.price DESC";
    }else if($_GET['sort'] == "rating"){
        $_SESSION['sort'] = "ORDER BY p.rating DESC";
    }   
}

if(isset($_SESSION['sort'])){
    $sort =  $_SESSION['sort'];
    $sql = "{$productsql} {$sort} LIMIT $offset, $productsPerPage";
}else{
    $sql = "{$productsql} LIMIT $offset, $productsPerPage";
}
$stmt = $conn->prepare($sql);
if(isset($_SESSION['plantsize']) &&  !isset($_SESSION['price'])){
    $stmt->execute([$size]);
}else if(isset($_SESSION['plantsize']) &&  isset($_SESSION['price'])){
    $stmt->execute([$size]);
}else{
    $stmt->execute();
}

// Calculate the total number of products with the search term
// $totalProductsQuery = "SELECT COUNT(*) AS total FROM product";
$totalProductsQuery = "SELECT COUNT(*) AS total {$total}";
$totalProductsStmt = $conn->prepare($totalProductsQuery);
if(isset($_SESSION['plantsize'])){
    $totalProductsStmt->execute([$size]);
}else if(isset($_SESSION['plantsize']) &&  isset($_SESSION['price'])){
    $totalProductsStmt->execute([$size]);
}else{
    $totalProductsStmt->execute();
}
$totalProductsStmt->execute();
$totalProductsRow = $totalProductsStmt->fetch(PDO::FETCH_ASSOC);
$totalProducts = $totalProductsRow['total'];

// Calculate the total number of pages
$totalPages = ceil($totalProducts / $productsPerPage);


// end pagination

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
        <a href="./productpage.php" class="btn-backtotops"><i class="fas fa-arrow-up"></i></a>
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

    <!-- Start Product Section -->
    <main class="prodcut-section">

        <!-- start product page header -->
        <div class="text-center" style="background-image: url('./assets/img/banner/banner2.jpg');background-size:cover;">
            <div class="container-fluid py-5 product-page-headers">
                <h1 class="text-uppercase">
                    <?php if ($_SESSION['cid'] == "accessory") { ?>
                        Accessory Products
                    <?php } elseif ($_SESSION['cid'] == "discount") { ?>
                        Discout Products
                    <?php } elseif ($_SESSION['cid'] == "newarrived") { ?>
                        New Arrived Products
                    <?php } elseif (isset($_SESSION['filter'])) { ?>
                        Filter Products
                    <?php } else { ?>
                        <?php foreach ($catgories as $category) {
                            if ($_SESSION['cid'] == $category['ctgid']) {
                                echo $category['ctgname'];
                            }
                        }
                        ?>
                    <?php } ?>
                </h1>
                <p class="h5">Planti Plant Shop</p>
            </div>
        </div>
        <!-- end product page header -->

        <!-- start .breadcrumb-nav -->
        <nav aria-label="breadcrumb" class="breadcrumb-nav mt-3 border-bottom">
            <div class="container-fluid">
                <ol class="breadcrumb">
                    <?php if (isset($_SESSION['email'])) { ?>
                        <li class="breadcrumb-item text-muted"><a href="customerhomepage.php" class="nav-link">Home</a></li>
                    <?php } else { ?>
                        <li class="breadcrumb-item text-muted"><a href="index.php" class="nav-link">Home</a></li>
                    <?php } ?>
                    <li class="breadcrumb-item" aria-current="page">
                        <?php if ($_SESSION['cid'] == "accessory") { ?>
                            Accessory Products
                        <?php } elseif ($_SESSION['cid'] == "discount") { ?>
                            Discout Products
                        <?php } elseif ($_SESSION['cid'] == "newarrived") { ?>
                            New Arrived Products
                        <?php } elseif (isset($_SESSION['filter'])) { ?>
                            Filter Products
                        <?php } else { ?>
                            <?php foreach ($catgories as $category) {
                                if ($_SESSION['cid'] == $category['ctgid']) {
                                    echo ucfirst($category['ctgname']);
                                }
                            }
                            ?>
                        <?php } ?>
                    </li>
                </ol>
            </div><!-- End .container-fluid -->
        </nav><!-- End .breadcrumb-nav -->
        <!-- end .breadcrumb-nav -->

        <!-- start product page content -->
        <div class="page-content">
            <div class="container-fluid">

                <!-- start tool box -->
                <div class="toolbox d-flex justify-content-between align-items-center py-3">
                    <div class="toolbox-left">
                        <a href="javascript:void(0);" class="text-decoration-none text-dark"><i class="fas fa-sliders-h small me-1"></i> Filters</a>
                    </div> <!-- End .toolbox-left -->

                    <div class="toolbox-center">
                        <div class="toolbox-info text-muted">
                            Showing <span class="text-dark"><?php echo (($page - 1) * $productsPerPage + 1); ?> - <?php echo min($page * $productsPerPage, $totalProducts); ?> of <?php echo $totalProducts; ?></span> Products
                        </div><!-- End .toolbox-info -->
                    </div><!-- End .toolbox-center -->

                    <div class="toolbox-right">
                        <div class="d-flex align-items-center">
                            <span class="">Sort by:</span>
                            <div class="dropdown ms-2">
                                <a class="text-muted btn btn-white border border-success rounded-0 dropdown-toggle p-0 px-2 py-1" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                    Sorting
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="productpage.php?sort=price">Price</a></li>
                                    <li><a class="dropdown-item" href="productpage.php?sort=rating">Most Rated</a></li>
                                </ul>
                            </div>
                        </div><!-- End .toolbox-sort -->
                    </div><!-- End .toolbox-center -->
                </div>
                <!-- end tool box -->

                <!-- start product area -->
                <div class="container py-2 mb-5">
                    <div class="row">
                        <?php
                        if ($stmt->rowCount() > 0) {
                            $rows = $stmt->fetchAll();
                            // echo "<pre>".print_r($row,true)."</pre>"; 
                            foreach ($rows as $row) {
                        ?>
                                <div class="col-sm-6 col-md-4 col-lg-3 mb-4 colums">
                                    <div class="product products text-center border border-muted shadow">
                                        <figure class="product-media medias">
                                            <?php
                                            try {
                                                $pid = $row['pid'];
                                                $conn = connect();
                                                $sql = "SELECT * from productimage WHERE productid = ?";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->execute([$pid]);
                                                $productimages = $stmt->fetchAll();
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

                                            <?php $discount = null ?>

                                            <?php if ($_SESSION['cid'] == "discount") { ?>
                                                <?php if ($row['newarrived'] == 1) { ?>
                                                    <div class="bg-danger p-1 discounts">
                                                        <span class="text-white small"><?php echo $row['discountpercent'] ?>% OFF</span>
                                                    </div>
                                                    <div class="bg-warning news">
                                                        <span class="text-white">New</span>
                                                    </div>
                                                <?php  } else { ?>
                                                    <div class="bg-danger p-1 discounts">
                                                        <span class="text-white small"><?php echo $row['discountpercent'] ?>% OFF</span>
                                                    </div>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <?php if ($row['discount'] == 1 && $row['newarrived'] == 1) {
                                                    $conn = connect();
                                                    $sql = "SELECT * FROM discount WHERE productid=?";
                                                    $stmt = $conn->prepare($sql);
                                                    $stmt->execute([$pid]);
                                                    $discount = $stmt->fetch();
                                                    $conn = null;
                                                ?>
                                                    <div class="bg-danger p-1 discounts">
                                                        <span class="text-white small"><?php echo $discount['discountpercent'] ?>% OFF</span>
                                                    </div>
                                                    <div class="bg-warning news">
                                                        <span class="text-white">New</span>
                                                    </div>
                                                <?php } else if ($row['discount'] == 1 && $row['newarrived'] == 0) {
                                                    $conn = connect();
                                                    $sql = "SELECT * FROM discount WHERE productid=?";
                                                    $stmt = $conn->prepare($sql);
                                                    $stmt->execute([$pid]);
                                                    $discount = $stmt->fetch();
                                                    $conn = null;
                                                ?>
                                                    <div class="bg-danger p-1 discounts">
                                                        <span class="text-white small"><?php echo $discount['discountpercent'] ?>% OFF</span>
                                                    </div>
                                                <?php } else if ($row['discount'] == 0 && $row['newarrived'] == 1) { ?>
                                                    <div class="bg-warning newarriveds">
                                                        <span class="text-white">New</span>
                                                    </div>
                                                <?php } ?>
                                            <?php } ?>

                                            <div class="product-action-vertical">
                                                <a href="./wishlist.php?pid=<?php echo $pid ?>" class="btn-wishlist"><i class="far fa-heart"></i></a>
                                                <a href="javascript:void(0);" class="btn-quickview" title="Quick view"><i class="fas fa-binoculars"></i></a>
                                            </div><!-- End .product-action-vertical -->

                                            <?php if (isset($_SESSION['email'])) { ?>
                                                <div class="product-action">
                                                    <a href="./cart.php?pid=<?php echo $pid ?>" class="btn-product btn-cart"><span>add to cart</span></a>
                                                </div><!-- End .product-action -->
                                            <?php  } ?>
                                        </figure><!-- End .product-media -->


                                        <div class="product-body px-3">
                                            <h3 class="product-title"><a href="productdetail.php?pid=<?php echo $row['pid'] ?>"><?php echo $row['pname'] ?></a></h3>
                                            <!-- End .product-title -->
                                            <?php if ($_SESSION['cid'] == "discount") { ?>
                                                <div class="product-price">
                                                    <del class="text-muted">$<?php echo $row['price'] ?></del>
                                                    <spa class="ps-1">$<?php echo $row['discountprice'] ?></spa>
                                                </div><!-- End .product-price -->
                                            <?php } else { ?>
                                                <?php if ($row['discount'] == 1) { ?>
                                                    <div class="product-price">
                                                        <del class="text-muted">$<?php echo $row['price'] ?></del>
                                                        <spa class="ps-1">$<?php echo $discount['discountprice'] ?></spa>
                                                    </div><!-- End .product-price -->
                                                <?php } else { ?>
                                                    <div class="product-price">
                                                        $<?php echo $row['price'] ?>
                                                    </div><!-- End .product-price -->
                                                <?php } ?>
                                            <?php } ?>
                                            <div class="ratings-container">
                                                <div class="ratings m-0">
                                                    <?php
                                                    $rating = round($row['rating']);
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

                            <?php
                            }
                        } else {
                            ?>
                            <p class="h4 text-center pt-5">Product Not found</p>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <!-- end product area -->

                <!-- start paginaion section -->
                <nav class="d-flex justify-content-center mb-5" aria-label="Page navigation">
                    <ul class="pagination">
                        <?php if ($page > 1) { ?>
                            <li class="page-item"><a class="page-link text-success" href="?page=1">First</a></li>
                            <li class="page-item">
                                <a class="page-link text-success" href="?page=<?php echo ($page - 1); ?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
                            </li>
                        <?php } ?>
                        <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                            <?php if ($i == 1 || $i == $totalPages || $i == $page || ($i >= $page - 100 && $i <= $page + 100)) { ?>
                                <li class="page-item"><a class="page-link text-success <?php echo ($i == $page) ? 'active' : ''; ?>" href="?page=<?php echo $i; ?>" style="background-color: <?php echo ($i == $page) ? 'lightgray' : 'white'; ?>; color: white; margin-right: 3px;"><?php echo $i; ?></a></li>
                        <?php }
                        } ?>
                        <?php if ($page < $totalPages) { ?>
                            <li class="page-item">
                                <a class="page-link text-success" href="?page=<?php echo ($page + 1); ?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
                            </li>
                            <li class="page-item"><a class="page-link text-success" href="?page=<?php echo $totalPages; ?>">Last</a></li>
                        <?php } ?>
                    </ul>
                </nav>
                <!-- end pagination section -->

            </div>
        </div>
        <!-- end product page content -->

        <!-- start fliter section -->
        <div class="sidebar-filter">
            <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                <h4>Filter</h4>
                <i class="fas fa-times h4 text-success closebtns"></i>
            </div>
            <form action="./productpage.php" method="post">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <button type="submit" name="productfilter" class="btn btn-success btn-sm rounded-0 p-0 px-3 py-1">Apply</button>
                    <button type="reset" class="btn btn-success btn-sm rounded-0 p-0 px-3 py-1 btnclears">Clear</button>
                </div><!-- End .widget -->

                <!-- start category filter -->
                <div class="border-bottom mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-3 category-widget-titles">
                        <h5 class="me-3">Category </h5>
                        <i class="fas fa-angle-down h5 category-widget-title-icons"></i>
                    </div>

                    <div class="category-widget pb-3">
                        <div class="widget-body">
                            <div class="filter-items filter-items-count">
                                <?php $totalproduct = 0; ?>
                                <?php foreach ($catgories as $category) { ?>

                                    <?php
                                    try {
                                        $categoryid = $category['ctgid'];
                                        $conn = connect();
                                        $sql = "SELECT COUNT(*) AS total FROM product WHERE categoryid = $categoryid";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute();
                                        $rows = $stmt->fetch(PDO::FETCH_ASSOC);
                                        $procutcounts = $rows['total'];
                                        $totalproduct += $procutcounts;
                                    } catch (PDOException $e) {
                                        echo $e->getMessage();
                                    }
                                    ?>

                                    <?php if ($procutcounts != 0) { ?>
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="category" id="<?php echo str_replace(" ", "", $category['ctgname']), $category['ctgid'] ?>" value="<?php echo $category['ctgid'] ?>" required />
                                                <label class=" form-check-label" for="<?php echo str_replace(" ", "", $category['ctgname']), $category['ctgid'] ?>">
                                                    <?php echo ucfirst($category['ctgname']) ?>
                                                </label>
                                            </div>
                                            <span class="item-count"><?php echo $procutcounts ?></span>
                                        </div><!-- End .filter-item -->
                                    <?php } ?>

                                <?php } ?>

                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category" id="all" value="all">
                                        <label class="form-check-label" for="all">
                                            Show All Cagetories
                                        </label>
                                    </div>
                                    <span class="item-count"><?php echo $totalproduct ?></span>
                                </div> <!-- End .filter-item -->

                            </div><!-- End .filter-items -->
                        </div><!-- End .widget-body -->
                    </div>
                </div>
                <!-- end category filter -->

                <!-- start size filter -->
                <div class="border-bottom mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-3 size-widget-titles">
                        <h5 class="me-3">Size</h5>
                        <i class="fas fa-angle-down h5 size-widget-title-icons"></i>
                    </div>

                    <div class="size-widget pb-3">
                        <div class="widget-body">
                            <div class="filter-items filter-items-count">
                                <?php
                                try {
                                    $conn = connect();
                                    $sql = "SELECT DISTINCT size FROM product";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute();
                                    $sizeofplant = $stmt->fetchAll();
                                    $conn = null;
                                } catch (PDOException $e) {
                                    echo $e->getMessage();
                                }
                                ?>

                                <?php foreach ($sizeofplant as $sizeofplant) { ?>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="form-check">
                                            <input class="form-check-input me-3" type="radio" name="plantsize" id="<?php echo $sizeofplant['size'] ?>" value="<?php echo $sizeofplant['size'] ?>" />
                                            <label class="form-check-label" for="<?php echo $sizeofplant['size'] ?>">
                                                <?php echo $sizeofplant['size'] ?>
                                            </label>
                                        </div>
                                    </div><!-- End .filter-item -->
                                <?php } ?>
                            </div><!-- End .filter-items -->
                        </div><!-- End .widget-body -->
                    </div>
                </div>
                <!-- end size filter -->

                <!-- start price filter -->
                <div class="border-bottom">
                    <div class="d-flex justify-content-between align-items-center mb-3 price-widget-titles">
                        <h5 class="me-3">Price </h5>
                        <i class="fas fa-angle-down h5 price-widget-title-icons"></i>
                    </div>

                    <div class="price-widget pb-3">
                        <div class="widget-body">
                            <div class="filter-items filter-items-count">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="form-group">
                                        <?php
                                        try {
                                            $conn = connect();
                                            $sql = "SELECT MAX(price) AS highest_price FROM product";
                                            $stmt = $conn->prepare($sql);
                                            $stmt->execute();
                                            $highestprice = $stmt->fetch();
                                            $conn = null;
                                        } catch (PDOException $e) {
                                            echo $e->getMessage();
                                        }
                                        ?>
                                        <label for="pricerange" class="form-label">Price Range: <span class="text-success">$0 to $<span class="text-success" id="max-price"><?php echo ceil(ceil($highestprice['highest_price']) / 2) ?></span></span></label>
                                        <input type="range" class="form-range" name="price" min="1" max="<?php echo ceil($highestprice['highest_price']) ?>" value="<?php echo ceil(ceil($highestprice['highest_price']) / 2) ?>" id="pricerange">
                                        <input type="hidden" name="midvalue" value="<?php echo ceil(ceil($highestprice['highest_price']) / 2) ?>"/>
                                    </div>
                                </div><!-- End .filter-item -->
                            </div><!-- End .filter-items -->
                        </div><!-- End .widget-body -->
                    </div>
                </div>
                <!-- end price filter -->
            </form>
        </div>
        <!-- start fliter section -->

    </main>
    <!-- End Product Section -->

    <!-- elfsight for telegram chat -->
    <div class="elfsight-app-c42bdffe-41f6-412d-8c61-0a216b2ddefc" data-elfsight-app-lazy></div>
    <!-- elfsight for telegram chat -->


    <!-- Start Footer Section-->
    <footer class="px-3 footerbgs">
        <div class="container-fluid">

            <div class="row text-white pt-5 pb-2">

                <div class="col-md-2 col-sm-6">
                    <h5 class="mb-3 text-uppercase">Userful Links</h5>
                    <ul class="list-unstyled"> <!-- list-unstyled = list-style:none, padding:0; , margin:0;-->
                        <li><a href="javascript:volid(0);" class="footerlinks">About Planti</a></li>
                        <li><a href="javascript:volid(0);" class="footerlinks">How to shop on Planti</a></li>
                        <li><a href="javascript:volid(0);" class="footerlinks">FAQ</a></li>
                        <li><a href="javascript:volid(0);" class="footerlinks">Contact us</a></li>
                        <li><a href="javascript:volid(0);" class="footerlinks">Login in</a></li>
                    </ul>
                </div>

                <div class="col-md-3 col-sm-6">
                    <h5 class="mb-3 text-uppercase">Customer Services</h5>
                    <ul class="list-unstyled"> <!-- list-unstyled = list-style:none, padding:0; , margin:0;-->
                        <li><a href="javascript:volid(0);" class="footerlinks">Payment Methods</a></li>
                        <li><a href="javascript:volid(0);" class="footerlinks">Money-back guarantee!</a></li>
                        <li><a href="javascript:volid(0);" class="footerlinks">Returns</a></li>
                        <li><a href="javascript:volid(0);" class="footerlinks">Shipping</a></li>
                        <li><a href="javascript:volid(0);" class="footerlinks">Terms and conditions</a></li>
                        <li><a href="javascript:volid(0);" class="footerlinks">Privacy Policy</a></li>
                    </ul>
                </div>

                <div class="col-md-2 col-sm-6">
                    <h5 class="mb-3 text-uppercase">My Account</h5>
                    <ul class="list-unstyled"> <!-- list-unstyled = list-style:none, padding:0; , margin:0;-->
                        <li><a href="javascript:volid(0);" class="footerlinks">Sign In</a></li>
                        <li><a href="javascript:volid(0);" class="footerlinks">View Cart</a></li>
                        <li><a href="javascript:volid(0);" class="footerlinks">My Wishlist</a></li>
                        <li><a href="javascript:volid(0);" class="footerlinks">Track My Order</a></li>
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
                                <li class="ms-3"><a href="javascript:volid(0);" class="nav-link"><i class="fab fa-instagram"></i></a></li>
                                <li class="ms-3"><a href="javascript:volid(0);" class="nav-link"><i class="fab fa-facebook"></i></a></li>
                                <li class="ms-3"><a href="javascript:volid(0);" class="nav-link"><i class="fab fa-youtube"></i></a></li>
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
                        <a href="javascript:void(0);" class="text-light">Terms of Use</a>
                        <span class="px-2"> | </span>
                        <a href="javascript:void(0);" class="text-light">Privacy Policy</a>
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
    <script src="./js/filter.js" type="text/javascript"></script>

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

</body>

</html>