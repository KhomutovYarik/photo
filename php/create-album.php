<?php

    require_once('connection.php');

    session_start();

    $images_array = $_POST['images'];

    $array_string = "(";

    for ($i = 0; $i < sizeof($images_array) - 1; $i++)
        $array_string .= $images_array[$i].",";

    $array_string .= $images_array[sizeof($images_array) - 1].")";

    $curDate = date('Y-m-d h:i:s');

    if (isset($_POST['description']))
        $query = "insert into albums (name, description, create_date, permission) values ('".$_POST['name']."', '".$_POST['description']."', '".$curDate."', ".$_POST['permission'].")";
    else
        $query = "insert into albums (name, create_date, permission) values ('".$_POST['name']."', '".$curDate."', ".$_POST['permission'].")";

    mysqli_query($connection, $query);

    $num_album = $connection->insert_id;

    $query = "update images set album_id=".$num_album." where id in ".$array_string;

    mysqli_query($connection, $query);
    
?>