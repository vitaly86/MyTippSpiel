<?php
class Result
{
    private $table_name;
    private $conn;

    private $result_id;
    private $result_user_id;
    private $result_event_id;
    private $score_user;
    private $arr_res_user_id = [];
    private $arr_score_user = [];

    public function __construct($db_conn)
    {
        $this->conn = $db_conn;
        $this->table_name = "results";
    }

    public function table_not_empty()
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

    public function not_exists($data)
    {
        try {
            $sql = "SELECT urid, evrid FROM " . $this->table_name;
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $result_ok = true;
            foreach ($results as $result) {
                if ($result['urid'] == $data['urid'] && $result['evrid'] == $data['evrid']) {
                    $result_ok = false;
                    break;
                }
            }
            if ($result_ok) {
                return 1;
            } else return 0;
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function initResult($data)
    {
        try {
            $sql = "SELECT score FROM " . $this->table_name . " WHERE urid=? AND evrid=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($data);
            if ($stmt->rowCount() == 1) {
                $result = $stmt->fetch();
                $this->score_user = $result['score'];
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function initOrderResults($event_id)
    {
        try {
            $sql = "SELECT urid, score FROM " . $this->table_name . " WHERE evrid=? ORDER BY score desc";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$event_id]);
            if ($stmt->rowCount() > 0) {
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($results as $result) {
                    $this->arr_res_user_id[] = $result['urid'];
                    $this->arr_score_user[] = $result['score'];
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
            $sql = "INSERT INTO " . $this->table_name . " (urid, evrid, score) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $res = $stmt->execute($data);
            return $res;
        } catch (PDOException $e) {
            return 0;
        }
    }



    public function getResult()
    {
        $data = $this->score_user;
        return $data;
    }

    public function getOrderResults()
    {
        $data = array(
            'id_users' => $this->arr_res_user_id,
            'user_score' => $this->arr_score_user
        );
        return $data;
    }
}
