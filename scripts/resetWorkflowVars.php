<?php
//session_start();
$errMsg = "<p>Error attempting to reset session variables</p>";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["resetWfVars"])) {
        if(!empty($_SESSION['inputFileCreated'])) {
            unset($_SESSION['inputFileCreated']);
        }

        echo '<p>Sucessfully reset session variables.</p>';
    }
}

