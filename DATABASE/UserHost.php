<?php

class UserHost
{
    private $table_name;
    private $conn;

    private $user_id;
    private $host_id;

    public function __construct($db_conn)
    {
        $this->conn = $db_conn;
        $this->table_name = "usershosts";
    }


    public function insert($data)
    {
        try {
            $sql = "INSERT INTO " . $this->table_name . " (hostid, huserid) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            $res = $stmt->execute($data);
            return $res;
        } catch (PDOException $e) {
            return 0;
        }
    }
}
