<?php


class Operation
{
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getOperationBySmartBox(string $packetNumber, string $smartBoxName): array | string
    {
        // $operationExist = $this->isOperationExist($packetNumber, $smartBoxName);

        // if ($operationExist) {
        //     http_response_code(400);

        //     return "{}";
        // }


        $sql = "SELECT * FROM p3_gamme WHERE digitex LIKE '%$smartBoxName%' AND pack_num = '$packetNumber' AND op_state != 1";
        // SELECT * FROM `p3_gamme` WHERE digitex LIKE 'ISA201-70' AND pack_num = "37245840";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll();
        $stmt->closeCursor();

        if (!$results) {
            http_response_code(400);

            return "{}";
        }

        $filtred_results = [];
        array_push($filtred_results, $results[0]);
        for ($i = 1; $i < count($results); $i++) {
            if ($results[$i]["id"] === $results[$i - 1]["id"] + 1) {
                array_push($filtred_results, $results[$i]);
            }
        }
        foreach ($filtred_results as $filtred_result) {
            $op_id = $filtred_result["id"];
            $op_sql = "UPDATE p3_gamme SET op_state = 1 WHERE id = '$op_id'";

            $op_stmt = $this->conn->prepare($op_sql);
            $op_stmt->execute();
            $op_stmt->closeCursor();
        }

        $result = array(
            'of_num' => $filtred_results[0]['of_num'],
            'operation_code' => $filtred_results[0]['operation_code'],
            'designation' => $filtred_results[0]['designation'],
            'machine_ref' => $filtred_results[0]['machine_ref'],
            'unit_time' => (float) $filtred_results[0]['unit_time'],
            'h_counter' => date("H"),
            'min_counter' => date("i"),
        );

        if (count($filtred_results) > 1) {
            for ($i = 1; $i < count($filtred_results); $i++) {
                $result['operation_code'] .= ',' . $filtred_results[$i]['operation_code'];
                $result['designation'] .= ',' . $filtred_results[$i]['designation'];
                $result['unit_time'] += (float) $filtred_results[$i]['unit_time'];
            }
        }

        return array(
            'of_num' => $result['of_num'],
            'operation_code' => $result['operation_code'],
            'designation' => $result['designation'],
            'machine_ref' => $result['machine_ref'],
            'qte_H' => $result['qte_H'] ?? (string) floor(60 / ((float) $result['unit_time'])),
            'unit_time' => (string) $result['unit_time'],
            'h_counter' => date("H"),
            'min_counter' => date("i"),
        );
    }

    public function insertOperation(array $data): int
    {
        $tagRFID = $data["tag_RFID"];
        $packetNumber = $data["packet_number"];
        $operationCode = $data["operation_code"];
        $designation = $data["designation"];
        $unit_time = $data["unit_time"];
        $quantity = $data["quantity"];
        $operatorRN = $data["registration_number"];
        $firstName = $data["first_name"];
        $lastName = $data["last_name"];
        $machineID = $data["machine_ref"];
        $digitex = $data["digitex_smart_box"];
        $current_day = date("d/m/Y");
        $current_time = date("H:i:s");

        $sql = "INSERT INTO p4_pack_operation
            (pack_num, Code_operation, Operation_name, registration_number, Firstname, Lastname, machine_ref, digitex, cur_day, cur_time, unit_time, quantity)
                VALUES
            ('$packetNumber', '$operationCode', '$designation', '$operatorRN', '$firstName', '$lastName', '$machineID', '$digitex', '$current_day', '$current_time', '$unit_time', '$quantity');";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $stmt->closeCursor();

        if (str_contains(strtolower($designation), "contr")) {
            // echo "yes";
            $sql2 = "UPDATE p12_control SET tag_id = '$tagRFID' WHERE state = 'here';";
            $stmt2 = $this->conn->prepare($sql2);
            $stmt2->execute();
            $stmt2->closeCursor();
        }


        return $stmt->rowCount();
    }

    // private function isOperationExist(string $packetNumber, string $smartBoxName): bool
    // {
    //     // $sql = "SELECT * FROM p4_pack_operation
    //     //         WHERE pack_num = '$packetNumber' AND digitex = '$smartBoxName'";

