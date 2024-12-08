<?php include('config/constants.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Website</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <!-- Navbar Section Starts Here -->
    <section class="navbar">
        <div class="container">
            <div class="logo">
                <a href="#" title="Logo">
                    <img src="images/logo.png" alt="Restaurant Logo" class="img-responsive">
                </a>
            </div>

            <div class="menu text-right">
                <ul>
                    <li><a href="<?php echo SITEURL; ?>" class="menu-item">Home</a></li>
                    <li><a href="<?php echo SITEURL; ?>categories.php" class="menu-item">Categories</a></li>
                    <li><a href="<?php echo SITEURL; ?>foods.php" class="menu-item">Foods</a></li>
                    <li><a href="<?php echo SITEURL; ?>contact.php" class="menu-item">Contact</a></li>
                    <?php
                    if (isset($_SESSION['user'])) {
                        ?>
                        <li>Welcome <?php echo $_SESSION['user']; ?></li>
                        <li><a href="<?php echo SITEURL; ?>view-order.php" class="menu-item">View Orders</a></li>
                        <li><a href="<?php echo SITEURL; ?>logout.php" class="menu-item">Logout</a></li>
                        <?php
                    } else {
                        ?>
                        <li><a href="<?php echo SITEURL; ?>login.php" class="menu-item">Login</a></li>
                        <li><a href="<?php echo SITEURL; ?>register.php" class="menu-item">Register</a></li>
                        <?php
                    }
                    ?>
                </ul>
            </div>

            <div class="clearfix"></div>
        </div>
    </section>
    <!-- Navbar Section Ends Here -->
</body>
</html>