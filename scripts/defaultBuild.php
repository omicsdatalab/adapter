<?php
session_start();
$file = "../zip-files/bioliner.zip";
$rootPath = realpath("../zip-files/bioliner.zip");

if (file_exists($rootPath) && is_file($rootPath)) {

    header('Content-Description: File Transfer');
    header("Content-Type: application/zip");
    header('Content-Disposition: attachment; filename="'.basename($rootPath).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($rootPath));
    if (ob_get_level()) {
        ob_end_clean();
    }
    readfile($file);
    exit;
} else {
    echo "Invalid file";
}