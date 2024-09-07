<?php
    require('connection.php');

    if(isset($_POST['id'])){
        $id=$_POST['id'];
    
        $deleteQuery="DELETE FROM crud WHERE id=$id";
        $delete=mysqli_query($conn, $deleteQuery);
    }
?>