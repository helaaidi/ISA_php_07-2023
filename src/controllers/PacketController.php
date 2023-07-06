<?php

require __DIR__ . "/../models/Packet.php";


class PacketController
{
    public function __construct(private Packet $packet)
    {
        
    }

    public function processRequest(string $handler): void
    {
        $method = $_SERVER["REQUEST_METHOD"];

        switch ($handler) {
            case "byRFID":
                $this->packetByRFID($method);
                break;
            case "control":
                $this->controlPacket($method);
                break;
            case "models":
                $this->packetmodels($method);
                break;
            case "prod_lines":
                $this->packetprod_line($method);
                break;
            case "modelByprod_line":
                $this->packetmodelByprod_line($method);
                break;
            case "of_num":
                $this->packetByof_num($method);
                break;
            case "pack_num":
                $this->packetBypack_num($method);
                break;
            case "here":
                $this->hereState($method);
                break;
            case "triage":
                $this->tagTriage($method);
                break;
            case "research":
                $this->tagResearch($method);
                break;
            case "here_next":
                $this->herenext($method);
                break;
        }
    }

    private function packetByRFID(string $method): void
    {
        switch ($method) {
            case "GET":
                $RFID = explode("/", $_SERVER['REQUEST_URI'])[6] ?? null;
                echo json_encode($this->packet->getPacketByRFID($RFID));
                break;
            case "POST":
                // code goes here in case of_num POST Request...
                break;
        }
    }
    private function tagResearch(string $method): void
    {
        switch ($method) {
            case "GET":
                $tag_id = $_GET["tag_id"];
                echo json_encode($this->packet->getPack($tag_id));
                break;
            case "POST":
                // code goes here in case of_num POST Request...
                break;
        }
    }
    private function controlPacket(string $method): void
    {
        switch ($method) {
            case "GET":
                $pack_num = $_GET["pack_num"] ?? NULL;
                $prod_line = $_GET["prod_line"] ?? NULL;
                if($pack_num){
                    echo json_encode($this->packet->getPackId($pack_num, $prod_line));
                } else {
                    echo json_encode($this->packet->getcontrolPacket($prod_line));
                }
                
                break;
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $rows = $this->packet->insertcontrolPacket($data);

                echo json_encode($rows ? [
                    "message" => "Controle success"
                ] : "{}");
                break;

            case "PATCH":
                echo json_encode($this->packet->getcontrolPacketSoft($prod_line));
                break;
        }
    }

    private function packetmodels(string $method): void
    {
        switch ($method) {
            case "GET":
                echo json_encode($this->packet->getPacketmodels());
                break;
            case "POST":
                // code goes here in case of_num POST Request...
                break;
        }
    }

    private function packetprod_line(string $method): void
    {
        switch ($method) {
            case "GET":
                echo json_encode($this->packet->getPacketprod_line());
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
                echo json_encode($this->packet->getmodelByprod_line($prod_lineName));
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
                $modelName = $_GET["model"];
                echo json_encode($this->packet->getPacketByof_num($modelName));
                break;
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $rows = $this->packet->insertOF($data);

                echo json_encode($rows ? [
                    "message" => "Controle success"
                ] : "{}");
                break;
        }
    }
    private function packetBypack_num(string $method): void
    {
        switch ($method) {
            case "GET":
                $pack_num = $_GET["pack_num"];
                echo json_encode($this->packet->getPacketBypack_num($pack_num));
                break;
            case "POST":
                // code goes here in case of_num POST Request...
                break;
        }
    }
    
    private function herenext(string $method): void
    {
        switch ($method) {
            case "GET":
                $of_num = $_GET["of_num"];
                echo json_encode($this->packet->getPacket($of_num));
                break;
        }
    }
    private function hereState(string $method): void
    {
        switch ($method) {
            case "GET":
                $of_num = $_GET["of_num"];
                echo json_encode($this->packet->getPacketByState($of_num));
                break;
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $rows = $this->packet->updateOnePacket($data);

                echo json_encode($rows ? [
                    "message" => "Packet updated"
                ] : "{}");
                break;
            case "PATCH":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $rows = $this->packet->updatePacketByState($data);
                echo json_encode($rows ? [
                    "message" => "Packet updated"
                ] : "{}");
                break;
            case "DELETE":
                echo json_encode($this->packet->deleteTag());
                break;
        }
    }
    private function tagTriage(string $method): void
    {
        switch ($method) {
            case "GET":
                $tag_id = $_GET["tag_id"] ?? NULL;
                if($tag_id){
                    echo json_encode($this->packet->getstateTag($tag_id));
                } else {
                    echo json_encode($this->packet->getTagTriage());
                }
                break;
                
            case "POST":
                // code goes here in case of_num POST Request...
                break;
        }
    }
}
