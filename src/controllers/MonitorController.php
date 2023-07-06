<?php

require __DIR__ . "/../models/Monitor.php";


class MonitorController
{
    public function __construct(private Monitor $monitor)
    {
    }

    public function processRequest(string $handler): void
    {
        $method = $_SERVER["REQUEST_METHOD"];

        switch ($handler) {
            case "bySmartBox":
                $this->monitorBySmartBox($method);
                break;
            case "notification":
                $this->createNotification($method);
                break;
            case "call":
                $this->callNotification($method);
                break;
            case "callMaintainer":
                $this->monitorCallMaint($method);
                break;
            case "intervention":
                $this->interventionMaint($method);
                break;
                
        }
    }

    private function monitorBySmartBox(string $method): void
    {
        switch ($method) {
            case "GET":
                $smartBox = explode("/", $_SERVER['REQUEST_URI'])[6] ?? null;
                echo json_encode($this->monitor->getMonitorBySmartBox($smartBox));
                break;

            case "POST":
                // code goes here in case of_num POST Request...
                break;
        }
    }

    private function createNotification(string $method): void
    {
        switch ($method) {
            case "GET":
                // code goes here in case of_num GET Request...
                break;

            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $rows = $this->monitor->insertNotification($data);

                echo json_encode($rows ? [
                    "message" => "Notification Inserted"
                ] : "{}");
                break;
        }
    }

    private function monitorCallMaint(string $method): void
    {
        switch ($method) {
            case "GET":
                $state = explode("/", $_SERVER['REQUEST_URI'])[6] ?? null;
                echo json_encode($this->monitor->getCallMaint($state));
                break;

            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $rows = $this->monitor->insertCallMaint($data);

                echo json_encode($rows ? [
                    "message" => "Inscription reussie"
                ] : "{}");
                break;

            case "PATCH":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $rows = $this->monitor->updateCallMaint($data);

                echo json_encode($rows ? [
                    "message" => "Mise a jour reussie"
                ] : "{}");
                break;
        }
    }

    private function callNotification(string $method): void
    {
        switch ($method) {
            case "GET":
                echo json_encode($this->monitor->getNotification());
                break;

            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $rows = $this->monitor->updateCall($data);

                echo json_encode($rows ? [
                    "message" => "Mise a jour reussie"
                ] : "{}");
                break;
        }
    }
    private function interventionMaint(string $method): void
    {
        switch ($method) {
            case "GET":
               
                break;

            case "POST":
                
                break;
        }
    }
}
