<?php
require_once '../include/config.php';

$sql = "DELETE FROM minuman WHERE id ='".$_GET['id']."'";
echo $sql;

if (mysqli_query($conn,$sql)){
    header('Location:../menu.php');
}else{
    echo "Error: ". mysqli_error($conn);

    mysqli_close($conn);
}


?>