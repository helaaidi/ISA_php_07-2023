<?php

require __DIR__ . "/../models/ProductionLine.php";


class ProductionLineController
{
    public function __construct(private ProductionLine $productionLine)
    {
    }

    public function processRequest(string $handler): void
    {
        $method = $_SERVER["REQUEST_METHOD"];

        switch ($handler) {
            case "bySmartBox":
                $this->productionLineBySmartBox($method);
                break;
            case "bydigitex":
                $this->productionLineByDigiTex($method);
                break;
            case "prodlines":
                $this->getProdlines($method);
                break;
            case "bymachine":
                $this->machine($method);
                break;
        }
    }

    private function productionLineBySmartBox(string $method): void
    {
        switch ($method) {
            case "PATCH":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $rows = $this->productionLine->updateProductionLineBySmartBox($data);

                echo json_encode($rows ? [
                    "message" => "Production Line updated"
                ] : "{}");
                break;
        }
    }

    private function productionLineByDigiTex(string $method): void
    {
        switch ($method) {
            case "GET":
                $digitex = explode("/", $_SERVER['REQUEST_URI'])[6] ?? null;
                echo json_encode($this->productionLine->getProductionLineBydigitex($digitex));
                break;
            case "PATCH":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $rows = $this->productionLine->updateProductionLineBydigitex($data);

                echo json_encode($rows ? [
                    "message" => "Mise a jour reussie"
                ] : "{}");
                break;
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $rows = $this->productionLine->addProductionLineBydigitex($data);

                echo json_encode($rows ? [
                    "message" => "Mise a jour reussie"
                ] : "{}");
                break;
                
        }
    }
    private function getProdlines(string $method): void
    {
        switch ($method) {
            case "GET":
                echo json_encode($this->productionLine->allProdlines());
                break;
        }
    }
    private function machine(string $method): void
    {
        switch ($method) {
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $rows = $this->productionLine->getmachine($data);

                echo json_encode($rows ? [
                    "message" => "Mise a jour reussie"
                ] : "{}");
                break;
        }
    }
}
