<?php
//session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["resetModuleVars"])) {
        unset($_SESSION['moduleFileCreated']);
        unset($_SESSION['userModules']);
//        $_SESSION['userModules'] = array();
        echo '<p>Sucessfully reset session variables.</p>';
    }
}

//echo '<a href="../Modules.php">Back to Modules</a>';