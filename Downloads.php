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
            <h5 class="card-title">System Requirements</h5>
            <p class="card-text">Bioliner requires Java 8 to run, and around 1.2GB of free disk space.</p>
            <div class="card-deck">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Default Build</h5>
                        <p class="card-text">The default build comes with a set of example workflows, and the default module file.</p>
                    </div>
                    <div class="card-footer">
                        <form method="get" action="scripts/defaultBuild.php">
                            <button class="btn btn-secondary btn-block" type="submit">Download</button>
                        </form>
                    </div>
                </div>

                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Input File</h5>
                        <?php if (!$_SESSION['inputFileCreated']): ?>
                        <p class="card-text">You can generate an input file on the workflows page.</p>
                        <?php else: ?>
                            <p class="card-text">You can download the generated input file below.</p>
                        <?php endif;?>
                    </div>
                    <div class="card-footer">
                        <form method="get" action="scripts/userInputFile.php">
                            <button class="btn btn-secondary btn-block" type="submit"
                                    <?php if (!$_SESSION['inputFileCreated']): ?>disabled<?php endif;?>>Download</button>
                        </form>
                    </div>
                </div>
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Module File</h5>
                        <?php if ($_SESSION['moduleFileCreated']): ?>
                            <p class="card-text">With user-added modules</p>
                        <?php endif; ?>
                        <?php if (!$_SESSION['moduleFileCreated']): ?>
                            <p class="card-text">You can generate a module file on the workflows page.</p>
                            <p><small>A default module file is included with Bioliner.</small></p>
                        <?php else: ?>
                            <p class="card-text">You can download the generated module file below.</p>
                        <?php endif;?>
                    </div>
                    <div class="card-footer">
                        <form method="get" action="scripts/userModuleFile.php">
                            <button class="btn btn-secondary btn-block" type="submit"
                                    <?php if (!$_SESSION['moduleFileCreated']): ?>disabled<?php endif;?>>Download</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>