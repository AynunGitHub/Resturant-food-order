<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Add Admin</h1>
        <br><br>
        <?php
             if(isset($_SESSION['add']))//checking where the session is set or not
             {
                echo $_SESSION['add'];//display session massage if set
                unset($_SESSION['add']);//Remove session massage
             }
        
        ?>


        <form action=""method="POST">

            <table class="tbl-30">
                <tr>
                    <td>Full Name:</td>
                    <td>
                        <input type="text" name="full_name"placeholder="Enter your name">
                </td>
                </tr>
                <tr>
                    <td>Username:</td>
                    <td>
                        <input type="text" name="username" placeholder="Your username">

                    </td>
                </tr>
                <tr>
                    <td>Password: </td>
                    <td>
                        <input type="password" name="password" placeholder="Your Password">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Admin" class="btn-secondary">
                    </td>
                </tr>
            </table>  
        </form>


    </div>
</div>

<?php include('partials/footer.php'); ?>

<?php
// process the value from and save it in database
//check where the submit button is clicked or not 
     if(isset($_POST['submit']))
     {
        //Button Clicked 
        //echo "Button clicked";
        //1. get the data from form
        $full_name=$_POST['full_name'];
        $username=$_POST['username'];
        $password=md5($_POST['password']);//password Encription with md5
        //2. SqL query to save the data into database
        $sql="INSERT INTO tbl_admin SET
        FULL_NAME='$full_name',
        username='$username',
        password='$password'
        
        ";

        //3. Executing Query and saving data into database
        $res=mysqli_query($conn,$sql) or die(mysqli_error());

        //4. checked whether the (Query is Executed) data is inserted or not and display approptiate message
        if($res==TRUE)
        {
            //Data Inserted
            //echo "Data Inserted";
            //create a session variable to display massage
            $_SESSION['add']="Admin Added successfully";
            //Redirect page TO mANAGE aDMIN
            header("location:".SITEURL.'admin/manage-admin.php');
        }

       else
       {
       
       //Failed to Insert Data
            //echo "Faile to insert Data ";
            $_SESSION['add']="Failed to Add Admin";
            //Redirect page TO add aDMIN
            header("location:".SITEURL.'admin/add-admin.php');
        }

     }
 ?>

