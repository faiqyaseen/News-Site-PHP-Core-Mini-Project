<?php

    include "config.php";
    if(isset($_POST['submit'])){

        if(isset($_FILES['fileToUpload'])){
            $errors = array();

            $file_name = $_FILES['fileToUpload']['name'];
            $file_size = $_FILES['fileToUpload']['size'];
            $file_tmp = $_FILES['fileToUpload']['tmp_name'];
            $file_type = $_FILES['fileToUpload']['type'];
            $file_tmp = $_FILES['fileToUpload']['tmp_name'];
            $file_ext = strtolower(end(explode('.',$file_name)));
            $extesions = array("jpeg","jpg","png");

            if(in_array($file_ext,$extesions) === false){
                $errors[] = "This extension file is not allowed, please choose a jpg or jpeg or png file";
            }
            if($file_size > 2097152 ){
                $errors[] = "File must be 2mb or lower.";
            }

            $new_image = time()."-".basename($file_name);
            // $tareget = "upload/".$new_image;
            // $image_name = $new_image;
            if(empty($errors) == true){
                move_uploaded_file($file_tmp,"upload/".$new_image);
            }else{
                print_r($errors);
                die();
            }
        }
        session_start();
        $title = mysqli_real_escape_string($conn,$_POST['post_title']);
        $description = mysqli_real_escape_string($conn,$_POST['postdesc']);
        $category = mysqli_real_escape_string($conn,$_POST['category']);
        $date = date("d M, Y");
        $author = $_SESSION['user_id'];

        $sql = "INSERT INTO post(title, description, category, post_date, author, post_img)
                 VALUES ('{$title}','{$description}',{$category},'{$date}',{$author},'{$new_image}');";
        $sql .= "UPDATE category SET post = post + 1 WHERE category_id = {$category}";
        if(mysqli_multi_query($conn,$sql)){
            header("Location:{$hostname}/admin/post.php");
        }else{
            echo "<div class='alert alert-danger'>Query Failed</div>";
        }
    }


?>