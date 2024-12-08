<?php include('partials/menu.php'); ?>
<div class="main-content">
    <div class="wrapper">
        <h1>Change Password</h1>
        <br><br>

        <?php
        if(isset($_GET['id']))
        {
            $id=$_GET['id'];
        }



        ?>

        <form action="" method="POST">

        <table class="tbl-30">
            <tr>
                <td>Current Password: </td>
                <td>
                    <input type="password" name="current_password" placeholder="Current Password">
                </td>
            </tr>
            <tr>
                <td>New Password:</td>
                <td>
                    <input type="password" name="new_password" placeholder="New Password">
                </td>
            </tr>
            <tr>
                <td>Conform Password: </td>
                <td>
                    <input type="password" name="conform_password"placeholder="Conform Password">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <input type="submit" name="submit" value="Change Password" class="btn-secondary">
                </td>
            </tr>


        </table>
        </form>
        


    </div>

</div>
<?php 
    ///check whether the Submit Button is clicked or not
    if(isset($_POST['submit']))
    {
        //echo "CLICKED";
        //1.
        $id=$_POST['id'];
        $current_password=md5($_POST['current_password']);
        $new_password=md5($_POST['new_password']);
        $current_password=md5($_POST['conform_password']);

        //2.
        $sql="SELLECT * FROM  tbl_admin WHERE id=$id AND password='$current_password'";

        $res=mysqli_query($conn,$sql);
        if($res==true)
        {
            //check data is avilable or not
            $count=mysqli_num_rows($res);
            if($count==1)
            {
                //user exsisits and password can be changeed
                //echo "User Found";
                if($new_password==$conform_password)
                {
                   // echo "Password MATCH";
                   $sql2="UPDATE tbl_admin SET
                      password='$new_password'
                      WHERE id=$id
                   
                   ";
                   //Execute the query
                   $res2=mysqli_query($conn,$sql2);
                   if($res2==true)
                   {
                    $_SESSION['change-pwd']="<div class='success'>Password Changed Successfully. </div>";
                    header('location:'.SITEURL.'admin/manage-admin.php');


                   }
                   else
                   {
                    $_SESSION['change-pwd']="<div class='error'>Failed to change  password. </div>";
                    header('location:'.SITEURL.'admin/manage-admin.php');


                   }

                }
                else
                {
                    $_SESSION['pwd-not-match']="<div class='error'>Password Did not mached. </div>";
                    header('location:'.SITEURL.'admin/manage-admin.php');

                }

            }
            else
            {
                //user not exists
                $_SESSION['user-not-found']="<div class='error'>User Not Found. </div>";
                header('location:'.SITEURL.'admin/manage-admin.php');
            }
        }


    }

?>



<?php include('partials/footer.php'); ?>

