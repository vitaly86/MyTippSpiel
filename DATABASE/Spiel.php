<?php
class Spiel
{
    private $table_name;
    private $conn;

    private $s_id = [];
    private $s_name = [];
    private $s_datum = [];
    private $r_teamA;
    private $r_teamB;
    private $year;
    private $month;
    private $day;


    public function __construct($db_conn)
    {
        $this->conn = $db_conn;
        $this->table_name = "spiele";
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

    public function initSpiele($events_id)
    {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " WHERE espid=?";
            $stmt = $this->conn->prepare($sql);
            foreach ($events_id['user_event'] as $event_id) {
                $stmt->execute([$event_id]);
                if ($stmt->rowCount() >= 1) {
                    $spiele = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($spiele as $spiel) {
                        array_push($this->s_id, $spiel['spid']);
                        array_push($this->s_name, $spiel['spielname']);
                        array_push($this->s_datum, $spiel['spieldatum']);
                    }
                    return 1;
                } else {
                    return 0;
                }
            }
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function initSpieleAvailability($events_id)
    {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " WHERE espid=? AND YEAR(spieldatum)='2023' AND MONTH(spieldatum)>='2'";
            $stmt = $this->conn->prepare($sql);
            foreach ($events_id['user_event'] as $event_id) {
                $stmt->execute([$event_id]);
                if ($stmt->rowCount() >= 1) {
                    $spiele = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($spiele as $spiel) {
                        array_push($this->s_id, $spiel['spid']);
                        array_push($this->s_name, $spiel['spielname']);
                        array_push($this->s_datum, $spiel['spieldatum']);
                    }
                    return 1;
                } else {
                    return 0;
                }
            }
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function initSpieleLimit($datum) // $spiele_data['spiel_datum']
    {
        $this->year = substr($datum, 0, 4);
        $this->month = substr($datum, 5, 2);
        $this->day = substr($datum, 8, 2);
        return 1;
    }

    public function getTippsAvailability($limit_datum)
    {
        $spiel_datum = array(
            'year' => $this->year,
            'month' => $this->month,
            'day' => $this->day
        );

        if (($spiel_datum['year'] == $limit_datum['year']) && ($spiel_datum['month'] == $limit_datum['month']) && ($spiel_datum['day'] < $limit_datum['day'])) {
            return false;
        } else if (($spiel_datum['year'] == $limit_datum['year']) && ($spiel_datum['month'] < $limit_datum['month'])) {
            return false;
        } else if (($spiel_datum['year'] < $limit_datum['year'])) {
            return false;
        } else {
            return true;
        }
    }



    public function initSpieleEvent($event_id)
    {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " WHERE espid=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$event_id]);
            if ($stmt->rowCount() >= 1) {
                $spiele = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($spiele as $spiel) {
                    array_push($this->s_id, $spiel['spid']);
                    array_push($this->s_name, $spiel['spielname']);
                    array_push($this->s_datum, $spiel['spieldatum']);
                }
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function initSpieleResults($event_id)
    {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " WHERE espid=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$event_id]);
            if ($stmt->rowCount() >= 1) {
                $spiele = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($spiele as $spiel) {
                    array_push($this->s_id, $spiel['spid']);
                    array_push($this->s_name, $spiel['spielname']);
                    array_push($this->s_datum, $spiel['spieldatum']);
                    $this->r_teamA[] = $spiel['teamAresult'];
                    $this->r_teamB[] = $spiel['teamBresult'];
                }
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function initSpieleExists($data)
    {
        try {
            $sql = "SELECT COUNT(*) AS 'user_spiele' FROM " . $this->table_name . " WHERE espid=? AND spieldatum>?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($data);
            if ($stmt->rowCount() == 1) {
                $spiele = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if ($spiele[0]['user_spiele'] > 0) {
                    return 1;
                } else return 0;
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
            $sql = "INSERT INTO " . $this->table_name . " (espid, spielname, spieldatum) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $res = $stmt->execute($data);
            return $res;
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function is_SpielUnique($spiel_name, $event_id)
    {
        try {
            $sql = "SELECT spielname FROM " . $this->table_name . " WHERE spielname=? AND espid=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$spiel_name, $event_id]);
            if ($stmt->rowCount() > 0) {
                return 0;
            } else {
                return 1;
            }
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function getSpielData()
    {
        $data = array(
            'spiel_id' => $this->s_id,
            'spiel_name' => $this->s_name,
            'spiel_datum' => $this->s_datum
        );
        $this->s_id = [];
        $this->s_name = [];
        $this->s_datum = [];
        return $data;
    }

    public function getSpieleResults()
    {
        $data = array(
            'spiel_id' => $this->s_id,
            'spiel_name' => $this->s_name,
            'spiel_datum' => $this->s_datum,
            'result_A' => $this->r_teamA,
            'result_B' => $this->r_teamB
        );
        $this->s_id = [];
        $this->s_name = [];
        $this->s_datum = [];
        return $data;
    }

    public function getSpielLimitData()
    {
        $data = array(
            'year' => $this->year,
            'month' => $this->month,
            'day' => $this->day
        );
        return $data;
    }
}
