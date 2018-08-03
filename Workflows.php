<?php
require 'header.php';
require 'navbar.php';
require 'Module.php';
session_start();

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
                    <input type="text" class="form-control" name="outputFolder" id="outputFolder">
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
                <button class="btn btn-primary" id="addVariables" type="button">Add input variables</button>
            </form>
            <p id="workflowTitle"></p>
            <p id="workflow"></p>
        </div>
    </div>
    <div id="workflowForms"></div>
</div>
    <script>
        const workflow = [];
        const moduleList = [];

        $("#addModuleToWFButton").on("click", function() {
            workflow.push($("#moduleSelect").val());
            // addModuleToWorkflow($("#addModuleToWFButton").val());
            console.log(workflow);
            createWorkflowString();
        });

        $("#addVariables").on("click", function() {
            $.get("./moduleList.php", function (data) {
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
                $("#workflowForms").empty();
                createForm(moduleList);
            })
        });

        function createForm(workflowList) {

            for (let i = 0; i < workflowList.length; i++) {
                let html = '<div class="card margin-bot">';
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
                html += '<form name="' + 'workflowForm' + i + '">';
                html += '<div class="form-group">';
                html += '<label for="inputFile">Input File</label>';
                if (workflowList[i]['inputParam'] === 'true') {
                    html += '<input type="text" class="form-control" name="inputFile' + i
                        + '" id="inputFile" placeholder="Enter an input file" disabled>';
                } else {
                    html += '<input type="text" class="form-control" name="inputFile' + i
                        + '" id="inputFile" placeholder="Enter an input file">';
                }
                html += '</div>';
                html += '<div class="form-group">';
                html += '<label for="outputFile">Output File</label>';
                html += '<input type="text" class="form-control" name="outputFile' + i + '" id="outputFile" placeholder="Enter an output file">';
                html += '</div>';
                html += '<div class="form-group">';
                html += '<label for="params">Parameters: </label>';
                html += '<textarea class="form-control" name="params' + i + '" id="params"></textarea>';
                html += '</div>';
                html += '</div>';
                html += '</form>';
                $("#workflowForms").append(html);
                // const inputIsNotParam = workflowList[i]['inputParam'] === 'false';
                // if (i !== workflowList.length && inputIsNotParam) {
                //     $('#outputFile' + i).change(function(){
                //         $('#inputFile' + (i + 1)).val(this.value);
                //     });
                // }
            }

            const buttonHtml = '<button class="btn btn-primary" id="inputFileButton" type="button">Create Input File</button>';
            $("#workflowForms").append(buttonHtml);
            $("#inputFileButton").on("click", function() {
                const values = [];
                for (let i = 0; i < workflowList.length; i++) {
                    let formData = {
                        inputFile: $('input[name="inputFile' + i + '"]').val(),
                        outputFile: $('input[name="outputFile' + i + '"]').val(),
                        params: $('textarea[name="params' + i + '"]').val()
                    };
                    values.push(formData);
                    // console.log($('input[name="inputFile' + i + '"]').val());

                }
                console.log(values);
            });


        }

        function createWorkflowString() {
            let workflowString = "";
            workflow.map( x => {
                workflowString = workflowString + x + '-->';
            });
            workflowString = workflowString.substring(0, workflowString.length - 3);
            if(workflow.length === 1) {
                $("#workflowTitle").text("Current Workflow");
            }
            $("#workflow").text(workflowString);
        }
    </script>
  </body>
</html>