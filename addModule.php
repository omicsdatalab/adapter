<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userModule = new Module();
    $userModule->name = $_POST["name"];
    $userModule->category = $_POST["category"];
    $userModule->description = $_POST["description"];
    $userModule->inputFile = $_POST["inputFile"];
    $userModule->inputParam = $_POST["inputParam"];
    $userModule->outputFile_required = $_POST["outputFile_required"];
    $userModule->outputFile = $_POST["outputFile"];
    $userModule->outputParam = $_POST["outputParam"];
    $userModule->command = $_POST["command"];
    $userModule->params = $_POST["params"];

    $target_dir = "uploads" . DIRECTORY_SEPARATOR . session_id() . DIRECTORY_SEPARATOR;
    if(!file_exists($target_dir)) {
        mkdir($target_dir);
    }

    $target_file = $target_dir . basename($_FILES["moduleFile"]["name"]);

    if (move_uploaded_file($_FILES["moduleFile"]["tmp_name"], $target_file)) {
        $_SESSION["userModules"][] = $userModule;
        echo "The file ". basename( $_FILES["moduleFile"]["name"]). " has been uploaded.";
        $userModuleFile = $target_dir . "module.xml";
        if(file_exists($userModuleFile)) {
            createUserModuleXml($userModuleFile, $userModule);
        } else {
            copy("module.xml", $userModuleFile);
            createUserModuleXml($userModuleFile, $userModule);
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

function createUserModuleXml($file, $moduleToAdd) {
    $xml = simplexml_load_file($file);

    $module = $xml->addChild("module");
    $module->addChild("name", $moduleToAdd->name);
    $module->addChild("category", $moduleToAdd->category);
    $module->addChild("description", $moduleToAdd->description);
    $module->addChild("inputFile", $moduleToAdd->inputFile);
    $module->addChild("inputParam", $moduleToAdd->inputParam);
    $module->addChild("outputFile_required", $moduleToAdd->outputFile_required);
    $module->addChild("outputFile", $moduleToAdd->outputFile);
    $module->addChild("outputParam", $moduleToAdd->outputParam);
    $module->addChild("params", $moduleToAdd->params);
    $module->addChild("command", $moduleToAdd->command);

    //Format XML to save indented tree rather than one line
    $dom = new DOMDocument('1.0');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($xml->asXML());
    $dom->save($file);
}
