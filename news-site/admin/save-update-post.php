<?php
include "config.php";
if (isset($_POST['submit'])) {

    if (empty($_FILES['new_image']['name'])) {
        $new_image = $_POST['old_image'];
    } else {

        // Delete old image
        $sql1 = "SELECT * FROM post WHERE post_id = {$_POST['post_id']}";
        $result = mysqli_query($conn, $sql1);
        $row = mysqli_fetch_assoc($result);
        unlink("upload/" . $row['post_img']);


        // save new image
        $errors = array();

        $file_name = $_FILES['new_image']['name'];
        $file_size = $_FILES['new_image']['size'];
        $file_tmp = $_FILES['new_image']['tmp_name'];
        $file_type = $_FILES['new_image']['type'];
        $file_tmp = $_FILES['new_image']['tmp_name'];
        $file_ext = strtolower(end(explode('.', $file_name)));

        $extesions = array("jpeg", "jpg", "png");

        if (in_array($file_ext, $extesions) === false) {
            $errors[] = "This extension file is not allowed, please choose a jpg or jpeg or png file";
        }
        if ($file_size > 2097152) {
            $errors[] = "File must be 2mb or lower.";
        }
        $new_image = time()."-".basename($file_name);
        if (empty($errors) == true) {
            move_uploaded_file($file_tmp, "upload/" . $new_image);
        } else {
            print_r($errors);
            die();
        }
    }

    // if a user update the category of post // posts of category subtract and addition

    if ($_POST['old_category'] != $_POST['new_category']) {


        $sql = "UPDATE post SET title='{$_POST['post_title']}',description='{$_POST['postdesc']}',
        category={$_POST['new_category']},post_img='$new_image' WHERE post_id = {$_POST['post_id']};";

        $sql .= "UPDATE category SET post = post - 1 WHERE category_id = {$_POST['old_category']};";

        $sql .= "UPDATE category SET post = post + 1 WHERE category_id = {$_POST['new_category']}";
    } elseif ($_POST['old_category'] == $_POST['new_category']) {
        $sql = "UPDATE post SET title='{$_POST['post_title']}',description='{$_POST['postdesc']}',
        category={$_POST['new_category']},post_img='$new_image' WHERE post_id = {$_POST['post_id']}";
    }
    $result = mysqli_multi_query($conn, $sql);
    if ($result) {
        header("Location:{$hostname}/admin/post.php");
    } else {
        echo "Query Failed!";
    }
}
