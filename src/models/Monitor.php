<?php


class Monitor
{
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getMonitorBySmartBox(string $smartBox)
    {
        $sql = "SELECT * FROM i6_prod_line
                WHERE digitex = '$smartBox'";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        $stmt->closeCursor();

        if (!$result) {
            http_response_code(404);

            return "{}";
        }

        return array(
            'id' => $result['id'],
            'first_name' => $result['Firstname'],
            'last_name' => $result['Lastname'],
            'monitor' => $result['Instructor_name'],
        );
    }

    public function insertNotification(array $data): int
    {
        $productionLine = $data["production_line"];
        $packetNumber = $data["packet_number"];
        $smartBox = $data["digitex_smart_box"];
        $firstName = $data["first_name"];
        $lastName = $data["last_name"];
        $monitorName = $data["monitor_name"];
        $current_time = date("H:i:s");

        $sql = "INSERT INTO p10_notification
            (prod_line, piplette_num, digitex, Firstname, Lastname, Instructor_name, call_monitor, instant_call_monitor, monitor_arrival_time, call_maintainer, instant_call_maintainer, maintainer_arrival_time)
                VALUES
            ('$productionLine','$packetNumber','$smartBox','$firstName','$lastName','$monitorName','true','$current_time','','','','')";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $stmt->closeCursor();

        return $stmt->rowCount();
    }

    public function getNotification(): array | string
    {
        $sql = "SELECT prod__notification.id, prod__notification.smartbox, init__employee.first_name FROM prod__notification INNER JOIN init__employee ON prod__notification.operator = init__employee.matricule WHERE prod__notification.call_monitor = 'true'";
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

    public function updateCall(array $data): int
    {
        $monitor_arrival_time = $data["monitor_arrival_time"];
        $full_name = $data["full_name"];
        $id = $data["id"];

        $sql = "UPDATE prod__notification SET monitor_arrival_time = '$monitor_arrival_time', call_monitor = 'false' WHERE id = '$id'";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $stmt->closeCursor();

        return $stmt->rowCount();
    }
    public function insertCallMaint(array $data): int
    {
        $instant_call_maintainer = $data["instant_call_maintainer"];
        $digitex = $data["digitex"];
        $sql = "SELECT prod_line, monitor_reg_num, operator_reg_num FROM i6_prod_line WHERE digitex ='$digitex'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        $stmt->closeCursor();

        if (!$result) {
            http_response_code(400);

            return "{}";
        }
        $prod_line = $result["prod_line"];
        $monitor_reg_num = $result["monitor_reg_num"];
        $operator_reg_num = $result["operator_reg_num"];
        $sql2 = "INSERT INTO p10_notification (prod_line, digitex, operator_reg_num, monitor_reg_num, call_maintainer, instant_call_maintainer) VALUES ('$prod_line','$digitex','$operator_reg_num','$monitor_reg_num','true','$instant_call_maintainer')";
        $stmt2 = $this->conn->prepare($sql2);
        $stmt2->execute();
        $stmt2->closeCursor();

        return $stmt2->rowCount();
    }

    public function getCallMaint(string $state)
    {
        $sql = "SELECT * FROM p10_notification WHERE call_maintainer = '$state'";

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
    public function updateCallMaint(array $data): int
    {
        $maintainer_arrival_time = $data["maintainer_arrival_time"];
        $digitex = $data["digitex"];
        $full_name = $data["full_name"];
        $id = $data["id"];

        $sql = "UPDATE p10_notification SET call_maintainer = 'false', maintainer_arrival_time = '$maintainer_arrival_time' WHERE digitex='$digitex' AND id='$id'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $stmt->closeCursor();

        // $sql1 = "SELECT Machine FROM p3_gamme WHERE digitex = '$digitex'";
        // $stmt1 = $this->conn->prepare($sql1);
        // $stmt1->execute();
        // $result = $stmt1->fetch();
        // $stmt1->closeCursor();

        // if ($result) {
        //     $Machine = $result['Machine'];
        // }
        
        // $sql2 = "INSERT INTO p11_intervention (Fullname, Machine, machine_ref) VALUES ('$Fullname','$Machine',(SELECT machine_ref FROM i6_prod_line WHERE digitex = '$digitex'))";
        // $stmt2 = $this->conn->prepare($sql2);
        // $stmt2->execute();
        // $stmt2->closeCursor();

        return $stmt->rowCount();
    }
}
