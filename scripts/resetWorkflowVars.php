<?php
session_start();
if(!empty($_SESSION['inputFileCreated'])) {
    unset($_SESSION['inputFileCreated']);
}

echo '<a href="../Workflows.php">Back to Workflows</a>';