<?php
require_once "dbconnect.php";

date_default_timezone_set("Asia/Yangon");

if (!isset($_SESSION)) {
    session_start(); // to create succes if not exist
}

if (!isset($_SESSION['email'])) {
    header("Location:index.php");
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['checkout'])) {
    $_SESSION['subtotal'] = $_POST['subtotal'];
    $_SESSION['shipping'] = $_POST['shipping'];
    $_SESSION['total'] = $_POST['total'];
    $_SESSION['address'] = $_POST['address'];
    $_SESSION['cid'] = $_POST['cid'];
}

if (!isset($_SESSION['subtotal'])) {
    header("Location:index.php");
}


if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['function']) && $_GET['function'] == 'cancel') {

    unset($_SESSION['subtotal']);
    unset($_SESSION['shipping']);
    unset($_SESSION['total']);
    unset($_SESSION['address']);
    unset($_SESSION['cid']);

    header("Location:cart.php");
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['buy'])) {
    $cardnumber = $_POST['carddigit'];
    $cardname = $_POST['cardname'];
    $cardexp = $_POST['cardexp'];
    $cardcvv = $_POST['cardccv'];

    try {
        $conn = connect();
        $sql = "SELECT * FROM payment WHERE crnumber=? AND crname=? AND  expdate=? AND ccv=?";
        $stmt = $conn->prepare($sql);
        $status = $stmt->execute([$cardnumber, $cardname, $cardexp, $cardcvv]);
        $customer = $stmt->fetch();
        if ($stmt->rowCount() > 0) {

            $subtotal = $_SESSION['subtotal'];
            $shipping = $_SESSION['shipping'];
            $total = $_SESSION['total'];
            $address = $_SESSION['address'];
            $cid = $_SESSION['cid'];
            $shiplocation = null;
            $date = date("Y-m-d");
            $status = "progress";

            if ($shipping == 0.00) {
                $shiplocation = 'free shipping';
            } elseif ($shipping == 2.00) {
                $shiplocation = 'yangon';
            } elseif ($shipping == 5.00) {
                $shiplocation = 'mandalay';
            } elseif ($shipping == 4.00) {
                $shiplocation = 'nay pyitaw';
            } elseif ($shipping == 7.00) {
                $shiplocation = 'taug gyi';
            } elseif ($shipping == 5.00) {
                $shiplocation = 'mawlamyin';
            } elseif ($shipping == 8.00) {
                $shiplocation = 'myeik';
            }

            // echo $subtotal, $shipping, $total, $address, $cid, $date, $shiplocation;

            $sql = "INSERT INTO orders (customerid,subtotal,delifee,shipping,total,date,address,status) VALUES (?,?,?,?,?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $status = $stmt->execute([$cid, $subtotal, $shipping, $shiplocation, $total, $date, $address, $status]);

            $sql = "SELECT odid FROM orders ORDER BY odid DESC LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $orders = $stmt->fetch();
            $orderid = $orders['odid'];

            $sql = "SELECT * FROM cart WHERE customerid=?";
            $stmt = $conn->prepare($sql);
            $status = $stmt->execute([$cid]);
            $products = $stmt->fetchAll();

            foreach ($products as $product) {
                $pid = $product['productid'];
                $quantity = $product['quantity'];

                $sql = "INSERT INTO orderlines (orderid,productid,quantity) VALUES (?,?,?)";
                $stmt = $conn->prepare($sql);
                $status = $stmt->execute([$orderid, $pid, $quantity]);

                $sql = "UPDATE product SET quantity=quantity-? WHERE pid=?";
                $stmt = $conn->prepare($sql);
                $status = $stmt->execute([$quantity, $pid]);

            }

            $sql = "DELETE FROM cart WHERE customerid=?";
            $stmt = $conn->prepare($sql);
            $status = $stmt->execute([$cid]);

            $conn = null;

            unset($_SESSION['subtotal']);
            unset($_SESSION['shipping']);
            unset($_SESSION['total']);
            unset($_SESSION['address']);
            unset($_SESSION['cid']);

            $_SESSION['payment_success'] = "Your Order is Successful!!";
            header("Location: customerhomepage.php");
        } else {
            $_SESSION['payment_fail'] = "Something Wrong, Check your information again!!";
        }

        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}


?>

<!DOCTYPE html>
<html>

<head>
    <title>Visa Card</title>
    <link href="./css/checkout.css" rel="stylesheet" type="text/css" />
</head>

<body>

    <?php if (isset($_SESSION['payment_fail'])) { ?>

        <div class="fail-info">
            <?php echo $_SESSION['payment_fail']; ?>
        </div>

    <?php
        unset($_SESSION['payment_fail']);
    }
    ?>

    <div class="container">
        <div class="visacard">
            <form action="./checkout.php" method="post">
                <div class="logo">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png" class="visalog" alt="visacard" />
                </div>

                <div class="card-number">
                    <label for="card-digit">Card Number</label>
                    <input type="text" name="carddigit" id="card-digit" class="form-control" placeholder="1234 1234 1234 1234" maxlength="20" required />
                    <span class="underline"></span>
                </div>

                <div class="form-group">

                    <div class="card-holder">
                        <label for="card-name">Card Name</label>
                        <input type="text" name="cardname" id="card-name" class="form-control" placeholder="Your Full Name" required />
                        <span class="underline"></span>
                    </div>

                    <div class="card-date">
                        <label for="card-exp">Exp. Date</label>
                        <input type="text" name="cardexp" id="card-exp" class="form-control" placeholder="06/24" maxlength="5" required />
                        <span class="underline"></span>
                    </div>

                    <div class="card-code">
                        <label for="card-ccv">CCV</label>
                        <input type="text" name="cardccv" id="card-ccv" class="form-control" placeholder="135" maxlength="3" required />
                        <span class="underline"></span>
                    </div>
                </div>

                <br />

                <div class="buy-info">
                    <p class="subtotal">Subtotal: $<?php echo $_SESSION['subtotal'] ?></p>
                    <p class="ship-fee">Shipping: $<?php echo $_SESSION['shipping'] ?></p>
                    <p class="total">Total: $<?php echo $_SESSION['total'] ?></p>
                </div>

                <div class="buy">
                    <button type="submit" name="buy" class="buy-btns">Buy</button>
                    <a href="./checkout.php?function=cancel" class="cancel-btns">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <!-- <script src="./js/checkout.js" type="text/javascript"></script> -->

</body>

</html>