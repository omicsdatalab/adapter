<?php
require 'header.php';
require 'navbar.php';
session_start();
if(empty($_SESSION['moduleFileCreated'])) {
    $_SESSION['moduleFileCreated'] = false;
}
if(empty($_SESSION['inputFileCreated'])) {
    $_SESSION['inputFileCreated'] = false;
}
?>
<br>
<div class="container">
    <div class="card">
        <div class="card-body">
            <div>
                <h5>Default Build</h5>
                <form method="get" action="scripts/defaultBuild.php">
                    <button class="btn btn-secondary" type="submit">Download</button>
                </form>
            </div>
            <?php if ($_SESSION['inputFileCreated']): ?>
            <hr>
            <div>
                <h5>Input File</h5>
                <form method="get" action="scripts/userInputFile.php">
                    <button class="btn btn-secondary" type="submit">Download</button>
                </form>
            </div>
            <?php endif; ?>
            <?php if ($_SESSION['moduleFileCreated']): ?>
            <hr>
            <div>
                <h5>Module File With User-Added Modules</h5>
                <form method="get" action="scripts/userModuleFile.php">
                    <button class="btn btn-secondary" type="submit">Download</button>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>