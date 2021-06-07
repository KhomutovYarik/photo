<?php

    require_once('connection.php');

    session_start();

    $images_array = $_POST['images'];

    $array_string = "(";

    for ($i = 0; $i < sizeof($images_array) - 1; $i++)
        $array_string .= $images_array[$i].",";

    $array_string .= $images_array[sizeof($images_array) - 1].")";

    $query = "update images set ";

    if (isset($_POST['header']))
        $query .= "header='".$_POST['header']."', ";
    if (isset($_POST['description']))
        $query .= "description='".$_POST['description']."', ";
    $query .= "permission=".$_POST['permission']." where id in ".$array_string;

    mysqli_query($connection, $query);

?>