<?php
class TippGenau
{
    private $table_name;
    private $conn;

    private $gnt_id = [];
    private $sp_id = [];
    private $us_id = [];
    private $tipp_score = [];
    private $tipp_datum = [];
    private $tipptordiff;
    private $tippdatum;


    public function __construct($db_conn)
    {
        $this->conn = $db_conn;
        $this->table_name = "genautipps";
    }

    public function exists()
    {
        try {
            $sql = "SELECT * FROM " . $this->table_name;
            $res = $this->conn->query($sql);
            if ($res->rowCount() > 0) {
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function initGenauTipp($data)
    {
        try {
            $sql = "SELECT tipptordiff, tippdatum FROM " . $this->table_name . " WHERE stippid=? AND utippid=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($data);
            if ($stmt->rowCount() == 1) {
                $tipp = $stmt->fetch();
                $this->tipptordiff = $tipp['tipptordiff'];
                $this->tippdatum = $tipp['tippdatum'];
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
            $sql = "INSERT INTO " . $this->table_name . " (stippid, utippid, tipptordiff, tippdatum) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $res = $stmt->execute($data);
            return $res;
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function update($update_data, $spiel_id, $user_id)
    {
        try {
            $sql = "UPDATE " . $this->table_name . " SET ";
            $setFieldsValues = [];
            foreach ($update_data as $field => $value) {
                $setFieldsValues[] = "$field = :$field";
            }
            $sql .= implode(", ", $setFieldsValues);
            $whereCondition = " WHERE stippid = :spiel_id AND utippid = :user_id";
            $sql .= $whereCondition;
            $stmt = $this->conn->prepare($sql);
            foreach ($update_data as $field => $value) {
                $stmt->bindValue(":$field", $value);
            }
            $stmt->bindParam(':spiel_id', $spiel_id);
            $stmt->bindParam(':user_id', $user_id);
            $res = $stmt->execute();
            return $res;
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function not_Inserted($spiel_id, $user_id)
    {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " WHERE stippid=? AND utippid=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$spiel_id, $user_id]);
            // echo "spiel id " . $spiel_id . "<br>";
            // echo "user id " . $user_id . "<br>";
            // echo $stmt->rowCount();
            if ($stmt->rowCount() > 0) {
                return 0;
            } else {
                return 1;
            }
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function getTippGenau()
    {
        $data = $this->tipptordiff;
        return $data;
    }

    public function getTippGenauAll()
    {
        $data = array(
            'tordiff' => $this->tipptordiff,
            'datum' => $this->tippdatum
        );
        return $data;
    }
}
