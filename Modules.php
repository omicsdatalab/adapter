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
$xml = simplexml_load_file("modules.xml") or die("Error: Cannot create xml object.");
$modules = array();

foreach($xml->children() as $moduleFromXml) {
    $moduleToAdd = new Module();
    $moduleToAdd->name = $moduleFromXml->name;
    $moduleToAdd->category = $moduleFromXml->category;
    $moduleToAdd->description = $moduleFromXml->description;
    $moduleToAdd->inputFile = $moduleFromXml->inputFile;
    $moduleToAdd->inputParam = $moduleFromXml->inputParam;
    $moduleToAdd->outputFile_required = $moduleFromXml->outputFile_required;
    $moduleToAdd->outputFile = $moduleFromXml->outputFile;
    $moduleToAdd->outputParam = $moduleFromXml->outputParam;
    $moduleToAdd->params = $moduleFromXml->params;
    $moduleToAdd->command = $moduleFromXml->command;
    array_push($modules, $moduleToAdd);
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
                <form action="" enctype="multipart/form-data" method="POST">
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" placeholder="Enter a name">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="category">Category</label>
                                <input type="text" class="form-control" id="category" placeholder="Enter a category">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text" class="form-control" id="description" placeholder="Enter a description">
                    </div>
                    <div class="form-group">
                        <label for="inputFile">Input File</label>
                        <input type="text" class="form-control" id="inputFile" placeholder="Enter an input file">
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2">Output file required?</div>
                        <div class="col-sm-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="outputFileRadio" id="outputFileRadio1" value="true" checked>
                                <label class="form-check-label" for="outputFileTrue">True</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="outputFileRadio" id="inlineRadio2" value="false">
                                <label class="form-check-label" for="outputFileFalse">False</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputFile">Output File</label>
                        <input type="text" class="form-control" id="outputFile" placeholder="Enter an output file">
                    </div>
                    <div class="form-group">
                        <label for="command">Command</label>
                        <input type="text" class="form-control" id="command" placeholder="Enter a command">
                    </div>
                    <div class="form-group">
                        <label for="inputFile">Parameters</label>
                        <textarea class="form-control" id="params" rows="3" placeholder="Enter the parameters."></textarea>
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="customFile">
                        <label class="custom-file-label" for="customFile">Choose Module Executable</label>
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