<?php
require 'header.php';
require 'navbar.php';
require './models/Module.php';
session_start();
require './scripts/createInputFile.php';
require './scripts/resetWorkflowVars.php';

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

if(empty($_SESSION['moduleFileCreated'])) {
    $_SESSION['moduleFileCreated'] = false;
}
if(empty($_SESSION['inputFileCreated'])) {
    $_SESSION['inputFileCreated'] = false;
}

?>
<br>
<div class="container">
    <div class="float-right">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <button class="btn btn-primary btn-sm" name="resetWfVars" type="submit">Reset Workflows</button>
        </form>
    </div>
    <br>
    <br>
    <div class="card margin-bot">
        <div class="card-body">
            <h4 class="card-title">Create a workflow</h4>
            <form>
                <div class="form-group">
                    <label for="description">Unique ID</label>
                    <input type="text" class="form-control" id="uniqueId" name="uniqueId" placeholder="Unique ID">
                </div>
                <div class="form-group">
                    <label for="outputFolder">Output Folder</label>
                    <input type="text" class="form-control" name="outputFolder" placeholder="Output Folder" id="outputFolder"   >
                </div>
                <div class="input-group mb-3">
                    <select class="form-control" id="moduleSelect">
                        <?php
                        foreach ($modules as $module) {
                            echo "<option>" . $module->name . "</option>";
                        }
                        ?>
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" id="addModuleToWFButton" type="button">Add Module</button>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm" id="undoButton">Undo <i class="fas fa-undo"></i></button>
                <button type="button" class="btn btn-outline-primary btn-sm" id="resetButton">Clear <i class="far fa-times-circle"></i></button>
                <br>
                <p id="workflowTitle"></p>
                <p id="workflow"></p>
                <button class="btn btn-primary" id="addVariables" disabled type="button">Add input variables</button>
            </form>
        </div>
    </div>
    <div id="workflowForms"></div>
</div>
</body>
<script>
    let workflow = [];
    let moduleList = [];

    $("#addModuleToWFButton").on("click", function() {
        workflow.push($("#moduleSelect").val());
        createWorkflowDisplay();
        setButtonDisabledIfInvalid();
    });

    $("#undoButton").on("click", () => {
        if (workflow.length > 0) {
            if (workflow.length === 1) {
                $("#workflow").empty();
                $("#workflowTitle").empty();
            }
            workflow.pop();
            createWorkflowDisplay();
            setButtonDisabledIfInvalid();
        }
    });

    $("#resetButton").on("click", () => {
        if (workflow.length > 0) {
            workflow = [];
            $("#workflow").empty();
            $("#workflowTitle").empty();
            setButtonDisabledIfInvalid();
        }
    });

    $("#uniqueId").on("change", () => {
        setButtonDisabledIfInvalid();
    });

    $("#outputFolder").on("change", () => {
        setButtonDisabledIfInvalid();
    });

    function setButtonDisabledIfInvalid() {
        let id = $("#uniqueId").val();
        let outputFolder = $("#outputFolder").val();
        let workflowIsValid = workflow.length > 0 ? true : false;
        if (id !== "" && outputFolder !== "" && workflowIsValid) {
            $("#addVariables").prop("disabled", false);
        } else {
            $("#addVariables").prop("disabled", true);
        }
    }

    $("#addVariables").on("click", function() {
        $("#workflowForms").empty();
        moduleList = [];
        const uniqueID = $("#uniqueId").val();
        const outputFolder = $("#outputFolder").val();
        const workflowStr = createWorkflowString();
        $.get("./scripts/moduleList.php", function (data) {
            const parsedData = JSON.parse(data);
            workflow.map( module => {
                for (let i = 0; i < parsedData.length; i++) {
                    if (module === parsedData[i]['name']) {
                        console.log(parsedData[i]);
                        moduleList.push(parsedData[i]);
                        break;
                    }
                }
            });
            createForm(moduleList);
            $("#uniqueIdForm").val(uniqueID);
            $("#outputFolderForm").val(outputFolder);
            $("#workflowForm").val(workflowStr);
        });
    });

    function createForm(workflowList) {
        let html = '<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">';
        html += '<input id="uniqueIdForm" name="uniqueId" type="hidden">';
        html += '<input id="outputFolderForm" name="outputFolder" type="hidden">';
        html += '<input id="workflowForm" name="workflow" type="hidden">';
        for (let i = 0; i < workflowList.length; i++) {
            html += '<div class="card margin-bot">';
            html += '<div class="card-body">';
            html += '<div class="row">';
            html += '<div class="col-sm">';
            html += '<p><span class="h6">Name: </span>' + workflowList[i].name + '</p>';
            html += '<p><span class="h6">Category: </span>' + workflowList[i]['category'] + '</p>';
            html += '<p><span class="h6">Input is a parameter? </span>' + workflowList[i]['inputParam'] + '</p>';
            html += '<p><span class="h6">Output File Required? </span>' + workflowList[i]['outputFile_required'] + '</p>';
            html += '<p><span class="h6">Output is a parameter? </span>' + workflowList[i]['outputParam'] + '</p>';
            html += '</div>';
            html += '<div class="col-sm">';
            html += '<p class="h6">Description:</p>';
            html += '<p>' + workflowList[i]['description'] + '</p>';
            html += '</div>';
            html += '</div>';
            html += '<p class="h6">Parameters:</p>';
            html += '<p class="pad-left pad-bot">' + workflowList[i]['params'] + '</p>';
            if (workflowList[i]['inputParam'] === 'false') {
                html += '<div class="form-group">';
                html += '<label for="inputFile">Input File</label>';
                html += '<input type="text" class="form-control" name="input[' + i + '][inputFile]"'
                    + ' id="inputFile" placeholder="Enter an input file" required>';
                html += '</div>';
            }
            if (workflowList[i]['outputFile_required'] === 'true' && workflowList[i]['outputParam'] === 'false' ) {
                html += '<div class="form-group">';
                html += '<label for="outputFile">Output File</label>';
                html += '<input type="text" class="form-control" name="input[' + i + '][outputFile]"' + ' id="outputFile"' +
                    ' placeholder="Enter an output file" required>';
                html += '</div>';
            }
            html += '<div class="form-group">';
            html += '<label for="params">Parameters: </label>';
            html += '<textarea class="form-control" name="input[' + i + '][params]"' + ' id="params"></textarea>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
        }

        const buttonHtml = '<button class="btn btn-primary margin-bot-top" id="inputFileButton" name="createInputFile" type="submit">Create Input File</button>';
        html += buttonHtml;
        html += '</form>';
        $("#workflowForms").append(html);
    }

    function createWorkflowDisplay() {
        let workflowString = "";
        workflow.map( x => {
            workflowString = workflowString + x + '-->';
        });
        workflowString = workflowString.substring(0, workflowString.length - 3);
        if(workflow.length === 1) {
            $("#workflowTitle").text("Current Workflow:");
        }
        $("#workflow").text(workflowString);
    }

    function createWorkflowString() {
        let workflowString = "";
        workflow.map( x => {
            workflowString += x + ",";
        });
        workflowString = workflowString.substring(0, workflowString.length - 1);
        return workflowString;
    }
</script>
</html>