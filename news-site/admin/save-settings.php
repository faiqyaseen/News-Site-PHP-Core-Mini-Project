<?php 
include "config.php";
if(isset($_POST['submit'])){

    if(empty($_FILES['new_logo']['name'])){
       $file_name = $_POST['old_logo'];
    }
    else{

        // Delete old image
        $sql1 = "SELECT * FROM settings WHERE id = {$_POST['set_id']}";
        $result = mysqli_query($conn,$sql1);
        $row = mysqli_fetch_assoc($result);
        unlink("images/".$row['logo']);


        // save new image
        $errors = array();

        $file_name = $_FILES['new_logo']['name'];
        $file_size = $_FILES['new_logo']['size'];
        $file_tmp = $_FILES['new_logo']['tmp_name'];
        $file_type = $_FILES['new_logo']['type'];
        $file_tmp = $_FILES['new_logo']['tmp_name'];
        $file_ext = strtolower(end(explode('.',$file_name)));

        $extesions = array("jpeg","jpg","png");

        if(in_array($file_ext,$extesions) === false){
            $errors[] = "This extension file is not allowed, please choose a jpg or jpeg or png file";
        }
        if($file_size > 2097152 ){
            $errors[] = "File must be 2mb or lower.";
        }
        if(empty($errors) == true){
            move_uploaded_file($file_tmp,"images/".$file_name);
        }else{
            print_r($errors);
            die();
        }
    }

    
        $sql = "UPDATE settings SET websitename='{$_POST['website_name']}',logo='{$file_name}',
        footerdesc='{$_POST['footerdesc']}'WHERE id = {$_POST['set_id']}";

    $result = mysqli_query($conn,$sql);
    if($result){
        header("Location:{$hostname}/admin/setting.php");
    }
    else{
        echo "Query Failed!";
    }
    }
