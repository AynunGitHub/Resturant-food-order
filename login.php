<?php include('partials-front/menu.php'); ?>

<section class="login">
    <div class="container">
        <h2 class="text-center">Login</h2>
        
        <?php 
            if(isset($_SESSION['login']))
            {
                echo $_SESSION['login'];
                unset($_SESSION['login']);
            }
            if(isset($_SESSION['register']))
            {
                echo $_SESSION['register'];
                unset($_SESSION['register']);
            }
        ?>

        <form action="" method="POST" class="text-center">
            <div class="form-group">
                <input type="text" name="username" placeholder="Enter Username" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Enter Password" required>
            </div>
            <input type="submit" name="submit" value="Login" class="btn btn-primary">
            <p>Don't have an account? <a href="<?php echo SITEURL; ?>register.php">Register here</a></p>
        </form>
    </div>
</section>

<?php 
    // Process login form
    if(isset($_POST['submit']))
    {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        $sql = "SELECT * FROM tbl_users WHERE username='$username'";
        $res = mysqli_query($conn, $sql);

        if(mysqli_num_rows($res) == 1)
        {
            $row = mysqli_fetch_assoc($res);
            if(password_verify($password, $row['password']))
            {
                // Login successful
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user'] = $row['username'];
                header('location:'.SITEURL);
            }
            else
            {
                $_SESSION['login'] = "<div class='error'>Username or Password did not match.</div>";
                header('location:'.SITEURL.'login.php');
            }
        }
        else
        {
            $_SESSION['login'] = "<div class='error'>Username or Password did not match.</div>";
            header('location:'.SITEURL.'login.php');
        }
    }
?>

<?php include('partials-front/footer.php'); ?>