<?php 
//Include constants.php file here
include('../config/constants.php');

//echo "Delete Food Page";
if(isset($_GET['id']) && isset($_GET['image_name']))
{
    //process to delete
    //echo "Process to Delete";
    //get id and image name
    $id=$_GET['id'];
    $image_name=$_GET['image_name'];

    //remove image if available
    if($image_name!="")
    {
        //image is available so remove it
        $path="../images/food/".$image_name;
        //remove image
        $remove=unlink($path);

        //check whether the image is removed or not
        if($remove==false)
        {
            //failed to remove image
            $_SESSION['upload']="<div class='error'>Failed to Remove Image</div>";
            //Redirect to Manage Food Page
            header('location:'.SITEURL.'admin/manage-food.php');
            die();
        }
    }

    //delete data from database
    //sql query to delete food
    $sql="DELETE FROM tbl_food WHERE id=$id";

    //execute the query
    $res=mysqli_query($conn,$sql);

    //check whether the query executed or not
    if($res==true)
    {
        //food deleted
        $_SESSION['delete']="<div class='success'>Food Deleted Successfully</div>";
        //redirect to Manage Food Page
        header('location:'.SITEURL.'admin/manage-food.php');

}
else
{
    //failed to delete food
    $_SESSION['delete']="<div class='error'>Failed to Delete Food</div>";
    //redirect to Manage Food Page
    header('location:'.SITEURL.'admin/manage-food.php');
}

}

else
{
    //redirect to manage food page
   // echo "Redirect to Manage Food Page";
   $_SESSION['unauthorized']="<div class='error'>Unauthorized Access</div>";
   header('location:'.SITEURL.'admin/manage-food.php');

}


?>              