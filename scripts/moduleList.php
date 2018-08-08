<?php
require '../models/Module.php';
session_start();
echo json_encode(array_values($_SESSION["userModules"]));
