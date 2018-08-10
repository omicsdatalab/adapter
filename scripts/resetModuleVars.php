<?php
session_start();
if(!empty($_SESSION['moduleFileCreated'])) {
    unset($_SESSION['moduleFileCreated']);
}

if(!empty($_SESSION['userModules'])) {
    unset($_SESSION['userModules']);
}

echo '<a href="../Modules.php">Back to Modules</a>';