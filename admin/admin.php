<?php

require_once "./../dbconnect.php";
$admin = null;

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
        $sql = "SELECT * FROM admin WHERE amemail = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);
        $admin = $stmt->fetch();
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}


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
                                    <a href="./admin.php" class="nav-link text-white p-3 mb-2 sidebarlinks currents">
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
                                        <li><a href="javascript:void(0);" class="nav-link text-white sidebarlinks"><i class="fas fa-scroll me-4"></i> Adv Banner</a></li>
                                        <li><a href="javascript:void(0);" class="nav-link text-white sidebarlinks"><i class="fas fa-thumbtack ms-1 me-4"></i> Announcement</a></li>
                                        <li><a href="javascript:void(0);" class="nav-link text-white sidebarlinks"><i class="fas fa-chalkboard me-4"></i>Adv Carousel</a></li>
                                        <li><a href="javascript:void(0);" class="nav-link text-white sidebarlinks"><i class="fas fa-file-upload me-4"></i> Upload Blog</a></li>
                                    </ul>
                                </li>

                                <li class="nav-item nav-categories">Promotion</li>

                                <li class="nav-item">
                                    <a href="./discountitem.php" class="nav-link text-white p-3 mb-2 sidebarlinks"><i class="fas fa-tags me-3"></i> Add Discount Item</a>
                                </li>

                                <li class="nav-item">
                                    <a href="./newarriveditem.php" class="nav-link text-white p-3 mb-2 sidebarlinks"><i class="fas fa-dolly-flatbed me-3"></i> Add New Arrived Item</a>
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

                    <!-- Start Shortcut Area -->

                    <div class="row pt-md-5 mt-md-3">

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
                                            <p class="h5 text-muted">50</p>
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

                    <!-- Start Carousel Area -->
                    <div class="row">
                        <div class="col-md-3 col-sm-6 mb-2">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Sales</h6>

                                    <div id="sales" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner p-3">

                                            <div class="carousel-item active">

                                                <div class="d-flex">
                                                    <h3 class="me-3">$ 58,664</h3>
                                                    <h3 class="text-danger">+3.2%</h3>
                                                </div>

                                                <div>
                                                    <p class="fw-bold text-small">Revenue <span class="text-muted">($1572M last month)</span></p>
                                                </div>

                                                <button type="button" class="btn btn-outline-secondary btn-sm">
                                                    <i class="fas fa-calendar-alt me-1"></i> Jan
                                                </button>

                                            </div>

                                            <div class="carousel-item ">

                                                <div class="d-flex">
                                                    <h3 class="me-3">$ 68,664</h3>
                                                    <h3 class="text-danger">+2.2%</h3>
                                                </div>

                                                <div>
                                                    <p class="fw-bold text-small">Revenue <span class="text-muted">($1572M last month)</span></p>
                                                </div>

                                                <button type="button" class="btn btn-outline-secondary btn-sm">
                                                    <i class="fas fa-calendar-alt me-1"></i> Jan
                                                </button>

                                            </div>

                                            <div class="carousel-item">

                                                <div class="d-flex">
                                                    <h3 class="me-3">$ 78,664</h3>
                                                    <h3 class="text-danger">+1.2%</h3>
                                                </div>

                                                <div>
                                                    <p class="fw-bold text-small">Revenue <span class="text-muted">($1572M last month)</span></p>
                                                </div>

                                                <button type="button" class="btn btn-outline-secondary btn-sm">
                                                    <i class="fas fa-calendar-alt me-1"></i> Jan
                                                </button>

                                            </div>

                                            <button type="button" class="carousel-control-prev" data-bs-target="#sales" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon"></span>
                                            </button>

                                            <button type="button" class="carousel-control-next" data-bs-target="#sales" data-bs-slide="next">
                                                <span class="carousel-control-next-icon"></span>
                                            </button>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-3 col-sm-6 mb-2">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Purchease</h6>

                                    <div id="purches" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner p-3">

                                            <div class="carousel-item active">

                                                <div class="d-flex">
                                                    <h3 class="me-3">$ 58,664</h3>
                                                    <h3 class="text-danger">+3.2%</h3>
                                                </div>

                                                <div>
                                                    <p class="fw-bold text-small">Revenue <span class="text-muted">($1572M last month)</span></p>
                                                </div>

                                                <button type="button" class="btn btn-outline-secondary btn-sm">
                                                    <i class="fas fa-calendar-alt me-1"></i> Jan
                                                </button>

                                            </div>

                                            <div class="carousel-item ">

                                                <div class="d-flex">
                                                    <h3 class="me-3">$ 68,664</h3>
                                                    <h3 class="text-danger">+2.2%</h3>
                                                </div>

                                                <div>
                                                    <p class="fw-bold text-small">Revenue <span class="text-muted">($1572M last month)</span></p>
                                                </div>

                                                <button type="button" class="btn btn-outline-secondary btn-sm">
                                                    <i class="fas fa-calendar-alt me-1"></i> Jan
                                                </button>

                                            </div>

                                            <div class="carousel-item">

                                                <div class="d-flex">
                                                    <h3 class="me-3">$ 78,664</h3>
                                                    <h3 class="text-danger">+1.2%</h3>
                                                </div>

                                                <div>
                                                    <p class="fw-bold text-small">Revenue <span class="text-muted">($1572M last month)</span></p>
                                                </div>

                                                <button type="button" class="btn btn-outline-secondary btn-sm">
                                                    <i class="fas fa-calendar-alt me-1"></i> Jan
                                                </button>

                                            </div>

                                            <button type="button" class="carousel-control-prev" data-bs-target="#purches" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon"></span>
                                            </button>

                                            <button type="button" class="carousel-control-next" data-bs-target="#purches" data-bs-slide="next">
                                                <span class="carousel-control-next-icon"></span>
                                            </button>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-3 col-sm-6 mb-2">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Return</h6>

                                    <div id="return" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner p-3">

                                            <div class="carousel-item active">

                                                <div class="d-flex">
                                                    <h3 class="me-3">$ 58,664</h3>
                                                    <h3 class="text-danger">+3.2%</h3>
                                                </div>

                                                <div>
                                                    <p class="fw-bold text-small">Revenue <span class="text-muted">($1572M last month)</span></p>
                                                </div>

                                                <button type="button" class="btn btn-outline-secondary btn-sm">
                                                    <i class="fas fa-calendar-alt me-1"></i> Jan
                                                </button>

                                            </div>

                                            <div class="carousel-item ">

                                                <div class="d-flex">
                                                    <h3 class="me-3">$ 68,664</h3>
                                                    <h3 class="text-danger">+2.2%</h3>
                                                </div>

                                                <div>
                                                    <p class="fw-bold text-small">Revenue <span class="text-muted">($1572M last month)</span></p>
                                                </div>

                                                <button type="button" class="btn btn-outline-secondary btn-sm">
                                                    <i class="fas fa-calendar-alt me-1"></i> Jan
                                                </button>

                                            </div>

                                            <div class="carousel-item">

                                                <div class="d-flex">
                                                    <h3 class="me-3">$ 78,664</h3>
                                                    <h3 class="text-danger">+1.2%</h3>
                                                </div>

                                                <div>
                                                    <p class="fw-bold text-small">Revenue <span class="text-muted">($1572M last month)</span></p>
                                                </div>

                                                <button type="button" class="btn btn-outline-secondary btn-sm">
                                                    <i class="fas fa-calendar-alt me-1"></i> Jan
                                                </button>

                                            </div>

                                            <button type="button" class="carousel-control-prev" data-bs-target="#return" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon"></span>
                                            </button>

                                            <button type="button" class="carousel-control-next" data-bs-target="#return" data-bs-slide="next">
                                                <span class="carousel-control-next-icon"></span>
                                            </button>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-3 col-sm-6 mb-2">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Marketing</h6>

                                    <div id="marketing" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner p-3">

                                            <div class="carousel-item active">

                                                <div class="d-flex">
                                                    <h3 class="me-3">$ 58,664</h3>
                                                    <h3 class="text-danger">+3.2%</h3>
                                                </div>

                                                <div>
                                                    <p class="fw-bold text-small">Revenue <span class="text-muted">($1572M last month)</span></p>
                                                </div>

                                                <button type="button" class="btn btn-outline-secondary btn-sm">
                                                    <i class="fas fa-calendar-alt me-1"></i> Jan
                                                </button>

                                            </div>

                                            <div class="carousel-item ">

                                                <div class="d-flex">
                                                    <h3 class="me-3">$ 68,664</h3>
                                                    <h3 class="text-danger">+2.2%</h3>
                                                </div>

                                                <div>
                                                    <p class="fw-bold text-small">Revenue <span class="text-muted">($1572M last month)</span></p>
                                                </div>

                                                <button type="button" class="btn btn-outline-secondary btn-sm">
                                                    <i class="fas fa-calendar-alt me-1"></i> Jan
                                                </button>

                                            </div>

                                            <div class="carousel-item">

                                                <div class="d-flex">
                                                    <h3 class="me-3">$ 78,664</h3>
                                                    <h3 class="text-danger">+1.2%</h3>
                                                </div>

                                                <div>
                                                    <p class="fw-bold text-small">Revenue <span class="text-muted">($1572M last month)</span></p>
                                                </div>

                                                <button type="button" class="btn btn-outline-secondary btn-sm">
                                                    <i class="fas fa-calendar-alt me-1"></i> Jan
                                                </button>

                                            </div>

                                            <button type="button" class="carousel-control-prev" data-bs-target="#marketing" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon"></span>
                                            </button>

                                            <button type="button" class="carousel-control-next" data-bs-target="#marketing" data-bs-slide="next">
                                                <span class="carousel-control-next-icon"></span>
                                            </button>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>


                    </div>
                    <!-- End Carousel Area -->

                    <!-- Start Gauge Area -->
                    <div class="row">

                        <div class="col-lg-3 col-md-6 mb-2">
                            <div class="card shadown py-2 border-left-primarys">
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col">
                                            <h6 class="text-us fw-bold text-primary text-uppercase mb-1">Users</h6>
                                        </div>
                                        <div class="col-auto">
                                            <p class="h6 text-muted">Report</p>
                                        </div>
                                    </div>

                                    <div class="col-auto">
                                        <div id="gaugeusers">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-2">
                            <div class="card shadown py-2 border-left-successes">
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col">
                                            <h6 class="text-us fw-bold text-primary text-uppercase mb-1">Customers</h6>
                                        </div>
                                        <div class="col-auto">
                                            <p class="h6 text-muted">Report</p>
                                        </div>
                                    </div>

                                    <div class="col-auto">
                                        <div id="gaugecustomers">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-2">
                            <div class="card shadown py-2 border-left-infos">
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col">
                                            <h6 class="text-us fw-bold text-primary text-uppercase mb-1">Employees</h6>
                                        </div>
                                        <div class="col-auto">
                                            <p class="h6 text-muted">Report</p>
                                        </div>
                                    </div>

                                    <div class="col-auto">
                                        <div id="gaugeemployees">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-2">
                            <div class="card shadown py-2 border-left-warnings">
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col">
                                            <h6 class="text-us fw-bold text-primary text-uppercase mb-1">Invester</h6>
                                        </div>
                                        <div class="col-auto">
                                            <p class="h6 text-muted">Report</p>
                                        </div>
                                    </div>

                                    <div class="col-auto">
                                        <div id="gaugeinvesters">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- End Gauge Area -->


                    <!-- Start Expenses Area -->
                    <div class="row">

                        <div class="col-md-7 mb-4">
                            <div class="card shadown">
                                <div class="card-header">
                                    <h6 class="text-primary">Expenses</h6>
                                </div>
                                <div class="card-body">

                                    <h6 class="small">Other Expenses 20%</h6>
                                    <div class="progress mb-3">
                                        <div class="progress-bar bg-danger" style="width: 20%;" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>

                                    <h6 class="small">Sale Tracking 40%</h6>
                                    <div class="progress mb-3">
                                        <div class="progress-bar bg-warning" style="width: 40%;" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>

                                    <h6 class="small">Rental Fee 60%</h6>
                                    <div class="progress mb-3">
                                        <div class="progress-bar bg-primary" style="width: 60%;" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>

                                    <h6 class="small">Salary 80%</h6>
                                    <div class="progress mb-3">
                                        <div class="progress-bar bg-info" style="width: 80%;" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>

                                    <h6 class="small">Fixture 100%</h6>
                                    <div class="progress mb-3">
                                        <div class="progress-bar bg-success" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="card shadow">
                                <div class="card-header">
                                    <h6 class="text-primary">Revenue Sources</h6>
                                </div>
                                <div class="card-body">
                                    <div style="height: 250px; display: flex; justify-content: center; align-items: center;">
                                        <canvas id="myChart"></canvas>
                                    </div>
                                    <div class="small text-center mt-2">
                                        <span><i class="fas fa-circle text-warning"></i> Return Item</span>
                                        <span class="mx-2"><i class="fas fa-circle text-primary"></i> Direct
                                            Sales</span>
                                        <span><i class="fas fa-circle text-danger"></i> Online Sales</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- End Expenses Area -->

                    <!-- Start Earning Area -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="card-title">Earning Overview</h6>
                                    <div class="dropdown">
                                        <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>

                                        <div class="dropdown-menu shadow">
                                            <div class="dropdown-headder">Quick Action</div>
                                            <a href="javascript:void(0);" class="dropdown-item">Action</a>
                                            <a href="javascript:void(0);" class="dropdown-item">Edit</a>
                                            <div class="dropdown-divider"></div>
                                            <a href="javascript:void(0);" class="dropdown-item">View Report</a>
                                        </div>

                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="curve_chart" style="width: 100%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Earning Area -->

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

    <!-- Start Alert Message Section -->
    <div class="modal fade" id="onload" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-column justify-content-center align-items-center">
                        <img src="./../assets/img/gif/success.gif" width="150px" />
                        <!-- register success message -->
                        <?php
                        if (isset($_SESSION['admin_signup_success'])) {
                        ?>
                            <h5 class="mt-4"><?php echo $_SESSION['admin_signup_success'] ?></h5>
                        <?php
                        } ?>
                        <!-- login success message -->
                        <?php
                        if (isset($_SESSION['admin_login_success'])) {
                        ?>
                            <h5 class="mt-4"><?php echo $_SESSION['admin_login_success'] ?></h5>
                        <?php
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Alert Message Section -->


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
    <script src="./js/dashboard.js" type="text/javascript"></script>

    <?php
    if (isset($_SESSION['admin_signup_success']) || isset($_SESSION['admin_login_success'])) {
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
        unset($_SESSION['admin_signup_success']);
        unset($_SESSION['admin_login_success']);
    } ?>

</body>

</html>