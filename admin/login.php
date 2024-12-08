<?php include('../config/constants.php');  ?>
<html>

<head>
    <title>Login - Food Order System</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <div class="login">
        <h1 class="text-center">Login</h1>
        <br><br>
        <?php 
        if(isset($_SESSION['login']))
        {
            echo $_SESSION['login'];
            unset($_SESSION['login']);
        }

        ?>
        <br><br>

        <!--Login form -start here-->
<form action=""method="POST" class="text-center"> 
Username:  <br>
<input type="text" name="username" Placeholder="Enter Username"><br> <br>

Password: <br>
<input type="password" name="password" placeholder="Enter Password"><br> <br>

<input type="submit" name="submit" value="Login" class="btn-primary">
<br> <br>

</form>

        <!--Login form -ends here-->



        <p class="text-center">Created By - <a href="www.aynunnahermow.com ">Aynun naher mow</a></p>


    </div>
    
</body>


</html>
<?php 

//check tje submit cleak
if(isset($_POST['submit']))
{
    //process for login
    //1
    $username=$_POST['username'];
    $password=md5($_POST['password']);
    //2.
    $sql="SELECT*FROM tbl_admin WHERE username='$username' AND PASSWORD='$password'";
    //3
    $res=mysqli_query($conn,$sql);
    //4
    $count=mysqli_num_rows($res);
    if($count==1)
    {
        $_SESSION['login']="<div class='success'>Login Successfull.</div>";
        $_SESSION['user']=$username;
        header('location:'.SITEURL.'admin/');

    }
    else
    {
        $_SESSION['login']="<div class='error text-center'>Username or password did not match.</div>";
        header('location:'.SITEURL.'admin/login.php');


    }


}

?>