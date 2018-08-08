<?php
require 'header.php';
require 'navbar.php';
require './models/Module.php';
session_start();
require './scripts/createInputFile.php';

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
                    <input type="text" class="form-control" name="outputFolder" placeholder="Output Folder" id="outputFolder">
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

                <button class="btn btn-primary" id="addVariables" disabled type="button">Add input variables</button>

                <div class="btn-group btn-group-sm margin-left" role="group">
                    <button type="button" class="btn btn-default" id="undoButton"><i class="fas fa-undo"></i></button>
                    <button type="button" class="btn btn-default" id="resetButton"><i class="far fa-times-circle"></i></i></button>
                </div>


            </form>
            <p id="workflowTitle"></p>
            <p id="workflow"></p>
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
        // addModuleToWorkflow($("#addModuleToWFButton").val());
        console.log(workflow);
        createWorkflowDisplay();
        setButtonDisabledIfInvalid();
    });

    $("#undoButton").on("click", () => {
        if (workflow.length > 0) {
            workflow.pop();
            createWorkflowDisplay();
        }
    });

    $("#resetButton").on("click", () => {
        if (workflow.length > 0) {
            workflow = [];
            $("#workflow").empty();
            $("#workflowTitle").empty();
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
        let workflowIsValid = workflow.length > 1 ? true : false;
        if ((id !== "" || outputFolder !== "") && workflowIsValid) {
            $("#addVariables").prop("disabled", false);
        }
    }

    $("#addVariables").on("click", function() {
        $("#workflowForms").empty();
        moduleList = [];
        console.log(moduleList);
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
        console.log(workflowList);
        let html = '<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">';
        html += '<input id="uniqueIdForm" name="uniqueId" type="hidden">';
        html += '<input id="outputFolderForm" name="outputFolder" type="hidden">';
        html += '<input id="workflowForm" name="workflow" type="hidden">';
        for (let i = 0; i < workflowList.length; i++) {
            html += '<div class="card margin-bot">';
            html += '<div class="card-body">';
            html += '<div class="row">';
            html += '<div class="col-sm">';
            html += '<p>Name: ' + workflowList[i].name + '</p>';
            html += '<p>Category: ' + workflowList[i]['category'] + '</p>';
            html += '<p>Input is a parameter? ' + workflowList[i]['inputParam'] + '</p>';
            html += '<p>Output File Required? ' + workflowList[i]['outputFile_required'] + '</p>';
            html += '<p>Output is a parameter? ' + workflowList[i]['outputParam'] + '</p>';
            html += '</div>';
            html += '<div class="col-sm">';
            html += '<p>Description:</p>';
            html += '<p>' + workflowList[i]['description'] + '</p>';
            html += '</div>';
            html += '</div>';
            html += '<div class="form-group">';
            html += '<label for="inputFile">Input File</label>';
            if (workflowList[i]['inputParam'] === 'true') {
                html += '<input type="text" class="form-control" name="input[' + i + '][inputFile]"'
                    + ' id="inputFile" placeholder="Enter an input file" disabled>';
            } else {
                html += '<input type="text" class="form-control" name="input[' + i + '][inputFile]"'
                    + ' id="inputFile" placeholder="Enter an input file">';
            }
            html += '</div>';
            html += '<div class="form-group">';
            html += '<label for="outputFile">Output File</label>';
            html += '<input type="text" class="form-control" name="input[' + i + '][outputFile]"' + ' id="outputFile" placeholder="Enter an output file">';
            html += '</div>';
            html += '<div class="form-group">';
            html += '<label for="params">Parameters: </label>';
            html += '<textarea class="form-control" name="input[' + i + '][params]"' + ' id="params"></textarea>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
        }

        const buttonHtml = '<button class="btn btn-primary margin-bot-top" id="inputFileButton" type="submit">Create Input File</button>';
        // $("#workflowForms").append(buttonHtml);
        html += buttonHtml;
        html += '</form>';
        $("#workflowForms").append(html);

        // $("#inputFileButton").on("click", function() {
        //     const values = [];
        //     for (let i = 0; i < workflowList.length; i++) {
        //         let formData = {
        //             inputFile: $('input[name="inputFile' + i + '"]').val(),
        //             outputFile: $('input[name="outputFile' + i + '"]').val(),
        //             params: $('textarea[name="params' + i + '"]').val()
        //         };
        //         values.push(formData);
        //     }
        //     console.log(values);
        //     //send to server and create input xml.
        //     $.post("./createInputFile.php", JSON.stringify(values), (data) => {
        //         console.log(data);
        //         alert('success');
        //     }).fail( () => {
        //         alert('error');
        //     })
        // });
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