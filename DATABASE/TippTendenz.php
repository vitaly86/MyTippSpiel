<?php
class TippTendenz
{
    private $table_name;
    private $conn;

    private $tdt_id = [];
    private $sp_id = [];
    private $us_id = [];
    private $tipp_A_team = [];
    private $tipp_B_team = [];
    private $tipp_datum = [];
    private $tipp_teamA;
    private $tipp_teamB;
    private $tippdatum;


    public function __construct($db_conn)
    {
        $this->conn = $db_conn;
        $this->table_name = "tendenztipps";
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

    public function initTendenzTipp($data)
    {
        try {
            $sql = "SELECT tippAteam, tippBteam, tippdatum FROM " . $this->table_name . " WHERE stippid=? AND utippid=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($data);
            if ($stmt->rowCount() == 1) {
                $tipp = $stmt->fetch();
                $this->tipp_teamA = $tipp['tippAteam'];
                $this->tipp_teamB = $tipp['tippBteam'];
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
            $sql = "INSERT INTO " . $this->table_name . " (stippid, utippid, tippdatum, tippAteam, tippBteam) VALUES (?, ?, ?, ?, ?)";
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
            if ($stmt->rowCount() > 0) {
                return 0;
            } else {
                return 1;
            }
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function getTippTendenz()
    {
        $data = array(
            'tipp_teamA' => $this->tipp_teamA,
            'tipp_teamB' => $this->tipp_teamB
        );
        return $data;
    }

    public function getTippTendenzAll()
    {
        $data = array(
            'tipp_teamA' => $this->tipp_teamA,
            'tipp_teamB' => $this->tipp_teamB,
            'datum' =>  $this->tippdatum
        );
        $this->tipp_teamA = "";
        $this->tipp_teamB = "";
        $this->tippdatum = "";
        return $data;
    }
}
