<?php
session_start();
if(!empty($_SESSION['inputFileCreated'])) {
    unset($_SESSION['inputFileCreated']);
}

if(!empty($_SESSION['moduleFileCreated'])) {
    unset($_SESSION['moduleFileCreated']);
}

if(!empty($_SESSION['userModules'])) {
    unset($_SESSION['userModules']);
}

echo '<a href="/index.php">Back to home</a>';