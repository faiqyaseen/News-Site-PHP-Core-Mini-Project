<?php include "config.php";
   if($_SESSION["user_role"]==0){
    header("Location:{$hostname}/admin/post.php");
}

   
   $id = $_GET['id'];

    $sql = "DELETE FROM category WHERE category_id = {$id}";

    if(mysqli_query($conn,$sql)){
        header("Location:{$hostname}/admin/category.php");
    }
    else{
        echo "<p style='color:red; text-align:center; margin:10px 0;'>Can't Delete</p>"; 
    }

?>