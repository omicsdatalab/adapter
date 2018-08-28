<?php
require './header.php';
require './navbar.php';
session_start();
require './scripts/resetSessionVars.php';
?>
<div class="container">
    <br>
    <div class="row">
        <div class="col"></div>
        <div class="col-10">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Welcome to Bioliner</h4>
                    <div class="card-text">
                        <p>
                            Bioliner is a command line application used to facilitate a 'pipeline' of bioinformatics jobs.
                            Each pipeline has a list of modules to execute sequentially. Bioliner has several modules
                            included and the details on these can be found on the Modules page.
                        </p>
                        <hr>
                        <p class="h5">Workflows</p>
                        <p>
                            The Workflows page can be used to generate a file used by Bioliner to describe a job pipeline.
                            Each workflow has a run name and an output folder. Each 'Module' in the workflow has an input file,
                            output file and parameters.
                        </p>
                        <hr>
                        <p class="h5">Modules</p>
                        <p>
                            Each module has the following fields:
                        </p>
                        <ul>
                            <li>Name</li>
                            <li>Category</li>
                            <li>Description</li>
                            <li>Input File</li>
                            <li>Input is a parameter</li>
                            <li>Output file required</li>
                            <li>Output File</li>
                            <li>Output is a parameter</li>
                            <li>Command</li>
                            <li>Parameters</li>
                        </ul>
                        <p>
                            In the context of a module, input and output files are simply describing the file format(s),
                            while in the workflow generator these will be the actual names of the files to be used.
                        </p>
                        <p>
                            If you wish to add a new module to Bioliner, you can use the Modules page to create a new module file.
                            A readme containing further instructions on how to integrate your module is included in the bioliner.zip.
                            This can be found on the downloads page.
                        </p>
                    </div>
                    <hr>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <button class="btn btn-primary" name="resetAllVars" type="submit">Reset Session</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col"></div>
    </div>

</div>
<br>