    //     $sql = "SELECT * FROM `p4_pack_operation`
    //                 WHERE digitex = '$smartBoxName'
    //                 ORDER BY `p4_pack_operation`.`id` DESC
    //                 LIMIT 1";

    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->execute();
    //     $result = $stmt->fetch();
    //     $stmt->closeCursor();

    //     $result = $result["pack_num"] === $packetNumber ? $result : [];

    //     return (bool) $result;
    // }

    public function getOperationBymodel(string $modelName): array | string
    {
        $sql1 = "select * from init__model where model = '$modelName'";

        $stmt1 = $this->conn->prepare($sql1);
        $stmt1->execute();
        $result1 = $stmt1->fetch();
        $stmt1->closeCursor();

        $mod_id= $result1["id"];

        $sql = "select id, operation_num, designation, smartbox, machine_id from prod__gamme where model_id = '$mod_id'";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll();
        $stmt->closeCursor();

        if (!$results) {
            http_response_code(400);

            return "{}";
        }

        return $results;
    }

    public function getOperationMultiMachine(string $model, string $operation_code): array | string
    {
        $sql = "select smartbox, machine_id from prod__gamme where model_id = (SELECT id FROM init__model WHERE model = '$model') AND operation_num= '$operation_code'";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll();
        $stmt->closeCursor();

        if (!$results) {
            http_response_code(400);

            return "{}";
        }

        return $results;
    }

    public function updateOperationBymodel(array $data): int
    {
        $digitex = $data["digitex"];
        $operation_code = $data["operation_code"];
        $model = $data["model"];
        $sql = "UPDATE prod__gamme 
                SET smartbox = '$digitex', main_sb ='$digitex', machine_id = (SELECT machine_id FROM prod__implantation WHERE smartbox = '$digitex') 
                WHERE operation_num = '$operation_code' AND model_id = (SELECT id FROM init__model WHERE model = '$model');";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $stmt->closeCursor();

        return $stmt->rowCount();
    }
    public function updateOperationMultiMachine(array $data): int
    {
        $digitex = $data["digitex"];
        $operation_code = $data["operation_code"];
        $model = $data["model"];
        $machine_ref = $data["machine_ref"];
        
        $sql = "UPDATE prod__gamme 
                SET smartbox = '$digitex', machine_id = '$machine_ref'
                WHERE operation_num = '$operation_code' AND model_id = (SELECT id FROM init__model WHERE model = '$model');";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $stmt->closeCursor();

        return $stmt->rowCount();
    }
    
    public function deleteForOp(array $data): int
    {
        $operation_code = $data["operation_code"];
        $model = $data["model"];
        $sql = "UPDATE prod__gamme SET smartbox = '', machine_id = '', main_sb = '' WHERE operation_num = '$operation_code' AND model_id = (SELECT id FROM init__model WHERE model = '$model')";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $stmt->closeCursor();
    
        return $stmt->rowCount();
    }
    public function updateOperationBymodelPrin(array $data): int
    {
        $digitex = $data["digitex"];
        $digitexMulti = $data["digitexMulti"];
        $operation_code = $data["operation_code"];
        $machine_ref = $data["machine_ref"];
        $model = $data["model"];
        $sql = "UPDATE prod__gamme 
                SET smartbox = '$digitexMulti', main_sb ='$digitex', machine_id = '$machine_ref'
                WHERE operation_num = '$operation_code' AND model_id = (SELECT id FROM init__model WHERE model = '$model');";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $stmt->closeCursor();

        return $stmt->rowCount();
    }
    public function updateOperationBymodelSec(array $data): int
    {
        $digitex = $data["digitex"];
        $operation_code = $data["operation_code"];
        $model = $data["model"];
        $sql = "UPDATE prod__gamme 
                SET smartbox = '$digitex', machine_id = (SELECT machine_id FROM prod__implantation WHERE smartbox = '$digitex') 
                WHERE operation_num = '$operation_code' AND model_id = (SELECT id FROM init__model WHERE model = '$model');";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $stmt->closeCursor();

        return $stmt->rowCount();
    }


}
