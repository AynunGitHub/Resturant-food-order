<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Add Category</h1>

        <br><br>

        <?php 
        if(isset($_SESSION['add'])) {
            echo $_SESSION['add'];
            unset($_SESSION['add']);
        }

        if(isset($_SESSION['upload'])) {
            echo $_SESSION['upload'];
            unset($_SESSION['upload']);
        }
        ?>
        <br><br>

        <!-- Add Category Form -->
        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title: </td>
                    <td>
                        <input type="text" name="title" placeholder="Category Title" required>
                    </td>
                </tr>

                <tr>
                    <td>Select Image:</td>
                    <td>
                        <input type="file" name="image" required>
                    </td>
                </tr>

                <tr>
                    <td>Featured: </td>
                    <td>
                        <input type="radio" name="featured" value="Yes" required> Yes
                        <input type="radio" name="featured" value="No" required> No
                    </td>
                </tr>

                <tr>
                    <td>Active:</td>
                    <td>
                        <input type="radio" name="active" value="Yes" required> Yes
                        <input type="radio" name="active" value="No" required> No
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Category" class="btn-secondary">
                    </td>
                </tr>
            </table>
        </form>

        <?php 
        if(isset($_POST['submit'])) {

            // Sanitize inputs
            $title = mysqli_real_escape_string($conn, $_POST['title']);
            $featured = isset($_POST['featured']) ? $_POST['featured'] : "No";
            $active = isset($_POST['active']) ? $_POST['active'] : "No";

            // Handle file upload
            $image_name = "";
            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
                $image_name = $_FILES['image']['name']; 
                $ext = pathinfo($image_name, PATHINFO_EXTENSION);
                $image_name = "Food_Category_" . rand(000, 999) . '.' . $ext;
                
                $source_path = $_FILES['image']['tmp_name'];
                $destination_path = "../images/category/" . $image_name;

                // Check file type (only allow jpg, png, jpeg)
                $allowed_extensions = ['jpg', 'png', 'jpeg'];
                if (!in_array($ext, $allowed_extensions)) {
                    $_SESSION['upload'] = "<div class='error'>Invalid file type. Only JPG, PNG, and JPEG are allowed.</div>";
                    header('location:' . SITEURL . 'admin/add-category.php');
                    die();
                }

                // Check for file size (max 2MB)
                if ($_FILES['image']['size'] > 2097152) {
                    $_SESSION['upload'] = "<div class='error'>File size too large. Maximum allowed size is 25MB.</div>";
                    header('location:' . SITEURL . 'admin/add-category.php');
                    die();
                }

                // Move uploaded image
                $upload = move_uploaded_file($source_path, $destination_path);
                if ($upload == false) {
                    $_SESSION['upload'] = "<div class='error'>Failed to upload image.</div>";
                    header('location:' . SITEURL . 'admin/add-category.php');
                    die();
                }
            }

            // Insert into database using prepared statement
            $sql = "INSERT INTO tbl_category (title, image_name, featured, active) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssss", $title, $image_name, $featured, $active);

            $res = mysqli_stmt_execute($stmt);
            if ($res == true) {
                $_SESSION['add'] = "<div class='success'>Category Added Successfully.</div>";
                header('location:' . SITEURL . 'admin/manage-category.php');
            } else {
                $_SESSION['add'] = "<div class='error'>Failed to Add Category.</div>";
                header('location:' . SITEURL . 'admin/add-category.php');
            }
        }
        ?>

    </div>
</div>

<?php include('partials/footer.php'); ?>
