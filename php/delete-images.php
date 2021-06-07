<?php

    require_once('connection.php');

    session_start();

    $images_array = $_POST['images'];

    $array_string = "(";

    for ($i = 0; $i < sizeof($images_array) - 1; $i++)
        $array_string .= $images_array[$i].",";

    $array_string .= $images_array[sizeof($images_array) - 1].")";

    $search = "select id, extension from all_images where id in ".$array_string;

    $result = mysqli_query($connection, $search);

    $query = "delete from images where id in ".$array_string;

    mysqli_query($connection, $query);

    for ($i = 0; $i < mysqli_num_rows($result); $i++)
    {
        $row = mysqli_fetch_row($result);
        unlink('../uploaded/'.$_SESSION['id'].'/'.$row[0].'.'.$row[1]);
    }

?>