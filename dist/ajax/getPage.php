<?php
require_once '../includes/common.php';

$filename = sanitizeString($_GET['filename']);

//It is possible that the page won’t exist (for example when getting an interaction page that doesn’t need any other content besides the interaction itself.) So, first check to see if the page exists.

if (file_exists(APP_ROOT . $filename)) {
    echo file_get_contents(APP_ROOT . $filename);
}
