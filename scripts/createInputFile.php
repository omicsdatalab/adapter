<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["createInputFile"])) {

        $target_dir = "uploads" . DIRECTORY_SEPARATOR . session_id() . DIRECTORY_SEPARATOR;
        if(!file_exists($target_dir)) {
            mkdir($target_dir);
        }

        $uniqueId = $_POST['uniqueId'];
        $outputFolder = $_POST['outputFolder'];
        $workflow = $_POST['workflow'];
        $workflowArray = explode(",", $workflow);
        $inputs = array();
        foreach ($_POST['input'] as $value) {
            $inputs[] = "[" . createInputFileString($value['inputFile']) . "," . createOutputFileString($value['outputFile'])
            . "," . createParamsString($value['params']) . "]";
        }

        $inputXml = new SimpleXMLElement("<inputConfig></inputConfig>");
        $workflowEl = $inputXml->addChild("workflow");
        $inputXml->workflow = $workflow;
        $outputFolderEl = $inputXml->addChild("outputFolder");
        $inputXml->outputFolder = $outputFolder;
        $uniqueIdEl = $inputXml->addChild("uniqueId");
        $inputXml->uniqueId = $uniqueId;
        $modulesEl = $inputXml->addChild("modules");
        for ($i = 0; $i < count($inputs); $i++) {
            $modulesEl->addChild("module");
            $modulesEl->module[$i]->addChild("name");
            $modulesEl->module[$i]->name = $workflowArray[$i];
            $modulesEl->module[$i]->addChild("input");
            $modulesEl->module[$i]->input = $inputs[$i];
        }
        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($inputXml->asXML());

        $userInputFile = $target_dir . "input.xml";
        $dom->save($userInputFile);

        $_SESSION['inputFileCreated'] = true;
        echo "Your input file has been generated and is available to download.";
    }
}

function createInputFileString($inputFile) {
    if(!isset($inputFile)) {
        $inputFile = "";
    }
    $inputFile = trim($inputFile);
    $inputStr = '"Inputfile:' . $inputFile . '"';
    return $inputStr;
}

function createOutputFileString($outputFile) {
    if(!isset($outputFile)) {
        $outputFile = "";
    }
    $outputFile = trim($outputFile);
    $outputStr = '"outputfile:' . $outputFile . '"';
    return $outputStr;
}

function createParamsString($params) {
    if(!isset($params)) {
        $params = "";
    }
    $paramStr = '"' . trim($params) . '"';
    return $paramStr;
}