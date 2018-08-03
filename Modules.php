<?php
/**
 * Created by PhpStorm.
 * User: Josh
 * Date: 31/07/2018
 * Time: 11:45
 */
require 'header.php';
require 'navbar.php';
require 'Module.php';
session_start();
$userModule = null;
require 'addModule.php';

$xml = simplexml_load_file("module.xml") or die("Error: Cannot create xml object.");
$modules = array();

if(empty($_SESSION['userModules'])) {
    $_SESSION['userModules'] = array();
    $modules = $_SESSION['userModules'];
    foreach($xml->children() as $moduleFromXml) {
        $moduleToAdd = new Module();
        $moduleToAdd->name = (string)$moduleFromXml->name;
        $moduleToAdd->category = (string)$moduleFromXml->category;
        $moduleToAdd->description = (string)$moduleFromXml->description;
        $moduleToAdd->inputFile = (string)$moduleFromXml->inputFile;
        $moduleToAdd->inputParam = (string)$moduleFromXml->inputParam;
        $moduleToAdd->outputFile_required = (string)$moduleFromXml->outputFile_required;
        $moduleToAdd->outputFile = (string)$moduleFromXml->outputFile;
        $moduleToAdd->outputParam = (string)$moduleFromXml->outputParam;
        $moduleToAdd->params = (string)$moduleFromXml->params;
        $moduleToAdd->command = (string)$moduleFromXml->command;
        $modules[] = $moduleToAdd;
        $_SESSION["userModules"][] = $moduleToAdd;
    }
} else {
    $modules = $_SESSION['userModules'];
}
?>
    <br>
    <div class="container">
        <ul class="nav nav-tabs justify-content-center" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#all-modules">Module</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#new-module">New Module</a>
            </li>
        </ul>
        <div class="tab-content">
            <br>
            <div class="tab-pane fade show active" role="tabpanel" id="all-modules">
                <?php foreach($modules as $module): ?>
                    <div class="card margin-bot">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm">
                                    <p ><span class="h5">Name: </span>
                                        <?php echo $module->name?></p>
                                </div>
                                <div class="col-sm">
                                    <p><span class="h5">Category: </span>
                                        <?php echo $module->category?></p>
                                </div>
                            </div>
                            <p class="h5">Description:</p>
                            <p class="pad-left pad-bot"><?php echo $module->description?></p>
                            <div class="row">
                                <div class="col-sm">
                                    <p class="h5">Input File:</p>
                                    <p class="pad-left pad-bot"><?php echo $module->inputFile?></p>
                                </div>
                                <div class="col-sm">
                                    <p class="h5">Output File Required:</p>
                                    <p class="pad-left pad-bot"><?php echo $module->outputFile_required?></p>
                                </div>
                                <div class="col-sm">
                                    <?php if ($module->outputFile_required == 'true'): ?>
                                        <p class="h5">Output File:</p>
                                        <p class="pad-left pad-bot"><?php echo $module->outputFile?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <p class="h5">Command:</p>
                            <p class="pad-left pad-bot"><?php echo $module->command?></p>
                            <p class="h5">Parameters:</p>
                            <p class="pad-left pad-bot"><?php echo $module->params?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="tab-pane fade" role="tabpanel" id="new-module">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data" method="POST">
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Enter a name">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="category">Category</label>
                                <input type="text" class="form-control" name="category" id="category" placeholder="Enter a category">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text" class="form-control" id="description" name="description" placeholder="Enter a description">
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3">Input file is a parameter:</div>
                        <div class="col-sm-9">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inputParam" id="inputParamRadio1" value="true">
                                <label class="form-check-label" for="inputParamTrue">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inputParam" id="inputParamRadio2" value="false" checked>
                                <label class="form-check-label" for="inputParamFalse">No</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputFile">Input File</label>
                        <input type="text" class="form-control" name="inputFile" id="inputFile" placeholder="Enter an input file">
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3">Output file required?</div>
                        <div class="col-sm-9">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="outputFile_required" id="outputFileRadio1" value="true" checked>
                                <label class="form-check-label" for="outputFile_required">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="outputFile_required" id="outputFileRadio2" value="false">
                                <label class="form-check-label" for="outputFile_required">No</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3">Output file is a parameter:</div>
                        <div class="col-sm-9">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="outputParam" id="outputParamRadio1" value="true">
                                <label class="form-check-label" for="outputParamTrue">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="outputParam" id="outputParamRadio2" value="false" checked>
                                <label class="form-check-label" for="outputParamFalse">No</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputFile">Output File</label>
                        <input type="text" class="form-control" name="outputFile" id="outputFile" placeholder="Enter an output file">
                    </div>
                    <div class="form-group">
                        <label for="command">Command</label>
                        <input type="text" class="form-control" id="command" name="command" placeholder="Enter a command">
                    </div>
                    <div class="form-group">
                        <label for="inputFile">Parameters</label>
                        <textarea class="form-control" id="params" name="params" rows="3" placeholder="Enter the parameters."></textarea>
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="moduleFile" id="moduleFile">
                        <label class="custom-file-label" for="moduleFile">Choose Module Executable</label>
                    </div>
                    <button type="submit" class="btn btn-primary margin-bot-top">Submit</button>
                </form>
            </div>
        </div>
    </div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>
</html>