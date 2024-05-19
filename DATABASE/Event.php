<?php
class Event
{
    private $table_name;
    private $conn;

    private $e_id = [];
    private $e_name = [];
    private $e_host;

    private $null_e_id = [];

    public function __construct($db_conn)
    {
        $this->conn = $db_conn;
        $this->table_name = "events";
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

    public function initEvent($host_id)
    {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " WHERE heid=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$host_id]);
            if ($stmt->rowCount() >= 1) {
                $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($events as $event) {
                    array_push($this->e_id, $event['eid']);
                    array_push($this->e_name, $event['ename']);
                }
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            return 0;
        }
    }

    /*******************************************Tomorow*************************************************************** */
    public function initEventHost($host_id, $data)
    {
        try {
            $sql = "SELECT eid, ename FROM " . $this->table_name . " WHERE heid=? AND eid IN (" . implode(", ", $data) . ")";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$host_id]);
            if (count($data) >= 1) {
                $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($events as $event) {
                    $this->e_id[] = $event['eid'];
                    $this->e_name[] = $event['ename'];
                }
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            return 0;
        }
    }

    /********************************************************************************************************************** */

    public function initEventUser($events_id)
    {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " WHERE eid=?";
            $stmt = $this->conn->prepare($sql);
            if (count($events_id['user_event']) > 0) {
                foreach ($events_id['user_event'] as $event_id) {
                    $stmt->execute([$event_id]);
                    if ($stmt->rowCount() == 1) {
                        $event = $stmt->fetch();
                        array_push($this->e_id, $event['eid']);
                        array_push($this->e_name, $event['ename']);
                    }
                }
                return 1;
            } else return 0;
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function find_null_UserEvents($data)
    {
        try {
            $sql = "SELECT eid FROM " . $this->table_name . " WHERE eid NOT IN (" . implode(", ", $data) . ")";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($events as $event) {
                $this->null_e_id[] = $event['eid'];
            }
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function nullEventUser($events_id)
    {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " WHERE eid=?";
            $stmt = $this->conn->prepare($sql);
            if (count($events_id) > 0) {
                foreach ($events_id as $event_id) {
                    $stmt->execute([$event_id]);
                    if ($stmt->rowCount() == 1) {
                        $event = $stmt->fetch();
                        array_push($this->e_id, $event['eid']);
                        array_push($this->e_name, $event['ename']);
                    }
                }
                return 1;
            } else return 0;
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function initEventSpiele($events_id)                                 // verification after
    {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " WHERE eid=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$events_id]);
            if ($stmt->rowCount() == 1) {
                $event = $stmt->fetch();
                $this->e_id = $event['eid'];
                $this->e_name = $event['ename'];
                $this->e_host = $event['heid'];
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function init_null_EventsUser($events_id)
    {
        try {
            $sql = "SELECT eid, ename FROM " . $this->table_name . " WHERE eid=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$events_id]);
            if ($stmt->rowCount() == 1) {
                $event = $stmt->fetch();
                $this->e_id = $event['eid'];
                $this->e_name = $event['ename'];
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
            $sql = "INSERT INTO " . $this->table_name . " (heid, ename) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            $res = $stmt->execute($data);
            return $res;
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function is_EventUnique($event_name)
    {
        try {
            $sql = "SELECT ename FROM " . $this->table_name . " WHERE ename=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$event_name]);
            if ($stmt->rowCount() > 0) {
                return 0;
            } else {
                return 1;
            }
        } catch (PDOException $e) {
            return 0;
        }
    }


    // public function getEventsHost()
    // {
    //     $events = array(
    //         'event_id' => $this->e_id,
    //         'event_name' => $this->e_name
    //     );
    //     // return $data;
    //     $ul = "<ul id='list_events'>";
    //     for ($i = 0; $i < count($events['event_id']); $i++) {
    //         $event_spiele = $events['event_id'][$i];
    //         $ul .= "<li>" . $events['event_id'][$i] . ". " .
    //             "<a href=" . "hinzufuegen_spiele.php?event_id=$event_spiele>" .
    //             $events['event_name'][$i] . "</a></li>";
    //     }
    //     $ul .= "</ul>";
    //     echo $ul;
    // }

    public function getEventsHost()
    {
        $events = array(
            'event_id' => $this->e_id,
            'event_name' => $this->e_name
        );
        return $events;
    }

    public function getHostEvents()
    {
        $events = array(
            'event_id' => $this->e_id,
            'event_name' => $this->e_name
        );
        return $events;
    }

    public function get_null_UserEvents()
    {
        return $this->null_e_id;
    }

    public function getEventSpiele()
    {
        $events = array(
            'event_id' => $this->e_id,
            'event_name' => $this->e_name,
            'event_host' => $this->e_host
        );
        return $events;
    }

    public function startPageEvents()
    {
        try {
            $sql = "SELECT * FROM " . $this->table_name;
            $events = $this->conn->query($sql);
            if ($events->rowCount() > 0) {
                foreach ($events as $event) {
                    array_push($this->e_id, $event['eid']);
                    array_push($this->e_name, $event['ename']);
                }
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function getEventsStart()
    {
        $events = array(
            'event_id' => $this->e_id,
            'event_name' => $this->e_name
        );
        return $events;
    }


    public function getUserEvents()
    {
        $data = array(
            'event_id' => $this->e_id,
            'event_name' => $this->e_name
        );
        return $data;
    }

    public function null_data_UserEvents()
    {
        $data = array(
            'event_id' => $this->e_id,
            'event_name' => $this->e_name
        );
        return $data;
    }

    public function retrieveHost($event_id)
    {
        try {
            $sql = "SELECT heid FROM " . $this->table_name . " WHERE eid=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$event_id]);
            if ($stmt->rowCount() == 1) {
                $event_host = $stmt->fetch();
                $this->e_host = $event_host['heid'];
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function retainHost()
    {
        $current_host = $this->e_host;
        return $current_host;
    }
}
