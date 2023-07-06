<?php


class Import
{
    private PDO $conn2;

    public function __construct(Database $database)
    {
        $this->conn2 = $database->getConnection();
    }

    public function getPacketprod_line(): array | string
    {
        $sql = "select prod_line from p2_packet";

        $stmt = $this->conn2->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll();
        $stmt->closeCursor();

        if (!$results) {
            http_response_code(400);

            return "{}";
        }

        return $results;
    }

    public function getmodelByprod_line(string $prod_lineName): array | string
    {
        $sql = "select model from p2_packet WHERE prod_line LIKE '$prod_lineName%'";

        $stmt = $this->conn2->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll();
        $stmt->closeCursor();

        if (!$results) {
            http_response_code(400);

            return "{}";
        }

        return $results;
    }

    public function getPacketByof_num(string $modelName): array | string
    {
        $sql = "select DISTINCT(of_num) from p2_packet WHERE model ='$modelName'";

        $stmt = $this->conn2->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll();
        $stmt->closeCursor();

        if (!$results) {
            http_response_code(400);

            return "{}";
        }

        return $results;
    }
    
}
