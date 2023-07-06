<?php

require __DIR__ . "/../models/Operator.php";


class OperatorController
{
    public function __construct(private Operator $operator)
    {
    }

    public function processRequest(string $handler): void
    {
        $method = $_SERVER["REQUEST_METHOD"];  // GET

        switch ($handler) {
            case "byRFID":
                $this->operatorByRFID($method);
                break;
            case "presence":
                $this->operatorPresenceTime($method);
                break;
            case "downtime":
                $this->operatorDownTime($method);
                break;
            case "performance":
                $this->operatorPerformance($method);
                break;
            case "performanceHour":
                $this->operatorPerformanceHour($method);
                break;
            case "addOperator":
                $this->insertoperator($method);
                break;
        }
    }

    private function operatorByRFID(string $method): void
    {
        switch ($method) {
            case "GET":
                $RFID = explode("/", $_SERVER['REQUEST_URI'])[6] ?? null;  // e3cfaf19
                echo json_encode($this->operator->getOperatorByRFID($RFID));
                break;
            case "POST":
                // code goes here in case of_num POST Request...
                break;
        }
    }

    private function operatorPresenceTime(string $method): void
    {
        switch ($method) {
            case "GET":
                // code goes here in case of_num GET Request...

                break;
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $rows = $this->operator->addOperatorPresenceTime($data);

                echo json_encode($rows ? [
                    "message" => "Presence Inserted"
                ] : "{}");
                break;
        }
    }

    private function operatorDownTime(string $method): void
    {
        switch ($method) {
            case "GET":
                // code goes here in case of_num GET Request...

                break;
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $rows = $this->operator->addOperatorDownTime($data);

                echo json_encode($rows ? [
                    "message" => "Downtime Inserted"
                ] : "{}");
                break;
        }
    }

    private function insertoperator(string $method): void
    {
        switch ($method) {
            case "GET":
                // code goes here in case of_num GET Request...

                break;
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $rows = $this->operator->addOperatorByrfid($data);

                echo json_encode($rows ? [
                    "message" => "Insertion réussie"
                ] : "{}");
                break;
        }
    }

    private function operatorPerformance(string $method): void
    {
        switch ($method) {
            case "GET":
                $registrationNumber = explode("/", $_SERVER['REQUEST_URI'])[6] ?? null;
                echo json_encode($this->operator->getOperatorPerformance($registrationNumber));
                break;

            case "POST":
                // code goes here in case of_num POST Request...
                break;
        }
    }
    private function operatorPerformanceHour(string $method): void
    {
        switch ($method) {
            case "GET":
                $qrcode = explode("/", $_SERVER['REQUEST_URI'])[6] ?? null;
                echo json_encode($this->operator->getPerformanceHour($qrcode));
                break;

            case "POST":
                // code goes here in case of_num POST Request...
                break;
        }
    }
}
