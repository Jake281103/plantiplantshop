<?php

require_once "./../dbconnect.php";
$admin = null;
$products = null;
date_default_timezone_set("Asia/Yangon");

if (!isset($_SESSION)) {
    session_start(); // to create succes if not exist
}

if (!isset($_SESSION['admin_email'])) {
    header("Location:adminlogin&signup.php");
}

if (isset($_SESSION['admin_email'])) {
    $email = $_SESSION['admin_email'];
    try {
        $conn = connect();

        // Get all admin data
        $sql = "SELECT * FROM admin WHERE amemail = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        // Get all product data
        $sql = "SELECT * FROM product";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $products = $stmt->fetchAll();

        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

// To check enter discount product if it exists or not in discount table 
function checkinnewtable($pid)
{
    try {
        $conn = connect();
        $sql = "SELECT * FROM newarrived WHERE productid=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$pid]);
        if ($stmt->rowCount() > 0) {
            return false;
        } else {
            return true;
        }
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['newinsert'])) {
    $pid = $_POST['pid'];
    $date = $_POST['date'];
    $checkinnewtable = checkinnewtable($pid);
    if ($checkinnewtable) {
        try {
            $conn = connect();

            // To update the newarrived column in product table
            $sql = "UPDATE product SET newarrived=1 WHERE pid=$pid";
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $sql = "INSERT INTO newarrived (productid,date) VALUES (?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$pid, $date]);
            $_SESSION['newarrived_insert_success'] = "New Arrived Product is successfully inserted";
            $conn = null;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    } else {
        $_SESSION['newarrived_product_exist'] = "New Arrived Product is already existed";
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['newupdate'])) {
    $npid = $_POST['npid'];
    $prepid = $_POST['prepid'];
    $pid = $_POST['pid'];
    $date = $_POST['date'];
    try {
        $conn = connect();
        if ($prepid != $pid) {
            $checkinnewtable = checkinnewtable($pid);
            if ($checkinnewtable) {

                // update newarrived column of previous product
                $sql = "UPDATE product SET newarrived=0 WHERE pid=$prepid";
                $stmt = $conn->prepare($sql);
                $stmt->execute();

                // update newarrived column of new arrived product
                $sql = "UPDATE product SET newarrived=1 WHERE pid=$pid";
                $stmt = $conn->prepare($sql);
                $stmt->execute();

                // update in newarrived table
                $sql = "UPDATE newarrived SET productid=?, date=? WHERE npid=?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$pid, $date, $npid]);

                $_SESSION['newarrived_update_success'] = "New Arrived Product is successfully Updated";

                $conn = null;
            } else {
                $conn = null;
                $_SESSION['newarrived_product_exist'] = "New Arrived Product is already existed";
            }
        } else {

            // update in newarrived table
            $sql = "UPDATE newarrived SET productid=?, date=? WHERE npid=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$pid, $date, $npid]);

            $_SESSION['newarrived_update_success'] = "New Arrived Product is successfully Updated";

            $conn = null;
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['deleteid']) && isset($_GET['pid'])) {
    $npid = $_GET['deleteid'];
    $pid = $_GET['pid'];
    try {
        $conn = connect();

        // update newarrived column from product table
        $sql = "UPDATE product SET newarrived=0 WHERE pid=$pid";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // delete new arrived product from newarrived table
        $sql = "DELETE FROM newarrived WHERE npid=$npid";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $_SESSION['newarrived_delete_success'] = "New Arrived ID(" . $npid . ") has been successfully deleted";

        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

// Start Pagination
if (!isset($_SESSION['showrowcount'])) {
    $_SESSION['showrowcount'] = 10;
}

$productsPerPage = null;
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['rowcountupdate'])) {
    $_SESSION['showrowcount'] = $_POST['rowcount']; // Adjust this value as needed
}

$productsPerPage = $_SESSION['showrowcount']; // Adjust this value as needed

// Get the current page number from the URL parameter, default to page 1
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Calculate the offset for the SQL query
$offset = ($page - 1) * $productsPerPage;

// Query to fetch products from the database for the current page
$conn = connect();
$sql = "SELECT * FROM newarrived ORDER BY date LIMIT $offset, $productsPerPage";
$stmt = $conn->query($sql);

// Calculate the total number of products with the search term
$totalProductsQuery = "SELECT COUNT(*) AS total FROM newarrived";
$totalProductsStmt = $conn->prepare($totalProductsQuery);
$totalProductsStmt->execute();
$totalProductsRow = $totalProductsStmt->fetch(PDO::FETCH_ASSOC);
$totalProducts = $totalProductsRow['total'];

// Calculate the total number of pages
$totalPages = ceil($totalProducts / $productsPerPage);

// End Pagination


?>

<!DOCTYPE html>
<html>

<head>
    <title>Planti | Admin</title>
    <!-- fav icon -->
    <link href="./../assets/img/fav/logo2.png" rel="icon" type="image/png" sizes="16X16" />
    <!-- bootstrap css1 js1 -->
    <link href="./../assets/libs/bootstrap-5.3.2-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- fontawesome css1 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- jquery ui css1 js1 -->
    <link href="./../assets/libs/jquery-ui-1.13.2/jquery-ui.min.css" rel="stylesheet" type="text/css" />
    <!-- custom css -->
    <link href="./css/admin.css" rel="stylesheet" type="text/css">

</head>

<body>

    <!-- Start Site Setting -->
    <div id="sitesettings" class="sitesettings">
        <div class="sitesettings-item">
            <a href="javascript:void(0);" id="sitetoggle" class="sitetoggle">
                <i class="fas fa-cog anirotates"></i>
            </a>
        </div>
    </div>
    <!-- End Site Setting -->


    <!-- Start Left Navbar -->
    <div class="wrappers">
        <nav class="navbar navbar-expand-md navbar-light">
            <button type="button" class="navbar-toggler ms-auto mb-2" data-bs-toggle="collapse" data-bs-target="#nav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div id="nav">
                <div class="container-fluid">
                    <div class="row">

                        <!-- start left side bar -->
                        <!-- Note:: -->
                        <!-- vh-100    height:100vh; -->
                        <!-- overflow-auto       overflow-y:scroll; -->
                        <div class="col-lg-2 col-md-3 fixed-top vh-100 overflow-auto leftsidebars">
                            <ul class="navbar-nav flex-column mt-4">

                                <li class="nav-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <img src="./../assets/img/fav/logo2.png" width="40" class="rounded-circle" />
                                        <h4 class="text-white text-uppercase mt-2 me-3" style="letter-spacing: 2px;">Planti</h4>
                                    </div>
                                </li>

                                <li class="nav-item nav-categories">Main</li>

                                <li class="nav-item">
                                    <a href="./admin.php" class="nav-link text-white p-3 mb-2 sidebarlinks">
                                        <i class="fas fa-tachometer-alt me-3"></i> Dashboard
                                    </a>
                                </li>

                                <li class="nav-item"><a href="javascript:void(0);" class="nav-link text-white p-3 mb-2 sidebarlinks" data-bs-toggle="collapse" data-bs-target="#datas"><i class="fas fa-database me-3"></i> Data Manipulation <i class="fas fa-angle-left mores"></i></a>
                                    <ul id="datas" class="collapse list-unstyled ms-4">
                                        <li><a href="./datamanipulation/product.php" class="nav-link text-white sidebarlinks"><i class="fas fa-seedling me-4"></i> Product</a></li>
                                        <li><a href="./datamanipulation/category.php" class="nav-link text-white sidebarlinks"><i class="fas fa-list-alt me-4"></i> Category</a></li>
                                        <li><a href="./datamanipulation/productimage.php" class="nav-link text-white sidebarlinks"><i class="fas fa-images me-4"></i> Product Image</a></li>
                                    </ul>
                                </li>

                                <li class="nav-item"><a href="javascript:void(0);" class="nav-link text-white p-3 mb-2 sidebarlinks" data-bs-toggle="collapse" data-bs-target="#orders"><i class="fas fa-store me-3"></i> Order <i class="fas fa-angle-left mores"></i></a>
                                    <ul id="orders" class="collapse list-unstyled ms-4">
                                        <li><a href="./datamanipulation/orderstatus.php" class="nav-link text-white sidebarlinks"><i class="fas fa-edit me-4"></i> Update Order Status</a></li>
                                        <li><a href="./datamanipulation/orderdetail.php" class="nav-link text-white sidebarlinks"><i class="fas fa-info-circle me-4"></i> Oder Detail</a></li>
                                        <li><a href="./datamanipulation/orderdetail.php" class="nav-link text-white sidebarlinks"><i class="fas fa-history me-4"></i> Order History</a></li>
                                    </ul>
                                </li>

                                <li class="nav-item"><a href="javascript:void(0);" class="nav-link text-white p-3 mb-2 sidebarlinks" data-bs-toggle="collapse" data-bs-target="#advs"><i class="fas fa-ad me-3"></i> Advertisement Area <i class="fas fa-angle-left mores"></i></a>
                                    <ul id="advs" class="collapse list-unstyled ms-4">
                                        <li><a href="./datamanipulation/orderstatus.php" class="nav-link text-white sidebarlinks"><i class="fas fa-edit me-4"></i> Update Order Status</a></li>
                                        <li><a href="./datamanipulation/orderdetail.php" class="nav-link text-white sidebarlinks"><i class="fas fa-info-circle me-4"></i> Oder Detail</a></li>
                                        <li><a href="./datamanipulation/orderdetail.php" class="nav-link text-white sidebarlinks"><i class="fas fa-history me-4"></i> Order History</a></li>
                                    </ul>
                                </li>

                                <li class="nav-item nav-categories">Promotion</li>

                                <li class="nav-item">
                                    <a href="./discountitem.php" class="nav-link text-white p-3 mb-2 sidebarlinks"><i class="fas fa-tags me-3"></i> Add Discount Item</a>
                                </li>

                                <li class="nav-item">
                                    <a href="./newarriveditem.php" class="nav-link text-white p-3 mb-2 sidebarlinks currents"><i class="fas fa-dolly-flatbed me-3"></i> Add New Arrived Item</a>
                                </li>

                                <li class="nav-item nav-categories">Acconut</li>

                                <li class="nav-item">
                                    <a href="javascript:void(0);" class="nav-link text-white p-3 mb-2 sidebarlinks">
                                        <i class="fas fa-user me-3"></i> Customers
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="javascript:void(0);" class="nav-link text-white p-3 mb-2 sidebarlinks"><i class="fas fa-users-cog me-3"></i> Admin</a>
                                </li>

                                <li class="nav-item nav-categories">Data Representation</li>

                                <li class="nav-item"><a href="javascript:void(0);" class="nav-link text-white p-3 mb-2 sidebarlinks" data-bs-toggle="collapse" data-bs-target="#fixedanalysis"><i class="fas fa-download me-3"></i> Fixed
                                        Analysis <i class="fas fa-angle-left mores"></i></a>
                                    <ul id="fixedanalysis" class="collapse">
                                        <li><a href="javascript:void(0);" class="nav-link text-white sidebarlinks"><i class="fas fa-long-arrow-alt-right me-4"></i> Days </a>
                                        </li>
                                        <li><a href="javascript:void(0);" class="nav-link text-white sidebarlinks"><i class="fas fa-long-arrow-alt-right me-4"></i> Categories </a>
                                        </li>
                                        <li><a href="javascript:void(0);" class="nav-link text-white sidebarlinks"><i class="fas fa-long-arrow-alt-right me-4"></i> Payment Types </a>
                                        </li>
                                    </ul>
                                </li>

                            </ul>
                        </div>
                        <!-- end left side bar -->

                        <!-- start top side bar -->
                        <div class="col-lg-10 col-md-9 fixed-top ms-auto topnavbars">

                            <div class="row">

                                <div class="navbar navbar-expand navbar-light bg-white shadow">
                                    <!-- start quick search -->
                                    <form class="me-auto" action="" method="">

                                        <div class="input-group">
                                            <input type="text" name="quicksearch" id="quicksearch" class="form-control border border-success rounded-0 ms-2" placeholder="Search Something..." />
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-success rounded-0" data-bs-toggle="modal" data-bs-target="#quicksearchmodal"><i class="fas fa-search"></i></button>
                                            </div>
                                        </div>

                                    </form>
                                    <!-- end quick search -->

                                    <!-- start notify & useraccount -->
                                    <ul class="navbar-nav pe-5 me-5">
                                        <!-- notify -->
                                        <li class="nav-item me-3 dropdowns">
                                            <!-- if javascript funcction is write in html inline, event must be used and not use other likes e -->
                                            <a href="javascript:void(0);" class="nav-link" onclick="dropbtn(event)">
                                                <i class="fas fa-bell"></i>
                                                <span class="badge bg-danger">5+</span>
                                            </a>

                                            <div class="dropdown-contents mydropdowns">
                                                <h6>Alert Center</h6>

                                                <a href="javascript:void(0);" class="d-flex">
                                                    <div class="me-3">
                                                        <i class="fas fa-file-alt"></i>
                                                    </div>
                                                    <div>
                                                        <p class="small text-muted">21 May 2024</p>
                                                        <i>A new member created</i>
                                                    </div>
                                                </a>

                                                <a href="javascript:void(0);" class="d-flex">
                                                    <div class="me-3">
                                                        <i class="fas fa-file-alt"></i>
                                                    </div>
                                                    <div>
                                                        <p class="small text-muted">21 May 2024</p>
                                                        <i>A new member created</i>
                                                    </div>
                                                </a>

                                                <a href="javascript:void(0);" class="d-flex">
                                                    <div class="me-3">
                                                        <i class="fas fa-file-alt"></i>
                                                    </div>
                                                    <div>
                                                        <p class="small text-muted">21 May 2024</p>
                                                        <i>A new member created</i>
                                                    </div>
                                                </a>
                                            </div>
                                        </li>
                                        <!-- notify -->

                                        <!-- message -->
                                        <!-- message -->

                                        <!-- user account -->
                                        <li class="nav-item dropdown">
                                            <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown">
                                                <span class="text-muted small me-2"><?php echo $admin['amname'] ?></span>
                                                <img src="./../assets/img/user/admin.png" class="rounded-circle" width="50" alt="user1" />
                                            </a>

                                            <div class="dropdown-menu">
                                                <a href="javascript:void(0);" class="dropdown-item"><i class="fas fa-user text-muted" me-2></i> Profile</a>
                                                <a href="javascript:void(0);" class="dropdown-item"><i class="fas fa-user text-muted" me-2></i> Setting</a>
                                                <a href="javascript:void(0);" class="dropdown-item"><i class="fas fa-user text-muted" me-2></i> Activity</a>
                                                <a href="./adminlogout.php" class="dropdown-item"><i class="fas fa-user text-muted" me-2></i> Logout</a>
                                            </div>
                                        </li>
                                        <!-- user account -->
                                    </ul>
                                    <!-- end notify & useraccount -->

                                    <!-- start mobile close btn -->
                                    <button type="button" class="close-btns" data-bs-toggle="collapse" data-bs-target="#nav">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <!-- end mobile close btn -->

                                </div>

                            </div>


                        </div>
                        <!-- end top side bar -->

                    </div>
                </div>
            </div>
        </nav>
    </div>
    <!-- Start Left Navbar -->


    <!-- Start Content Area -->
    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-10 col-md-9 ms-auto">

                    <!-- Start Form Area -->
                    <div class="row pt-md-5 mt-md-3">
                        <!-- start insert form -->
                        <div class="col-md-6 mb-2">
                            <div class="card shadow">
                                <div class="card-body px-4">
                                    <h4 class="card-title text-success mb-4">Insert New Arrived Product</h4>
                                    <form action="./newarriveditem.php" method="post">
                                        <div class="form-group px-3 mb-4">
                                            <label for="pid" class="h6 pb-1">Product ID</label>
                                            <select class="form-select form-select-md border-success rounded-0 mb-3" name="pid" id="pid" required>
                                                <option selected disabled>Select Product Id</option>
                                                <?php foreach ($products as $product) { ?>
                                                    <option value="<?php echo $product['pid'] ?>"><?php echo ucfirst($product['pid']) ?> - <?php echo ucfirst($product['pname']) ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group px-3 mb-4">
                                            <label for="date" class="h6 pb-1">Date</label>
                                            <input type="date" name="date" id="date" class="form-control rounded-0 border-success" min="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>" required>
                                        </div>
                                        <div class="px-3">
                                            <button type="submit" name="newinsert" class="btn btn-success rounded-0 me-3">Insert</button>
                                            <button type="reset" class="btn btn-danger rounded-0">Reset</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- end insert form -->

                        <!-- start update form -->
                        <div class="col-md-6 mb-2">
                            <div class="card shadow">
                                <div class="card-body px-4">
                                    <h4 class="card-title text-success mb-4">Update New Arrived Product
                                        <?php if (isset($_GET['updateid']) && isset($_GET['pid'])) { ?>
                                            (ID - <?php echo $_GET['updateid'] ?> )
                                        <?php } ?>
                                    </h4>
                                    <form action="./newarriveditem.php" method="post">
                                        <?php if (isset($_GET['updateid']) && isset($_GET['pid']) && isset($_GET['date'])) { ?>
                                            <input type="hidden" name="npid" value="<?php echo $_GET['updateid'] ?>" />
                                        <?php } ?>
                                        <?php if (isset($_GET['updateid']) && isset($_GET['pid']) && isset($_GET['date'])) { ?>
                                            <input type="hidden" name="prepid" value="<?php echo $_GET['pid'] ?>" />
                                        <?php } ?>
                                        <div class="form-group px-3 mb-4">
                                            <label for="updatepid" class="h6 pb-1">Product ID</label>
                                            <?php if (isset($_GET['updateid']) && isset($_GET['pid']) && isset($_GET['date'])) { ?>
                                                <select class="form-select form-select-md border-success rounded-0 mb-3" name="pid" id="updatepid" required>
                                                    <?php foreach ($products as $product) { ?>
                                                        <?php if ($_GET['pid'] == $product['pid']) { ?>
                                                            <option value="<?php echo $product['pid'] ?>" selected><?php echo ucfirst($product['pid']) ?> - <?php echo ucfirst($product['pname']) ?></option>
                                                        <?php } else { ?>
                                                            <option value="<?php echo $product['pid'] ?>"><?php echo ucfirst($product['pid']) ?> - <?php echo ucfirst($product['pname']) ?></option>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            <?php } else { ?>
                                                <select class="form-select form-select-md border-success rounded-0 mb-3" name="pid" id="updatepid" required>
                                                    <option selected disabled>Select Product Id</option>
                                                    <?php foreach ($products as $product) { ?>
                                                        <option value="<?php echo $product['pid'] ?>"><?php echo ucfirst($product['pid']) ?> - <?php echo ucfirst($product['pname']) ?></option>
                                                    <?php } ?>
                                                </select>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group px-3 mb-4">
                                            <label for="updatedate" class="h6 pb-1">Date</label>
                                            <?php if (isset($_GET['updateid']) && isset($_GET['pid']) && isset($_GET['date'])) { ?>
                                                <input type="date" name="date" id="updatedate" class="form-control rounded-0 border-success" min="<?php echo $_GET['date'] ?>" max="<?php echo date("Y-m-d"); ?>" value="<?php echo $_GET['date'] ?>" required>
                                            <?php } else { ?>
                                                <input type="date" name="date" id="updatedate" class="form-control rounded-0 border-success" min="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>" placeholder="Enter Discount Percent" required>
                                            <?php } ?>
                                        </div>
                                        <div class="px-3">
                                            <?php if (isset($_GET['updateid']) && isset($_GET['pid']) && isset($_GET['date'])) { ?>
                                                <button type="submit" name="newupdate" class="btn btn-success rounded-0 me-3">Update</button>
                                            <?php } else { ?>
                                                <button type="submit" name="newupdate" class="btn btn-success rounded-0 me-3" disabled>Update</button>
                                            <?php } ?>
                                            <button type="reset" class="btn btn-danger rounded-0">Reset</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- end update form -->

                    </div>
                    <!-- End Form Area -->

                    <!-- Start Table Area -->
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="card shadow">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div class="text-muted" style="font-size: 14px;">
                                        Showing <span class="text-success fw-bold"><?php echo (($page - 1) * $productsPerPage + 1); ?> - <?php echo min($page * $productsPerPage, $totalProducts); ?> of <?php echo $totalProducts; ?></span> Products
                                    </div>
                                    <div class="mt-1">
                                        <form action="./newarriveditem.php" method="post">
                                            <div class="form-group d-flex">
                                                <select class="form-select form-select-sm border-success rounded-0 mb-2" id="rowcount" name="rowcount">
                                                    <option value="10" selected>10 Roles</option>
                                                    <option value="12">12 Roles</option>
                                                    <option value="14">14 Roles</option>
                                                    <option value="16">16 Roles</option>
                                                    <option value="18">18 Roles</option>
                                                    <option value="20">20 Roles</option>
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-success ms-2" name="rowcountupdate" style="height:32px;">Change</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr class="table-success text-center">
                                                <th scope="col">New Arrived ID</th>
                                                <th scope="col">Product Id</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Features</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($stmt->rowCount() > 0) {
                                                $row = $stmt->fetchAll();
                                                $conn = null;
                                                foreach ($row as $row) {
                                            ?>
                                                    <tr class="text-center align-middle">
                                                        <th scope="row"><?php echo $row['npid'] ?></th>
                                                        <td><?php echo $row['productid'] ?></td>
                                                        <td><?php echo $row['date'] ?></td>
                                                        <td style="width: 400px;">
                                                            <a href="newarriveditem.php?updateid=<?php echo $row['npid'] ?>&pid=<?php echo $row['productid'] ?>&date=<?php echo $row['date'] ?>" class="btn btn-warning btn-sm me-3">Update</a>
                                                            <a href="newarriveditem.php?deleteid=<?php echo $row['npid'] ?>&pid=<?php echo $row['productid'] ?>" class="btn btn-danger btn-sm">Delete</a>
                                                        </td>
                                                    </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                    </table>
                                    <!-- start paginaion section -->
                                    <nav class="d-flex justify-content-center my-2" aria-label="Page navigation">
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
                        </div>
                    </div>
                    <!-- End Table Area -->

                </div>
            </div>
        </div>
    </section>
    <!-- End Content Area -->


    <!-- Start Footer Section -->
    <footer>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-10 col-md-9 ms-auto">

                    <div class="row border-top pt-3 mt-3">
                        <div class="col-md-6 text-center">
                            <u class="list-inline">
                                <li class="list-inline-item me-2">
                                    <a href="javascript:void(0);">Planti Plant Shop</a>
                                </li>
                                <li class="list-inline-item me-2">
                                    <a href="javascript:void(0);">About</a>
                                </li>
                                <li class="list-inline-item me-2">
                                    <a href="javascript:void(0);">Contact</a>
                                </li>
                            </u>
                        </div>
                        <div class="col-md-6 text-center">
                            <p>&copy; <span id="getyear">2000</span> Copyright. All Rights Reserved.</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </footer>
    <!-- End Footer Section -->

    <!-- Start Right Navbar -->
    <div class="right-panels">
        <h6>Customer your template</h6>
        <p class="small">Hifi!! here you can change you theme</p>
        <hr />
        <div class="themecolors">
            <a href="javascript:void(0);"><i class="fas fa-square text-primary shadow fa-lg"></i></a>
            <a href="javascript:void(0);"><i class="fas fa-square text-secondary shadow fa-lg"></i></a>
            <a href="javascript:void(0);"><i class="fas fa-square text-info shadow fa-lg"></i></a>
            <a href="javascript:void(0);"><i class="fas fa-square text-success shadow fa-lg"></i></a>
            <a href="javascript:void(0);"><i class="fas fa-square text-warning shadow fa-lg"></i></a>
            <a href="javascript:void(0);"><i class="fas fa-square text-danger shadow fa-lg"></i></a>
            <a href="javascript:void(0);"><i class="fas fa-square text-muted shadow fa-lg"></i></a>
            <a href="javascript:void(0);"><i class="fas fa-square text-white shadow fa-lg"></i></a>
            <a href="javascript:void(0);"><i class="fas fa-square text-dark shadow fa-lg"></i></a>
            <a href="javascript:void(0);"><i class="fas fa-square text-light shadow fa-lg"></i></a>
        </div>
    </div>
    <!-- End Right Navbar -->


    <!-- Start Modal Area -->
    <div id="quicksearchmodal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h6 class="modal-title">Result</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group">
                        <li class="list-group-item"><a href="javascript:void(0);"></a>WDF 1001</li>
                        <li class="list-group-item"><a href="javascript:void(0);"></a>WDF 1002</li>
                        <li class="list-group-item"><a href="javascript:void(0);"></a>WDF 1003</li>
                        <li class="list-group-item"><a href="javascript:void(0);"></a>WDF 1004</li>
                    </ul>
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
    <!-- End Modal Area -->

    <!-- Start Message Modal -->
    <?php
    $alertmessage = null;
    $gifpath = null;
    if (isset($_SESSION['newarrived_insert_success'])) {
        $alertmessage = $_SESSION['newarrived_insert_success'];
        $gifpath = "./../../assets/img/gif/success.gif";
    } elseif (isset($_SESSION['newarrived_update_success'])) {
        $alertmessage = $_SESSION['newarrived_update_success'];
        $gifpath = "./../../assets/img/gif/success.gif";
    } elseif (isset($_SESSION['newarrived_product_exist'])) {
        $alertmessage = $_SESSION['newarrived_product_exist'];
        $gifpath = "./../../assets/img/gif/fail1.gif";
    } elseif (isset($_SESSION['newarrived_delete_success'])) {
        $alertmessage = $_SESSION['newarrived_delete_success'];
        $gifpath = "./../../assets/img/gif/success.gif";
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
    <script src="./../assets/libs/bootstrap-5.3.2-dist/js/bootstrap.bundle.min.js" type="text/javascript"></script>
    <!-- jquery js1 -->
    <script src="./../assets/libs/jquery/jquery-3.7.1.min.js" type="text/javascript"></script>
    <!-- jquery ui css1 js1 -->
    <script src="./../assets/libs/jquery-ui-1.13.2/jquery-ui.min.js" type="text/javascript"></script>
    <!-- googlechart js1 -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- chartjs js1 -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- justage js1 -->
    <!-- Raphael must be included before justgage -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.4/raphael-min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/justgage/1.2.9/justgage.min.js"></script>
    <!-- custom js -->
    <script src="./js/admin.js" type="text/javascript"></script>

    <?php
    if (
        isset($_SESSION['newarrived_insert_success']) || isset($_SESSION['newarrived_update_success']) || isset($_SESSION['newarrived_product_exist'])
        || isset($_SESSION['newarrived_delete_success'])
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
        unset($_SESSION['newarrived_insert_success']);
        unset($_SESSION['newarrived_update_success']);
        unset($_SESSION['newarrived_product_exist']);
        unset($_SESSION['newarrived_delete_success']);
    } ?>


</body>

</html>