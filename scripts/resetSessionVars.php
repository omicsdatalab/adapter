<?php
//session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["resetAllVars"])) {
        unset($_SESSION['inputFileCreated']);
        unset($_SESSION['moduleFileCreated']);
        unset($_SESSION['userModules']);

        echo '<p>Sucessfully reset session variables.</p>';
    }
}


//echo '<a href="/index.php">Back to home</a>';