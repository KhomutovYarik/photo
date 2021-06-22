<?php

    require_once('connection.php');

    session_start();

    if ($_SESSION['auth'])
    {

        $curDate = date('Y-m-d h:i:s');

        $query = "insert into comments (user_id, image_id, text, create_date) values (".$_SESSION['id'].", ".$_POST['image_id'].", '".$_POST['comment_text']."', '".$curDate."')";

        mysqli_query($connection, $query);

        $num_comment = $connection->insert_id;

        $curDate = date('d-m-Y h:i');

        $return_data = [$num_comment, $_SESSION['username'], $curDate];

        echo json_encode($return_data);
        
    }
    
?>