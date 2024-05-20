<?php
class Einstellung
{
    private $table_name;
    private $conn;

    private $tordiff;
    private $winnloss;
    private $equality;


    public function __construct($db_conn)
    {
        $this->conn = $db_conn;
        $this->table_name = "hosts";
    }

    public function insert($data, $host_id, $host_name)
    {
        try {
            $sql = "UPDATE " . $this->table_name . " SET ";
            $setFieldsValues = [];
            foreach ($data as $field => $value) {
                $setFieldsValues[] = "$field = :$field";
            }
            $sql .= implode(", ", $setFieldsValues);
            $whereCondition = " WHERE hid = :host_id AND hostname = :host_name";
            $sql .= $whereCondition;
            $stmt = $this->conn->prepare($sql);
            foreach ($data as $field => $value) {
                $stmt->bindValue(":$field", $value);
            }
            $stmt->bindParam(':host_id', $host_id);
            $stmt->bindParam(':host_name', $host_name);
            $res = $stmt->execute();
            return $res;
        } catch (PDOException $e) {
            return 0;
        }
    }

    // public function not_Inserted($data)
    // {
    //     try {
    //         $sql = "SELECT * FROM " . $this->table_name . " WHERE hid=? AND hostname=? AND tordiff is ? AND winnloss is ? AND equality is ?";
    //         $stmt = $this->conn->prepare($sql);
    //         $stmt->execute($data);
    //         if ($stmt->rowCount() == 1) {
    //             return 1;
    //         } else {
    //             return 0;
    //         }
    //     } catch (PDOException $e) {
    //         return 0;
    //     }
    // }


    // public function getSpielData()
    // {
    //     $data = array(
    //         'spiel_id' => $this->s_id,
    //         'spiel_name' => $this->s_name,
    //         'spiel_datum' => $this->s_datum
    //     );
    //     return $data;
    // }
}
