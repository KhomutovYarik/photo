<?php

    require_once('connection.php');

    session_start();

    $images_array = $_POST['images'];
    $num_album = $_POST['album_id'];

    $array_string = "(";

    for ($i = 0; $i < sizeof($images_array) - 1; $i++)
        $array_string .= $images_array[$i].",";

    $array_string .= $images_array[sizeof($images_array) - 1].")";

    $query = "update images set album_id=".$num_album." where id in ".$array_string;

    mysqli_query($connection, $query);
    
?>