<?php
class Ergebniss
{
    private $table_name;
    private $conn;

    private $erg_Team_A;
    private $erg_Team_B;


    public function __construct($db_conn)
    {
        $this->conn = $db_conn;
        $this->table_name = "spiele";
    }


    // public function initSpiele($events_id)
    // {
    //     try {
    //         $sql = "SELECT * FROM " . $this->table_name . " WHERE espid=?";
    //         $stmt = $this->conn->prepare($sql);
    //         foreach ($events_id['user_event'] as $event_id) {
    //             $stmt->execute([$event_id]);
    //             if ($stmt->rowCount() >= 1) {
    //                 $spiele = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //                 foreach ($spiele as $spiel) {
    //                     array_push($this->s_id, $spiel['spid']);
    //                     array_push($this->s_name, $spiel['spielname']);
    //                     array_push($this->s_datum, $spiel['spieldatum']);
    //                 }
    //                 return 1;
    //             } else {
    //                 return 0;
    //             }
    //         }
    //     } catch (PDOException $e) {
    //         return 0;
    //     }
    // }

    // public function initSpieleEvent($event_id)
    // {
    //     try {
    //         $sql = "SELECT * FROM " . $this->table_name . " WHERE espid=?";
    //         $stmt = $this->conn->prepare($sql);
    //         $stmt->execute([$event_id]);
    //         if ($stmt->rowCount() >= 1) {
    //             $spiele = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //             foreach ($spiele as $spiel) {
    //                 array_push($this->s_id, $spiel['spid']);
    //                 array_push($this->s_name, $spiel['spielname']);
    //                 array_push($this->s_datum, $spiel['spieldatum']);
    //             }
    //             return 1;
    //         } else {
    //             return 0;
    //         }
    //     } catch (PDOException $e) {
    //         return 0;
    //     }
    // }

    public function insert($data, $spiel_id, $event_id)
    {
        try {
            $sql = "UPDATE " . $this->table_name . " SET ";
            $setFieldsValues = [];
            foreach ($data as $field => $value) {
                $setFieldsValues[] = "$field = :$field";
            }
            $sql .= implode(", ", $setFieldsValues);
            $whereCondition = " WHERE spid = :spiel_id AND espid = :event_id";
            $sql .= $whereCondition;
            $stmt = $this->conn->prepare($sql);
            foreach ($data as $field => $value) {
                $stmt->bindValue(":$field", $value);
            }
            $stmt->bindParam(':spiel_id', $spiel_id);
            $stmt->bindParam(':event_id', $event_id);
            $res = $stmt->execute();
            return $res;
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function not_Inserted($data)
    {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " WHERE spid=? AND espid=? AND teamAresult is ? AND teamBresult is ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($data);
            if ($stmt->rowCount() == 1) {
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            return 0;
        }
    }


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
