<?php

require_once "dbconnect.php";

$subtotal = 0;

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

function checkincarttable($cid, $pid)
{
    try {
        $conn = connect();
        $sql = "SELECT * FROM cart WHERE customerid=? AND productid=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$cid, $pid]);
        $cartproduct = $stmt->fetch();
        return $cartproduct;
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['pid'])) {
    $email = $_SESSION['email'];
    $userdatas = userdata($email);
    $cid = $userdatas['cid'];
    $pid = $_GET['pid'];
    $quantity = 1;

    $cartproduct = checkincarttable($cid, $pid);

    if ($cartproduct == null) {
        try {
            $conn = connect();
            $sql = "INSERT INTO cart (productid,customerid,quantity) VALUES (?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$pid, $cid, $quantity]);
            $conn = null;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    } else {
        $quantity = $cartproduct['quantity'] + 1;
        try {
            $conn = connect();
            $sql = "UPDATE cart SET quantity=? WHERE productid=? AND customerid=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$quantity, $pid, $cid]);
            $conn = null;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['addtocart'])) {
    $email = $_SESSION['email'];
    $userdatas = userdata($email);
    $cid = $userdatas['cid'];
    $pid = $_POST['pid'];
    $quantity = $_POST['quantity'];

    $cartproduct = checkincarttable($cid, $pid);

    if ($cartproduct == null) {
        try {
            $conn = connect();
            $sql = "INSERT INTO cart (productid,customerid,quantity) VALUES (?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$pid, $cid, $quantity]);
            $conn = null;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    } else {
        $quantity += $cartproduct['quantity'];
        try {
            $conn = connect();
            $sql = "UPDATE cart SET quantity=? WHERE productid=? AND customerid=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$quantity, $pid, $cid]);
            $conn = null;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['deletaction'])) {
    $cid = $_GET['cid'];
    $pid = $_GET['pid'];

    try {
        $conn = connect();
        $sql = "DELETE FROM cart WHERE customerid=? AND productid=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$cid,$pid]);
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

function getcartproduct($cid)
{
    try {
        $conn = connect();
        $sql = "SELECT * FROM cart WHERE customerid=? ";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$cid]);
        $cartproducts = $stmt->fetchAll();
        return $cartproducts;
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function getdiscount($pid)
{
    try {
        $conn = connect();
        $sql = "SELECT discountprice FROM discount WHERE productid=? ";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$pid]);
        $discounts = $stmt->fetch();
        return $discounts;
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

$email = $_SESSION['email'];
$userdatas = userdata($email);
$cid = $userdatas['cid'];
$getcartcounts = getcartcount($cid);
$cartcount = $getcartcounts['count'];
$cartproducts = getcartproduct($cid);

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['updatecard'])) {
    foreach($cartproducts as $cartproduct){
        $cartid = $cartproduct['cartid'];
        $updatequantity = $_POST[$cartid];
        try {
            $conn = connect();
            $sql = "UPDATE cart SET quantity = ? WHERE cartid=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$updatequantity,$cartid]);
            $conn = null;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    $cartproducts = getcartproduct($cid);
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
</head>

<body>

    <!-- Start Back to Top Us Section -->
    <div class="fixed-bottom">
        <a href="./cart.php" class="btn-backtotops"><i class="fas fa-arrow-up"></i></a>
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

    <!-- Start Cart Section -->
    <main class="prodcut-section">

        <!-- start cart page header -->
        <div class="text-center" style="background-image: url('./assets/img/banner/banner2.jpg');background-size:cover;">
            <div class="container-fluid py-5 product-page-headers">
                <h1 class="text-uppercase">Shopping Cart</h1>
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
                    <li class="breadcrumb-item" aria-current="page">Shopping Cart</li>
                </ol>
            </div><!-- End .container-fluid -->
        </nav><!-- End .breadcrumb-nav -->
        <!-- end .breadcrumb-nav -->

        <!-- start cart page content -->
        <div class="page-content mt-5">
            <div class="container">
                <div class="row">

                    <div class="col-lg-8">
                        <form action="./cart.php" method="post">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-muted" scope="col">Product</th>
                                        <th class="text-muted" scope="col"></th>
                                        <th class="text-muted" scope="col">Price</th>
                                        <th class="text-muted" scope="col">Quantity</th>
                                        <th class="text-muted" scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($cartproducts != null) { ?>

                                        <?php foreach ($cartproducts as $cartproduct) { ?>
                                            <tr class="align-middle">
                                                <th class="text-success py-3" scope="row">
                                                    <?php
                                                    try {
                                                        $pid = $cartproduct['productid'];
                                                        $conn = connect();
                                                        $sql = "SELECT * from productimage WHERE productid = ?";
                                                        $stmt = $conn->prepare($sql);
                                                        $stmt->execute([$pid]);
                                                        $productimages = $stmt->fetchAll();

                                                        $sql = "SELECT * from product WHERE pid = ?";
                                                        $stmt = $conn->prepare($sql);
                                                        $stmt->execute([$pid]);
                                                        $product = $stmt->fetch();

                                                        $conn = null;
                                                    } catch (PDOException $e) {
                                                        echo $e->getMessage();
                                                    }
                                                    ?>
                                                    <a href="./productdetail.php?pid=<?php echo $cartproduct['productid'] ?>">
                                                        <img src="<?php echo $productimages[0]['url'] ?>" width="70" alt="Product<?php echo $pid ?>" />
                                                    </a>
                                                </th>
                                                <td class="py-3" style="max-width: 220px;">
                                                    <h5 class="mt-2">
                                                        <a href="./productdetail.php?pid=<?php echo $cartproduct['productid'] ?>" class="nav-link"><?php echo $product['pname'] ?></a>
                                                    </h5>
                                                </td>
                                                <?php if ($product['discount'] == 1) { ?>
                                                    <td class="text-success pt-4 py-3">
                                                        <?php
                                                        $getdiscounts = getdiscount($pid);
                                                        $discountprice =  $getdiscounts['discountprice'];
                                                        ?>
                                                        <p><del>$<?php echo $product['price'] ?></del></p>
                                                        <p>$<?php echo $discountprice ?></p>
                                                    </td>
                                                <?php } else { ?>
                                                    <td class="text-success py-3">
                                                        $<?php echo $product['price'] ?>
                                                    </td>
                                                <?php } ?>
                                                <td class="text-success py-3">
                                                    <div class="form-group" style="width: 80px;">
                                                        <input type="number" name="<?php echo $cartproduct['cartid'] ?>" class="form-control rounded-0 border-success" value="<?php echo $cartproduct['quantity'] ?>" min="1" max="100" step="1" required>
                                                    </div>
                                                </td>
                                                <td class="text-danger py-3">
                                                    <?php
                                                    if ($product['discount'] == 1) {
                                                        $getdiscounts = getdiscount($pid);
                                                        $discountprice =  $getdiscounts['discountprice'];
                                                        $count = $cartproduct['quantity'];
                                                        $totalprice = $discountprice * $count;
                                                        echo "$" . number_format($totalprice, 2);
                                                        $subtotal += $totalprice;
                                                    } else {
                                                        $count = $cartproduct['quantity'];
                                                        $totalprice = $product['price'] * $count;
                                                        echo "$" . number_format($totalprice, 2);
                                                        $subtotal += $totalprice;
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-danger py-3">
                                                    <a href="./cart.php?deletaction=delete&cid=<?php echo $cid ?>&pid=<?php echo $cartproduct['productid'] ?>" class="nav-link mt-2"><i class="fas fa-times h4"></i></a>
                                                </td>
                                            </tr>
                                        <?php } ?>

                                    <?php } else { ?>
                                        <tr class="text-center">
                                            <td class="py-5" colspan="5">Product Not Found In Cart</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end mt-4 mb-4">
                                <button class="text-center h6 border-success reload-btns px-3 py-2" type="submit" name="updatecard">UPDATE CARD<i class="fas fa-sync-alt h5 ms-2"></i></button>
                            </div>
                        </form>
                    </div>

                    <div class="col-lg-4 mb-5">
                        <div class="px-3 pt-4 cart-summerys">
                            <form action="./checkout.php" method="post" id="formA">
                                <h5 class="border-bottom border-2 pb-3">Card Total</h5>
                                <table class="table borderless">
                                    <tbody>
                                        <tr class="align-middle h6 border-bottom">
                                            <td class="py-4">
                                                <div class="d-flex justify-content-between">
                                                    <p>Subtotal:</p>
                                                    <p>$<?php echo number_format($subtotal, 2) ?></p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="align-middle h6">
                                            <td class="py-4" colspan="2">
                                                <p>Free Shipping</p>
                                                <?php if ($subtotal >= 200) { ?>
                                                    <div class="progress" role="progressbar" aria-label="Example with label" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                                        <div class="progress-bar progress-bar-striped bg-success" style="width: 100%">100%</div>
                                                    </div>
                                                <?php } else { ?>
                                                    <?php
                                                    $delifreeprice = $subtotal / 200;
                                                    $delifreeprice = round($delifreeprice, 2);
                                                    $percent = floor($delifreeprice * 100);
                                                    ?>
                                                    <div class="progress" role="progressbar" aria-label="Example with label" aria-valuenow="<?php echo $percent ?>" aria-valuemin="0" aria-valuemax="100">
                                                        <div class="progress-bar progress-bar-striped bg-success" style="width: <?php echo $percent ?>%"><?php echo $percent ?>%</div>
                                                    </div>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <tr class="align-middle h6 border-bottom"">
                                            <td class=" py-4" colspan="2">
                                            <p>Shipping: </p>
                                            <div>
                                                <?php if ($subtotal >= 200) { ?>
                                                    <div class="d-flex justify-content-between">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input text-success" type="radio" name="shipping" role="switch" id="free" value="0.00" checked required>
                                                            <label class="form-check-label" for="free">Free Shipping</label>
                                                        </div>
                                                        <p>$0.00</p>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="radio" name="shipping" role="switch" id="ygn" value="2.00" disabled required>
                                                            <label class="form-check-label" for="ygn">Yangon</label>
                                                        </div>
                                                        <p>$2.00</p>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="radio" name="shipping" role="switch" id="mdy" value="5.00" disabled required>
                                                            <label class="form-check-label" for="mdy">Mandalay</label>
                                                        </div>
                                                        <p>$5.00</p>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="radio" name="shipping" role="switch" id="npt" value="4.00" disabled required>
                                                            <label class="form-check-label" for="npt">Nay Pyi Taw</label>
                                                        </div>
                                                        <p>$4.00</p>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="radio" name="shipping" role="switch" id="tg" value="7.00" disabled required>
                                                            <label class="form-check-label" for="tg">Taung Gyi</label>
                                                        </div>
                                                        <p>$7.00</p>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="radio" name="shipping" role="switch" id="mlm" value="5.00" disabled required>
                                                            <label class="form-check-label" for="mlm">Maw La Myin</label>
                                                        </div>
                                                        <p>$5.00</p>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="radio" name="shipping" role="switch" id="myk" value="8.00" disabled required>
                                                            <label class="form-check-label" for="myk">Myeik</label>
                                                        </div>
                                                        <p>$8.00</p>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="d-flex justify-content-between">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input text-success" type="radio" name="shipping" role="switch" id="free" value="0.00" disabled required>
                                                            <label class="form-check-label" for="free">Free Shipping</label>
                                                        </div>
                                                        <p>$0.00</p>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="radio" name="shipping" role="switch" id="ygn" value="2.00" required>
                                                            <label class="form-check-label" for="ygn">Yangon</label>
                                                        </div>
                                                        <p>$2.00</p>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="radio" name="shipping" role="switch" id="mdy" value="5.00" required>
                                                            <label class="form-check-label" for="mdy">Mandalay</label>
                                                        </div>
                                                        <p>$5.00</p>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="radio" name="shipping" role="switch" id="npt" value="4.00" required>
                                                            <label class="form-check-label" for="npt">Nay Pyi Taw</label>
                                                        </div>
                                                        <p>$4.00</p>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="radio" name="shipping" role="switch" id="tg" value="7.00" required>
                                                            <label class="form-check-label" for="tg">Taung Gyi</label>
                                                        </div>
                                                        <p>$7.00</p>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="radio" name="shipping" role="switch" id="mlm" value="5.00" required>
                                                            <label class="form-check-label" for="mlm">Maw La Myin</label>
                                                        </div>
                                                        <p>$5.00</p>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="radio" name="shipping" role="switch" id="myk" value="8.00" required>
                                                            <label class="form-check-label" for="myk">Myeik</label>
                                                        </div>
                                                        <p>$8.00</p>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            </td>
                                        </tr>
                                        <tr class="align-middle h6 border-bottom"">
                                            <td class=" py-4" colspan="2">
                                            <p>Location: </p>
                                            <div class="form-floating">
                                                <textarea class="form-control pt-4 pb-5" name="address" placeholder="Enter Location" id="floatingTextarea" style="resize:none" required></textarea>
                                                <label for="floatingTextarea">Enter Address</label>
                                            </div>
                                            </td>
                                        </tr>
                                        <tr class="align-middle h5 ">
                                            <td class="py-3 pt-4 text-danger" colspan="2">
                                                <div class="d-flex justify-content-between">
                                                    <p>Total:</p>
                                                    <p id="totalprice">$<?php echo number_format($subtotal, 2)?></p>
                                                </div>
                                                <input type="hidden" id="subtotal" name="subtotal" value="<?php echo number_format($subtotal, 2) ?>" />
                                                <input type="hidden" id="total" name="total" value="<?php echo number_format($subtotal, 2) ?>" />
                                                <input type="hidden" name="cid" value="<?php echo $cid?>" />
                                                <button type="submit" class="checkout-btns" name="checkout">PROCESS TO CHECKOUT</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
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