<?php

require __DIR__ . "/../models/Import.php";


class ImportController
{
    public function __construct(private Import $import)
    {
        
    }

    public function processRequest(string $handler): void
    {
        $method = $_SERVER["REQUEST_METHOD"];

        switch ($handler) {
            case "prod_lines":
                $this->packetprod_line($method);
                break;
            case "modelByprod_line":
                $this->packetmodelByprod_line($method);
                break;
            case "of_num":
                $this->packetByof_num($method);
                break;
        }
    }

    private function packetprod_line(string $method): void
    {
        switch ($method) {
            case "GET":
                echo json_encode($this->import->getPacketprod_line());
                break;
            case "POST":
                // code goes here in case of_num POST Request...
                break;
        }
    }

    private function packetmodelByprod_line(string $method): void
    {
        switch ($method) {
            case "GET":
                $prod_lineName = $_GET["prod_line"];
                echo json_encode($this->import->getmodelByprod_line($prod_lineName));
                break;
            case "POST":
                // code goes here in case of_num POST Request...
                break;
        }
    }
    private function packetByof_num(string $method): void
    {
        switch ($method) {
            case "GET":
                $modelName0 = explode("/", $_SERVER['REQUEST_URI'])[6] ?? null;
                $modelName = str_replace("%20"," ",$modelName0);
                echo json_encode($this->import->getPacketByof_num($modelName));
                break;
            case "POST":
                
                break;
        }
    }
}
