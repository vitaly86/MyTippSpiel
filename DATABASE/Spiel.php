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
    private $spiele_count = 0;
    private $r_teamA;
    private $r_teamB;
    private $current_date = "2024-01-25 18:00:01";

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

    public function existsOne($event_id)
    {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " WHERE espid=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$event_id]);
            echo "<br>Res Value<br>";
            if ($stmt->rowCount() == 1) {
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function existsTwo($event_id)
    {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " WHERE espid=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$event_id]);
            if ($stmt->rowCount() > 1) {
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
            $sql = "SELECT spielname, spieldatum FROM " . $this->table_name . " WHERE espid=?";
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


    public function initSpieleExpired($event_id)
    {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " WHERE espid=? AND spieldatum<?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$event_id, $this->current_date]);
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

    public function initSpieleEventHost($event_id)
    {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " WHERE espid=? ORDER BY spieldatum DESC";
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

    public function initSpieleResultsExpired($event_id)
    {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " WHERE espid=? AND spieldatum<? ORDER BY spieldatum DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$event_id, $this->current_date]);
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

    public function find_min_SpielDatum($event_id)
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

    public function find_max_SpielDatum($event_id)
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

    public function checkedBeginnEvent($spiel_datum)
    {
        if ($this->current_date <= $spiel_datum) {
            return true;
        } else return false;
    }

    public function checkedLaufenEvent($spiel_datum)
    {
        if (($this->min_spiel_datum < $spiel_datum) &&
            ($this->min_spiel_datum < $this->current_date)
        ) {
            $current_date = new DateTime($this->current_date);
            $trans_spiel_datum = new DateTime($spiel_datum);
            if ($current_date > $trans_spiel_datum) {
                return true;
            } else return false;
        } else return false;
    }

    public function checkedAbgelaufenEvent($spiel_datum)
    {
        if (($this->max_spiel_datum < $spiel_datum) && ($this->max_spiel_datum < $this->current_date)) {
            return true;
        } else return false;
    }

    public function showEventHost()
    {
        if (($this->min_spiel_datum <= $this->current_date) && ($this->min_spiel_datum != "")) {
            $this->spiele_count += 1;
            return true;
        } else return false;
    }

    public function checkEventsExist()
    {
        if ($this->spiele_count > 0) {
            return true;
        } else return false;
    }

    public function getCountEvents()
    {
        return $this->spiele_count;
    }

    public function get_Tipps_Available($max_enroll_datum)
    {
        if ($max_enroll_datum > $this->current_date) {
            return true;
        } else return false;
    }

    public function get_zeitraum_Event(
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

    public function get_ValidDatum($tipp_datum)
    {
        if ($tipp_datum < $this->spiel_datum) {
            return true;
        } else return false;
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
        $this->r_teamA = [];
        $this->r_teamB = [];
        return $data;
    }

    public function getEventStart()
    {
        return $this->min_spiel_datum;
    }

    public function getEventEnde()
    {
        $get_end_event = new DateTime($this->max_spiel_datum);
        $get_end_event->modify('+3 hours');
        return $get_end_event->format('Y-m-d H:i:s');
    }
}
