<?php


class TimeController
{
    public function __construct()
    {
    }

    public function processRequest(string $handler): void
    {
        $method = $_SERVER["REQUEST_METHOD"];

        switch ($handler) {
            case "currentDate":
                $this->currentDate($method);
                break;
            case "currentTime":
                $this->currentTime($method);
                break;
        }
    }

    private function currentDate(string $method): void
    {
        switch ($method) {
            case "GET":
                $current_date = date("d/m/Y");  // EX: 06/06/2022

                echo json_encode(array(
                    'current_date' => $current_date,
                ));
                break;
        }
    }

    private function currentTime(string $method): void
    {
        switch ($method) {
            case "GET":
                $current_time = date("H:i:s");  // EX: 14:54:40

                echo json_encode(array(
                    'current_time' => $current_time,
                ));
                break;
        }
    }
}
