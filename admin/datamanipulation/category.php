<?php

require_once "./../../dbconnect.php";
$categories = null;
$porductcountbycategory = null;
$eachcategory = null;
$admin = null;
$descriptions = ["plant","seed","accessory"];

if (!isset($_SESSION)) {
    session_start(); // to create succes if not exist
}

if (!isset($_SESSION['admin_email'])) {
    header("Location:./../adminlogin&signup.php");
}

if (isset($_SESSION['admin_email'])) {
    $email = $_SESSION['admin_email'];
    try {
        $conn = connect();
        $sql = "SELECT * FROM admin WHERE amemail = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);
        $admin = $stmt->fetch();
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
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

function productcountbycategory()
{
    try {
        $conn = connect();  // Assuming connect() is a function that returns a PDO connection
        $sql = "
            SELECT c.ctgid AS CategoryID, c.ctgname AS CategoryName, COUNT(p.pid) AS ProductCount
            FROM category AS c
            LEFT JOIN product AS p ON c.ctgid = p.categoryid
            GROUP BY c.ctgid, c.ctgname
        ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $count = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Fetch results as an associative array
        $conn = null;  // Close the connection
        return $count;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['cid'])) {
    $cid = $_GET['cid'];
    try {
        $conn = connect();
        $sql = "SELECT * FROM category WHERE ctgid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$cid]);
        $eachcategory = $stmt->fetchAll();
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['catginsert'])) {
    $catgname = htmlspecialchars($_POST['catgname']);
    $description = htmlspecialchars($_POST['descrp']);
    try {
        $conn = connect();
        $stmt = $conn->prepare("INSERT INTO category (ctgname,description) VALUES (:ctgname,:descrip)");
        $stmt->bindValue(':ctgname', $catgname);
        $stmt->bindValue(':descrip', $description);
        $stmt->execute();
        $_SESSION['catg_insert_success'] = "Your New Category is successfully inserted";
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['catgupdate'])) {
    $catgid = htmlspecialchars($_POST['catgid']);
    $catgname = htmlspecialchars($_POST['catgname']);
    $description = htmlspecialchars($_POST['descrp']);
    try {
        $conn = connect();
        $stmt = $conn->prepare("UPDATE category SET ctgname=?, description=? WHERE ctgid=?");
        $stmt->execute([$catgname, $description, $catgid]);
        $_SESSION['catg_update_success'] = "Your Category is successfully updated";
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

$categories = getcategory();
$porductcountbycategory = productcountbycategory();


?>

<!DOCTYPE html>
<html>

<head>
    <title>Planti | Admin</title>
    <!-- fav icon -->
    <link href="./../../assets/img/fav/logo2.png" rel="icon" type="image/png" sizes="16X16" />
    <!-- bootstrap css1 js1 -->
    <link href="./../../assets/libs/bootstrap-5.3.2-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- fontawesome css1 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- jquery ui css1 js1 -->
    <link href="./../../assets/libs/jquery-ui-1.13.2/jquery-ui.min.css" rel="stylesheet" type="text/css" />
    <!-- custom css -->
    <link href="./../css/admin.css" rel="stylesheet" type="text/css">

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
                                        <img src="./../../assets/img/fav/logo2.png" width="40" class="rounded-circle"/>
                                        <h4 class="text-white text-uppercase mt-2 me-3" style="letter-spacing: 2px;">Planti</h4>
                                    </div>
                                </li>

                                <li class="nav-item nav-categories">Main</li>

                                <li class="nav-item">
                                    <a href="./../admin.php" class="nav-link text-white p-3 mb-2 sidebarlinks">
                                        <i class="fas fa-tachometer-alt me-3"></i> Dashboard
                                    </a>
                                </li>

                                <li class="nav-item"><a href="javascript:void(0);" class="nav-link text-white p-3 mb-2 sidebarlinks currents" data-bs-toggle="collapse" data-bs-target="#datas"><i class="fas fa-database me-3"></i> Data Manipulation <i class="fas fa-angle-left mores"></i></a>
                                    <ul id="datas" class="collapse list-unstyled ms-4">
                                        <li><a href="./product.php" class="nav-link text-white sidebarlinks"><i class="fas fa-seedling me-4"></i> Product</a></li>
                                        <li><a href="./category.php" class="nav-link text-white sidebarlinks currents"><i class="fas fa-list-alt me-4"></i> Category</a></li>
                                        <li><a href="./productimage.php" class="nav-link text-white sidebarlinks"><i class="fas fa-images me-4"></i> Product Image</a></li>
                                    </ul>
                                </li>

                                <li class="nav-item"><a href="javascript:void(0);" class="nav-link text-white p-3 mb-2 sidebarlinks" data-bs-toggle="collapse" data-bs-target="#orders"><i class="fas fa-store me-3"></i> Order <i class="fas fa-angle-left mores"></i></a>
                                    <ul id="orders" class="collapse list-unstyled ms-4">
                                        <li><a href="./orderstatus.php" class="nav-link text-white sidebarlinks"><i class="fas fa-edit me-4"></i> Update Order Status</a></li>
                                        <li><a href="./orderdetail.php" class="nav-link text-white sidebarlinks"><i class="fas fa-info-circle me-4"></i> Oder Detail</a></li>
                                        <li><a href="./orderhistory.php" class="nav-link text-white sidebarlinks"><i class="fas fa-history me-4"></i> Order History</a></li>
                                    </ul>
                                </li>

                                <li class="nav-item"><a href="javascript:void(0);" class="nav-link text-white p-3 mb-2 sidebarlinks" data-bs-toggle="collapse" data-bs-target="#advs"><i class="fas fa-ad me-3"></i> Advertisement Area <i class="fas fa-angle-left mores"></i></a>
                                    <ul id="advs" class="collapse list-unstyled ms-4">
                                        <li><a href="javascript:void(0);" class="nav-link text-white sidebarlinks"><i class="fas fa-scroll me-4"></i> Adv Banner</a></li>
                                        <li><a href="javascript:void(0);" class="nav-link text-white sidebarlinks"><i class="fas fa-thumbtack ms-1 me-4"></i> Announcement</a></li>
                                        <li><a href="javascript:void(0);" class="nav-link text-white sidebarlinks"><i class="fas fa-chalkboard me-4"></i>Adv Carousel</a></li>
                                        <li><a href="javascript:void(0);" class="nav-link text-white sidebarlinks"><i class="fas fa-file-upload me-4"></i> Upload Blog</a></li>
                                    </ul>
                                </li>

                                <li class="nav-item nav-categories">Promotion</li>

                                <li class="nav-item">
                                    <a href="./../discountitem.php" class="nav-link text-white p-3 mb-2 sidebarlinks"><i class="fas fa-tags me-3"></i> Add Discount Item</a>
                                </li>

                                <li class="nav-item">
                                    <a href="./../newarriveditem.php" class="nav-link text-white p-3 mb-2 sidebarlinks"><i class="fas fa-dolly-flatbed me-3"></i> Add New Arrived Item</a>
                                </li>

                                <li class="nav-item nav-categories">Acconut</li>

                                <li class="nav-item">
                                    <a href="javascript:void(0);" class="nav-link text-white p-3 mb-2 sidebarlinks"><i class="fas fa-user me-3"></i> Customers</a>
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
                                                <img src="./../../assets/img/user/admin.png" class="rounded-circle" width="50" alt="user1" />
                                            </a>

                                            <div class="dropdown-menu">
                                                <a href="javascript:void(0);" class="dropdown-item"><i class="fas fa-user text-muted" me-2></i> Profile</a>
                                                <a href="javascript:void(0);" class="dropdown-item"><i class="fas fa-user text-muted" me-2></i> Setting</a>
                                                <a href="javascript:void(0);" class="dropdown-item"><i class="fas fa-user text-muted" me-2></i> Activity</a>
                                                <a href="./../adminlogout.php" class="dropdown-item"><i class="fas fa-user text-muted" me-2></i> Logout</a>
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

                    <!-- Start Shortcut Area -->
                    <div class="row">

                        <div class="col-lg-3 col-md-6 mb-2">
                            <div class="card shadow py-2 border-left-primarys">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="text-primary text-xs fw-bold text-uppercase mb-1">Sales (Monthly)
                                            </h6>
                                            <p class="h5 text-muted">$ 50,000</p>
                                        </div>
                                        <div class="col-auto">
                                            <!-- col-auto is used to move the text of the column to the end of the column-->
                                            <i class="fas fa-calendar fa-2x text-secondary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-2">
                            <div class="card shadow py-2 border-left-successes">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="text-primary text-xs fw-bold text-uppercase mb-1">rental fee
                                                (anual)</h6>
                                            <p class="h5 text-muted">$ 50,000</p>
                                        </div>
                                        <div class="col-auto">
                                            <!-- col-auto is used to move the text of the column to the end of the column-->
                                            <i class="fas fa-dollar-sign fa-2x text-secondary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-2">
                            <div class="card shadow py-2 border-left-infos">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="text-primary text-xs fw-bold text-uppercase mb-1">debit collect
                                            </h6>
                                            <div class="row">
                                                <div class="col-auto">
                                                    <p class="h5 text-muted">60%</p>
                                                </div>
                                                <div class="col">
                                                    <div class="progress" style="height: 13px;">
                                                        <div class="progress-bar bg-info" style="width: 60%;" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <!-- col-auto is used to move the text of the column to the end of the column-->
                                            <i class="fas fa-clipboard-list fa-2x text-secondary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-2">
                            <div class="card shadow py-2 border-left-warnings">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="text-primary text-xs fw-bold text-uppercase mb-1">Reques Message
                                            </h6>
                                            <p class="h5 text-muted">$ 50,000</p>
                                        </div>
                                        <div class="col-auto">
                                            <!-- col-auto is used to move the text of the column to the end of the column-->
                                            <i class="fas fa-comments fa-2x text-secondary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- End Shortcut Area -->

                    <!-- Start Form Area -->
                    <div class="row pt-md-5 mt-md-3">
                        <!-- start insert form -->
                        <div class="col-md-6 mb-2">
                            <div class="card shadow">
                                <div class="card-body px-4">
                                    <h4 class="card-title text-success mb-4">Insert Category</h4>
                                    <form action="category.php" method="post">
                                        <div class="form-group px-3 mb-4">
                                            <label for="catgname" class="h6 pb-1">Name</label>
                                            <input type="text" name="catgname" id="catgname" class="form-control rounded-0 border-success" placeholder="Enter Category Name" required>
                                        </div>
                                        <div class="form-group px-3 mb-4">
                                            <label for="descrp" class="h6 pb-1">Description</label>
                                            <select class="form-select form-select-md border-success rounded-0 mb-3" name="descrp" id="descrp" required>
                                                <option selected disabled>Select Category Description</option>
                                                <option value="plant">Plant</option>
                                                <option value="seed">Seed</option>
                                                <option value="accessory">Accessory</option>
                                            </select>
                                        </div>
                                        <div class="px-3">
                                            <button type="submit" name="catginsert" class="btn btn-success rounded-0 me-3">Insert</button>
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
                                    <h4 class="card-title text-success mb-4">Update Category
                                        <?php if (isset($_GET['cid'])) { ?>
                                            (ID - <?php echo $eachcategory[0]['ctgid'] ?> )
                                        <?php } ?>
                                    </h4>
                                    <form action="category.php" method="Post">
                                        <input type="hidden" name="catgid" value="<?php echo $eachcategory[0]['ctgid'] ?>" />
                                        <div class="form-group px-3 mb-4">
                                            <label for="catgname" class="h6 pb-1">Name</label>
                                            <?php if (isset($_GET['cid'])) { ?>
                                                <input type="text" name="catgname" id="catgname" class="form-control rounded-0 border-success" value="<?php echo $eachcategory[0]['ctgname'] ?>" required>
                                            <?php } else { ?>
                                                <input type="text" name="catgname" id="catgname" class="form-control rounded-0 border-success" placeholder="Enter Category Name" required>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group px-3 mb-4">
                                            <label for="updatedescrp" class="h6 pb-1">Description</label>
                                            <?php if (isset($_GET['cid'])) { ?>
                                                <select class="form-select form-select-md border-success rounded-0 mb-3" name="descrp" id="updatedescrp" required>
                                                    <option value="<?php echo $eachcategory[0]['description'] ?>" selected><?php echo ucfirst($eachcategory[0]['description'])?></option>
                                                    <?php foreach($descriptions as $description){ 
                                                        if($description != $eachcategory[0]['description']){   
                                                    ?>
                                                            <option value="<?php echo $description ?>"><?php echo ucfirst($description) ?></option>
                                                    <?php
                                                        } 
                                                    }
                                                    ?>
                                                </select>
                                            <?php } else { ?>
                                                <input type="text" name="descrp" id="updatedescrp" class="form-control rounded-0 border-success" placeholder="Enter Category Description" required>
                                            <?php } ?>
                                        </div>
                                        <div class="px-3">
                                        <?php if (isset($_GET['cid'])) { ?>
                                            <button type="submit" name="catgupdate" class="btn btn-success rounded-0 me-3">Update</button>
                                        <?php } else { ?>
                                            <button type="submit" name="catgupdate" class="btn btn-success rounded-0 me-3" disabled>Update</button>
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
                                <div class="card-body">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr class="table-success text-center">
                                                <th scope="col">Category ID</th>
                                                <th scope="col">Category Name</th>
                                                <th scope="col">Description</th>
                                                <th scope="col">Product Count</th>
                                                <th scope="col">Features</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($categories as $category) { ?>
                                                <tr class="text-center align-middle">
                                                    <th scope="row"><?php echo $category['ctgid'] ?></th>
                                                    <td><?php echo $category['ctgname'] ?></td>
                                                    <td><?php echo $category['description'] ?></td>
                                                    <?php foreach ($porductcountbycategory as $pcount) { ?>
                                                        <?php if ($category['ctgid'] == $pcount['CategoryID']) { ?>
                                                            <td><?php echo $pcount['ProductCount'] ?></td>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <td><a href="category.php?cid=<?php echo $category['ctgid'] ?>" class="btn btn-warning btn-sm">Update</a></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
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
    if (isset($_SESSION['catg_insert_success'])) {
        $alertmessage = $_SESSION['catg_insert_success'];
        $gifpath = "./../../assets/img/gif/success.gif";
    } elseif (isset($_SESSION['catg_update_success'])) {
        $alertmessage = $_SESSION['catg_update_success'];
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
    <script src="./../../assets/libs/bootstrap-5.3.2-dist/js/bootstrap.bundle.min.js" type="text/javascript"></script>
    <!-- jquery js1 -->
    <script src="./../../assets/libs/jquery/jquery-3.7.1.min.js" type="text/javascript"></script>
    <!-- jquery ui css1 js1 -->
    <script src="./../../assets/libs/jquery-ui-1.13.2/jquery-ui.min.js" type="text/javascript"></script>
    <!-- googlechart js1 -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- chartjs js1 -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- justage js1 -->
    <!-- Raphael must be included before justgage -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.4/raphael-min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/justgage/1.2.9/justgage.min.js"></script>
    <!-- custom js -->
    <script src="./../js/admin.js" type="text/javascript"></script>

    <?php
    if (isset($_SESSION['catg_insert_success']) || isset($_SESSION['catg_update_success'])) {
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
        unset($_SESSION['catg_insert_success']);
        unset($_SESSION['catg_update_success']);
    } ?>
</body>

</html>