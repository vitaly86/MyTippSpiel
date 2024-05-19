<?php

class UserHost
{
    private $table_name;
    private $conn;

    private $user_id;
    private $host_id = [];

    public function __construct($db_conn)
    {
        $this->conn = $db_conn;
        $this->table_name = "usershosts";
    }

    public function initUserHost($user_id)
    {
        try {
            $sql = "SELECT hostid FROM " . $this->table_name . " WHERE huserid=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$user_id]);
            if ($stmt->rowCount() >= 1) {
                $hosts = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($hosts as $host) {
                    $this->host_id[] = $host['hostid'];
                }
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            return 0;
        }
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

    public function getHostId()
    {
        $data = array('user_host' => $this->host_id);
        $this->host_id = [];
        return $data;
    }
}
