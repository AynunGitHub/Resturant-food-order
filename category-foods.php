<?php include('partials-front/menu.php'); ?>

<?php 
    //Check whether id is passed or not
    if(isset($_GET['category_id']))
    {
        //Category id is set
        $category_id = mysqli_real_escape_string($conn, $_GET['category_id']);
        
        //Get the category title based on category ID
        $sql = "SELECT title FROM tbl_category WHERE id=$category_id AND active='Yes'";
        $res = mysqli_query($conn, $sql);

        //Get the value from database
        if(mysqli_num_rows($res) > 0)
        {
            $row = mysqli_fetch_assoc($res);
            //Get the title
            $category_title = $row['title'];
        }
        else
        {
            //Category not found
            header('location:'.SITEURL);
            exit();
        }
    }
    else
    {
        //Category id not passed
        header('location:'.SITEURL);
        exit();
    }
?>

<!-- fOOD sEARCH Section Starts Here -->
<section class="food-search text-center">
    <div class="container">
        <h2>Foods on <a href="#" class="text-white">"<?php echo htmlspecialchars($category_title); ?>"</a></h2>
    </div>
</section>
<!-- fOOD sEARCH Section Ends Here -->

<!-- fOOD MEnu Section Starts Here -->
<section class="food-menu">
    <div class="container">
        <h2 class="text-center">Food Menu</h2>

        <?php 
            //Get foods based on selected category
            $sql2 = "SELECT * FROM tbl_food WHERE category_id=$category_id AND active='Yes'";
            $res2 = mysqli_query($conn, $sql2);
            $count2 = mysqli_num_rows($res2);

            //Check whether food is available or not
            if($count2 > 0)
            {
                while($row2 = mysqli_fetch_assoc($res2))
                {
                    $id = $row2['id'];
                    $title = $row2['title'];
                    $price = $row2['price'];
                    $description = $row2['description'];
                    $image_name = $row2['image_name'];
                    ?>
                    <div class="food-menu-box">
                        <div class="food-menu-img">
                            <?php 
                                if($image_name == "")
                                {
                                    echo "<div class='error'>Image not Available.</div>";
                                }
                                else
                                {
                                    ?>
                                    <img src="<?php echo SITEURL; ?>images/food/<?php echo htmlspecialchars($image_name); ?>" 
                                         alt="<?php echo htmlspecialchars($title); ?>" 
                                         class="img-responsive img-curve">
                                    <?php
                                }
                            ?>
                        </div>

                        <div class="food-menu-desc">
                            <h4><?php echo htmlspecialchars($title); ?></h4>
                            <p class="food-price">$<?php echo htmlspecialchars($price); ?></p>
                            <p class="food-detail">
                                <?php echo htmlspecialchars($description); ?>
                            </p>
                            <br>
                            <form action="<?php echo SITEURL; ?>order.php" method="GET">
                                <input type="hidden" name="food_id" value="<?php echo $id; ?>">
                                <input type="submit" class="btn btn-primary" value="Order Now">
                            </form>
                        </div>
                    </div>
                    <?php
                }
            }
            else
            {
                echo "<div class='error'>Food not Available.</div>";
            }
        ?>

        <div class="clearfix"></div>
    </div>
</section>
<!-- fOOD Menu Section Ends Here -->

<?php include('partials-front/footer.php'); ?>