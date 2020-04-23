<?php

require_once('session_stub.php');
php_session_start();
php_auth_restaurant_only();

function php_handle_upload_thumbnail() {
    $allowedExts = array("jpeg", "jpg");
    $temp = explode(".", $_FILES["file"]["name"]);
    $extension = end($temp);

    if ((($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/pjpeg")) && ($_FILES["file"]["size"] < 512000) && in_array($extension, $allowedExts)) {
        if ($_FILES["file"]["error"] > 0) {
            return $_FILES["file"]["error"];
        } else {
            $uid = php_get_uid();
          //  chmod("../img/restaurant/" . $uid . "/", 0777); 
            move_uploaded_file($_FILES["file"]["tmp_name"], "../img/restaurant/" . $uid . "/1.jpg");
            return "success";
        }
    } else {
        return "Invalid file type, only support JPEG file";
    }
}

######################################################################

$result = php_handle_upload_thumbnail();
if ($result == 'success') {
    header('Location:'.URL.'operation_success.php');
} else {
    header('Location:'.URL.'operation_failed.php?reason='.htmlentities($result));
}
return;
?>