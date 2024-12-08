<?php include('partials/menu.php'); ?>


<?php
    //check whether id is set or not
    if(isset($_GET['id']))
    {
        //get the id and all other details
        $id = $_GET['id'];

        //sql query to get the selected food
        $sql2 = "SELECT * FROM tbl_food WHERE id=$id";
        //execute the query
        $res2 = mysqli_query($conn, $sql2);
        //get the value based on query executed
        $row2 = mysqli_fetch_assoc($res2);

        //get the individual values of selected food
        
        $title = $row2['title'];
        $description = $row2['description'];
        $price = $row2['price'];
        $current_image = $row2['image_name'];
        $current_category = $row2['category_id'];
        $featured = $row2['featured'];
        $active = $row2['active'];
    }
    else
    {
        //redirect to manage food
        header('location:'.SITEURL.'admin/manage-food.php');
    }
?>


<div class="main-content">
    <div class="wrapper">
        <h1>Update Food</h1>
        <br><br>
        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title:</td>
                    <td>
                        <input type="text" name="title" value= "<?php echo $title; ?>">
                    </td>
                </tr>
                <tr>
                    <td>Description:</td>
                    <td>
                        <textarea name="description" cols="30" rows="5" ><?php echo $description; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td>Price:</td>
                    <td>
                        <input type="number" name="price" value="<?php echo $price;?>">
                    </td>
                </tr>
                <tr>
                    <td>Current Image:</td>
                    <td>

                        <?php 
                            if($current_image == "")
                            {
                                echo "<div class='error'>Image not Added.</div>";
                            }
                            else
                            {
                                //Display the Image
                                ?>
                                <img src="<?php echo SITEURL; ?>images/food/<?php echo $current_image; ?>" width="150px" height="150px">
                            <?php
                            }
                        ?>
                        
                                      
                    
                    </td>
                </tr>
                <tr>
                    <td>Select New Image: </td>
                    <td><input type="file" name="image"></td>
                </tr>
                <tr>
                    <td>Category:</td>
                    <td>
                        <select name="category">
                            <?php
                            //Get all the category from database
                            $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
                            //Execute the query
                            $res = mysqli_query($conn, $sql);
                            //Count the rows
                            $count = mysqli_num_rows($res);
                            //Check whether have category or not
                            if($count>0)
                            {
                                //We have category
                                while($row=mysqli_fetch_assoc($res))
                                {
                                   $category_title = $row['title'];
                                   $category_id = $row['id'];

                                   //echo "<option value='$category_id'>$category_title</option>";
                                   ?>
                                   <option <?php if($current_category==$category_id){echo "selected";} ?> value=" <?php echo $category_id;?>"><?php echo $category_title; ?></option>
                                   <?php
                                }
                            }
                            else
                            {
                                //category not available
                                echo "<option value='0'>Category not available</option>";
                            }
                            
                           
                                ?>
                            <option value="0">Test Category</option>
                        </select>
                    </td>
                </tr>


                <tr>
                    <td>Featured:</td>
                    <td>                    
                        <input <?php if($featured=="Yes") {echo "checked";} ?> type="radio" name="featured" value="Yes"> Yes
                        <input <?php if($featured=="No") {echo "checked";}?>type="radio" name="featured" value="No"> No
                    </td>
                </tr>
                <tr>
                    <td>Active:</td>
                    <td>
                        <input <?php if($active=="Yes") {echo "checked";} ?> type="radio" name="active" value="Yes"> Yes
                        <input <?php if($active=="No") {echo "checked";}?> type="radio" name="active" value="No"> No
                    </td>
                    
                </tr>
                

                <tr>
                    <td colspan="2">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                         <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">

                        <input type="submit" name="submit" value="Update Food" class="btn-secondary">
                    </td>
                </tr>
            </table>
        </form>

        <?php
        if(isset($_POST['submit']))
        {
            $id=$_POST['id'];
            $title=$_POST['title'];
            $description=$_POST['description'];
            $price=$_POST['price'];
            $current_image=$_POST['current_image'];
            $category_id=$_POST['category'];
            $featured=$_POST['featured'];
            $active=$_POST['active'];

            //upload new image if selected
            if(isset($_FILES['image']['name']))
            {
                $image_name=$_FILES['image']['name'];
                //check whether the image is selected or not
                if($image_name!="")
                {
                    //image is selected
                    //get the extension of selected image
                    $ext_array = explode('.', $image_name);
                    $ext = end($ext_array);

                   # $ext=end(explode('.',$image_name));

                    //can be jpg,png,gif,etc
                    $allowed_image_ext=array('png','jpg','jpeg','gif');

                    //check image extension is valid or not
                    if(in_array($ext,$allowed_image_ext))
                    {
                        //image is valid and upload
                        //rename the image
                        $image_name="Food-Name-".rand(000,999).'.'.$ext;

                        //get the source path
                        $source_path=$_FILES['image']['tmp_name'];

                        //get the destination path
                        $destination_path="../images/category/".$image_name;
                        //chmod 755 /path/to/your/project/images/food

                        $upload = move_uploaded_file($source_path, $destination_path);


                        //upload the image
                        //$upload=move_uploaded_file($source_path,$destination_path);
                        echo realpath('../images/category/');
                        



                    

                        //check whether the image is uploaded or not
                        if($upload==false)
                        {
                            //failed to upload the image
                            $_SESSION['upload']="<div class='error'>Failed to upload new image.</div>";
                            //redirect to manage food page
                            header('location:'.SITEURL.'admin/manage-food.php');
                            die();
                        }
                        //remove current  image if available
                        if($current_image!="")
                        {
                            //remove the image
                            $remove_path="../images/category/".$current_image;
                            $remove=unlink($remove_path);
                            //check whether the image is removed or not
                            if($remove==false)
                            {
                                //failed to remove the image
                                $_SESSION['remove-failed']="<div class='error'>Failed to remove current image.</div>";
                                //redirect to manage food page
                                header('location:'.SITEURL.'admin/manage-food.php');
                                die();
                            }
                        }
                    }


                    else
                    {
                        $image_name=$current_image;//default image when image is not selected

                    }
                        
            }
            else
            {
                $image_name=$current_image;//default image when butten is not clicked
            }


            //4. Update the food in database
            $sql3="UPDATE tbl_food SET
            title='$title',
            description='$description',
            price=$price,
            image_name='$image_name',
            category_id=$category_id,
            featured='$featured',
            active='$active'
            WHERE id=$id
            ";



            //execute the query
            $res3=mysqli_query($conn,$sql3);


            //check whether the query is executed or not
            if($res3==true)
            {
                //query executed and food updated
                $_SESSION['update']="<div class='success'>Food updated successfully.</div>";
                //redirect to manage food with session message
                header('location:'.SITEURL.'admin/manage-food.php');
                die();
            }
            else
            {
                //failed to update food
                $_SESSION['update']="<div class='error'>Failed to update food.</div>";
                //redirect to manage food with session message
                header('location:'.SITEURL.'admin/manage-food.php');
                die();
            }
        }
        else
        {
            //redirect to manage food with session message
            $_SESSION['unauthorize']="<div class='error'>Unauthorized Access.</div>";
            //redirect to manage food with session message


            }
        }
        ?>
    </div>

</div>



            </table>



        </form>


        
<?php include('partials/footer.php'); 
ob_end_flush(); // End output buffering
?>
