<?php
if (isset($_POST["PHPSESSID"])) {
        session_id($_POST["PHPSESSID"]);
}
session_start();

$dirtemp = "../../icones";

if (move_uploaded_file($_FILES['Filedata']['tmp_name'], $dirtemp.'/'.$_FILES["Filedata"]["name"])) {
    echo "SWFUpload File Saved: ".$_FILES["Filedata"]["name"];
} else {
    echo "SWFUpload File Not Saved: ".$_FILES["Filedata"]["name"];
}

?>