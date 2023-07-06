<?php

require __DIR__ . "/../models/Operation.php";


class OperationController
{
    public function __construct(private Operation $operation)
    {
    }

    public function processRequest(string $handler): void
    {
        $method = $_SERVER["REQUEST_METHOD"];

        switch ($handler) {
            case "bySmartBox":
                $this->operationBySmartBox($method);
                break;
            case "create":
                $this->createOperation($method);
                break;
            case "bymodel":
                $this->operationBymodel($method);
                break;
            case "MultiMachine":
                $this->operationMultiMachine($method);
                break;
            case "bymodelPrin":
                $this->operationBymodelPrin($method);
                break;
            case "bymodelSec":
                $this->operationBymodelSec($method);
                break;
        }
    }

    private function operationBySmartBox(string $method): void
    {
        switch ($method) {
            case "GET":
                $packetNumber = $_GET["packetNumber"];
                $smartBoxName = explode("/", $_SERVER['REQUEST_URI'])[6] ?? null;
                echo json_encode($this->operation->getOperationBySmartBox($packetNumber, $smartBoxName));
                break;
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $rows = $this->operation->deleteForOp($data);

                echo json_encode($rows ? [
                    "message" => "Production Line updated"
                ] : "{}");
                break;
        }
    }

    private function createOperation(string $method): void
    {
        switch ($method) {
            case "GET":
                // code goes here in case of_num GET Request...
                break;
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $rows = $this->operation->insertOperation($data);

                echo json_encode($rows ? [
                    "message" => "Operation Inserted"
                ] : "{}");
                break;
        }
    }
    private function operationBymodelPrin(string $method): void
    {
        switch ($method) {
            case "GET":
        
                break;
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $rows = $this->operation->updateOperationBymodelPrin($data);

                echo json_encode($rows ? [
                    "message" => "Production Line updated"
                ] : "{}");
                break;
        }
    }
    private function operationBymodelSec(string $method): void
    {
        switch ($method) {
            case "GET":
        
                break;
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $rows = $this->operation->updateOperationBymodelSec($data);

                echo json_encode($rows ? [
                    "message" => "Production Line updated"
                ] : "{}");
                break;
        }
    }
    private function operationBymodel(string $method): void
    {
        switch ($method) {
            case "GET":
                $modelName0 = explode("/", $_SERVER['REQUEST_URI'])[6] ?? null;
                $modelName = str_replace("%20"," ",$modelName0);
                echo json_encode($this->operation->getOperationBymodel($modelName));
                break;
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $rows = $this->operation->updateOperationBymodel($data);

                echo json_encode($rows ? [
                    "message" => "Production Line updated"
                ] : "{}");
                break;
        }
    }

    private function operationMultiMachine(string $method): void
    {
        switch ($method) {
            case "GET":
                $model = $_GET["model"];
                $operation_code = $_GET["operation_code"];
                echo json_encode($this->operation->getOperationMultiMachine($model, $operation_code));
                break;
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $rows = $this->operation->updateOperationMultiMachine($data);

                echo json_encode($rows ? [
                    "message" => "Production Line updated"
                ] : "{}");
                break;
        }
    }
}
