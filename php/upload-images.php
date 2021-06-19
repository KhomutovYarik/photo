<?php

require_once('connection.php');

session_start();

$curDate = date('Y-m-d h:i:s');

$images_id_array = [];

for ($i = 0; $i < $_POST['files_length']; $i++)
{
    if (isset($_FILES['file_'.$i]))
    {
        $path_parts = pathinfo($_FILES['file_'.$i]["name"]);

        $fileext = $path_parts['extension'];
        $filename = $path_parts['filename'];
        $filesize = $FILES["file"]["size"]/1024;
        $user_id = $_SESSION['id'];

        $query = "select id from extensions where name = '$fileext'";

        $result = mysqli_query($connection, $query);

        $row_count = mysqli_num_rows($result);

        $ext_id = mysqli_fetch_row($result)[0];

        if ($row_count > 0)
        {
            $query = "insert into images (user_id, extension_id, header, create_date, permission) values ($user_id, $ext_id, '$filename', '$curDate', 3)";

            mysqli_query($connection, $query);

            $new_image_id = $connection->insert_id;

            array_push($images_id_array, $new_image_id);

            $location = $_SERVER['DOCUMENT_ROOT'].'/uploaded/'.$_SESSION['id'].'/';

            if (!file_exists($location))
            {
                mkdir($location, 0777, true);
            }

            $location .= $new_image_id.'.'.$fileext;

            move_uploaded_file($_FILES['file_'.$i]["tmp_name"], $location);
        }
    }
}

$cur_date = date('d-m-Y');

$return_array = [$images_id_array, $cur_date];

echo json_encode($return_array);

?>