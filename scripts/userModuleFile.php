<?php
session_start();
$inputFile = "../uploads" . DIRECTORY_SEPARATOR . session_id() . DIRECTORY_SEPARATOR . "module.xml";
if (file_exists($inputFile) && is_file($inputFile)) {

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($inputFile).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($inputFile));
    if (ob_get_level()) {
        ob_end_clean();
    }
    readfile($inputFile);
    $_SESSION['moduleFileCreated'] = false;
    exit;
} else {
    echo "Error downloading file";
}