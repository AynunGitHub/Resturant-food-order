<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Update Category</h1>

        <br><br>

        <?php 
        // Check if ID is set
        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            // Get category details
            $sql = "SELECT * FROM tbl_category WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);

            if ($res && mysqli_num_rows($res) == 1) {
                $row = mysqli_fetch_assoc($res);
                $title = $row['title'];
                $current_image = $row['image_name'];
                $featured = $row['featured'];
                $active = $row['active'];
            } else {
                $_SESSION['no-category-found'] = "<div class='error'>Category Not Found.</div>";
                header('location:' . SITEURL . 'admin/manage-category.php');
                die();
            }
        } else {
            header('location:' . SITEURL . 'admin/manage-category.php');
            die();
        }
        ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title: </td>
                    <td>
                        <input type="text" name="title" value="<?php echo $title; ?>" required>
                    </td>
                </tr>

                <tr>
                    <td>Current Image: </td>
                    <td>
                        <?php 
                        if ($current_image != "") {
                            echo "<img src='../images/category/$current_image' width='150px'>";
                        } else {
                            echo "<div class='error'>Image Not Added.</div>";
                        }
                        ?>
                    </td>
                </tr>

                <tr>
                    <td>New Image:</td>
                    <td>
                        <input type="file" name="image">
                    </td>
                </tr>

                <tr>
                    <td>Featured: </td>
                    <td>
                        <input type="radio" name="featured" value="Yes" <?php if ($featured == "Yes") echo "checked"; ?>> Yes
                        <input type="radio" name="featured" value="No" <?php if ($featured == "No") echo "checked"; ?>> No
                    </td>
                </tr>

                <tr>
                    <td>Active: </td>
                    <td>
                        <input type="radio" name="active" value="Yes" <?php if ($active == "Yes") echo "checked"; ?>> Yes
                        <input type="radio" name="active" value="No" <?php if ($active == "No") echo "checked"; ?>> No
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">
                        <input type="submit" name="submit" value="Update Category" class="btn-secondary">
                    </td>
                </tr>
            </table>
        </form>

        <?php 
        if (isset($_POST['submit'])) {

            // Get form data
            $id = $_POST['id'];
            $title = mysqli_real_escape_string($conn, $_POST['title']);
            $current_image = $_POST['current_image'];
            $featured = $_POST['featured'];
            $active = $_POST['active'];

            // Handle new image upload
            $image_name = $current_image;
            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
                $image_name = $_FILES['image']['name'];
                $ext = pathinfo($image_name, PATHINFO_EXTENSION);
                $image_name = "Food_Category_" . rand(000, 999) . '.' . $ext;

                $source_path = $_FILES['image']['tmp_name'];
                $destination_path = "../images/category/" . $image_name;

                // Check file type and size
                $allowed_extensions = ['jpg', 'png', 'jpeg'];
                if (!in_array($ext, $allowed_extensions)) {
                    $_SESSION['upload'] = "<div class='error'>Invalid file type. Only JPG, PNG, and JPEG are allowed.</div>";
                    header('location:' . SITEURL . 'admin/update-category.php?id=' . $id);
                    die();
                }

                if ($_FILES['image']['size'] > 26214400) {
                    $_SESSION['upload'] = "<div class='error'>File size too large. Maximum allowed size is 25MB.</div>";
                    header('location:' . SITEURL . 'admin/update-category.php?id=' . $id);
                    die();
                }

                // Move uploaded file
                $upload = move_uploaded_file($source_path, $destination_path);
                if ($upload == false) {
                    $_SESSION['upload'] = "<div class='error'>Failed to upload image.</div>";
                    header('location:' . SITEURL . 'admin/update-category.php?id=' . $id);
                    die();
                }

                // Remove old image
                if ($current_image != "") {
                    $remove_path = "../images/category/" . $current_image;
                    if (!unlink($remove_path)) {
                        $_SESSION['remove'] = "<div class='error'>Failed to remove old image.</div>";
                        header('location:' . SITEURL . 'admin/manage-category.php');
                        die();
                    }
                }
            }

            // Update database
            $sql = "UPDATE tbl_category SET title = ?, image_name = ?, featured = ?, active = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssssi", $title, $image_name, $featured, $active, $id);

            $res = mysqli_stmt_execute($stmt);
            if ($res == true) {
                $_SESSION['update'] = "<div class='success'>Category Updated Successfully.</div>";
                header('location:' . SITEURL . 'admin/manage-category.php');
            } else {
                $_SESSION['update'] = "<div class='error'>Failed to Update Category.</div>";
                header('location:' . SITEURL . 'admin/update-category.php?id=' . $id);
            }
        }
        ?>

    </div>
</div>

<?php include('partials/footer.php'); ?>
