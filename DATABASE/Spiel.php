<?php
class Spiel
{
    private $table_name;
    private $conn;

    private $s_id = [];
    private $s_name = [];
    private $s_datum = [];

    private $spiel_name;
    private $spiel_datum;
    private $min_spiel_datum;
    private $max_spiel_datum;
    private $r_teamA;
    private $r_teamB;
    private $current_date = "2023-05-12 23:30:01";

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

    public function init_one_Spiel($spiel_id)
    {
        try {
            $sql = "SELECT spielname, spieldatum FROM " . $this->table_name . " WHERE spid=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$spiel_id]);
            if ($stmt->rowCount() == 1) {
                $spiel = $stmt->fetch();
                $this->spiel_name = $spiel['spielname'];
                $this->spiel_datum = $spiel['spieldatum'];
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

    public function getTippsAvailability($limit_datum)
    {
        if ($limit_datum < $this->current_date) {
            return false;
        }
        return true;
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

    public function verifySpieleEvent($event_id)
    {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " WHERE espid=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$event_id]);
            if ($stmt->rowCount() > 0) {
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function get_min_SpielDatum($event_id)
    {
        try {
            $sql = "SELECT DISTINCT MIN(spieldatum) AS min_datum FROM " . $this->table_name . " WHERE espid=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$event_id]);
            if ($stmt->rowCount() == 1) {
                $this->min_spiel_datum = $stmt->fetch()['min_datum'];
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function get_max_SpielDatum($event_id)
    {
        try {
            $sql = "SELECT DISTINCT MAX(spieldatum) AS max_datum FROM " . $this->table_name . " WHERE espid=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$event_id]);
            if ($stmt->rowCount() == 1) {
                $this->max_spiel_datum = $stmt->fetch()['max_datum'];
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function get_debut_Event($max_enroll_datum)
    {
        if ($max_enroll_datum > $this->current_date) {
            return true;
        } else return false;
    }

    public function get_zietraum_Event(
        $min_datum,
        $max_datum
    ) {
        if ($min_datum > $this->current_date) {
            return 'enroll';
        } else if (($min_datum < $this->current_date) && ($max_datum > $this->current_date)) {
            return "innerhalb";
        } else if (($max_datum < $this->current_date)) {
            return "ergebnisse";
        }
    }


    public function initSpieleExists($event_id)
    {
        try {
            $sql = "SELECT COUNT(*) AS 'user_spiele' FROM " . $this->table_name . " WHERE espid=? AND spieldatum>?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$event_id, $this->current_date]);
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

    public function get_one_SpielData()
    {
        $data = array(
            'spiel_name' => $this->spiel_name,
            'spiel_datum' => $this->spiel_datum
        );
        return $data;
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

    public function showSpielminDatum()
    {
        return $this->min_spiel_datum;
    }

    public function showSpielmaxDatum()
    {
        return $this->max_spiel_datum;
    }
}
