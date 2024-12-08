<?php 
    include('../config/constants.php');

    // Check if user is logged in
    if(!isset($_SESSION['user']))
    {
        $_SESSION['no-login-message'] = "<div class='error'>Please login to access Admin Panel.</div>";
        header('location:'.SITEURL.'admin/login.php');
        exit();
    }

    // Check if id is passed
    if(isset($_GET['id']) && isset($_GET['image_name']))
    {
        // Get the values
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        $image_name = mysqli_real_escape_string($conn, $_GET['image_name']);

        // First check and delete foods in this category
        $check_food_sql = "SELECT * FROM tbl_food WHERE category_id = $id";
        $check_food_res = mysqli_query($conn, $check_food_sql);

        if($check_food_res)
        {
            while($food = mysqli_fetch_assoc($check_food_res))
            {
                // Delete food image if exists
                if($food['image_name'] != "")
                {
                    $food_image_path = "../images/food/".$food['image_name'];
                    if(file_exists($food_image_path))
                    {
                        unlink($food_image_path);
                    }
                }
            }
            
            // Delete all foods in this category
            $delete_food_sql = "DELETE FROM tbl_food WHERE category_id = $id";
            mysqli_query($conn, $delete_food_sql);
        }

        // Delete category image if exists
        if($image_name != "")
        {
            $path = "../images/category/".$image_name;
            if(file_exists($path))
            {
                $remove = unlink($path);
                if($remove == false)
                {
                    $_SESSION['remove'] = "<div class='error'>Failed to remove category image.</div>";
                    header('location:'.SITEURL.'admin/manage-category.php');
                    exit();
                }
            }
        }

        // Delete category from database
        $sql = "DELETE FROM tbl_category WHERE id = $id";
        $res = mysqli_query($conn, $sql);

        if($res)
        {
            $_SESSION['delete'] = "<div class='success'>Category Deleted Successfully.</div>";
            header('location:'.SITEURL.'admin/manage-category.php');
            exit();
        }
        else
        {
            $_SESSION['delete'] = "<div class='error'>Failed to Delete Category.</div>";
            header('location:'.SITEURL.'admin/manage-category.php');
            exit();
        }
    }
    else
    {
        $_SESSION['delete'] = "<div class='error'>Unauthorized Access.</div>";
        header('location:'.SITEURL.'admin/manage-category.php');
        exit();
    }
?>