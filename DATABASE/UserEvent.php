<?php

class UserEvent
{
    private $table_name;
    private $conn;

    private $users_id = [];
    private $event_id = [];

    public function __construct($db_conn)
    {
        $this->conn = $db_conn;
        $this->table_name = "usersevents";
    }

    public function initUserEvent($user_id)
    {
        try {
            $sql = "SELECT eventid FROM " . $this->table_name . " WHERE euserid=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$user_id]);
            if ($stmt->rowCount() >= 1) {
                $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($events as $event) {
                    array_push($this->event_id, $event['eventid']);
                }
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            return 0;
        }
    }

    // public function nullUserEvent($data)
    // {
    //     try {
    //         $sql = "SELECT DISTINCT eventid FROM " . $this->table_name . " WHERE euserid<>? AND eventid NOT IN (SELECT eventid FROM usersevents WHERE eventid=?)";
    //         $stmt = $this->conn->prepare($sql);
    //         $stmt->execute($data);
    //         if ($stmt->rowCount() >= 1) {
    //             $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //             foreach ($events as $event) {
    //                 array_push($this->event_id, $event['eventid']);
    //             }
    //             return 1;
    //         } else {
    //             return 0;
    //         }
    //     } catch (PDOException $e) {
    //         return 0;
    //     }
    // }


    public function nullUserEvent($data)
    {
        try {
            $sql = "SELECT DISTINCT eventid FROM " . $this->table_name . " WHERE eventid NOT IN (" . implode(", ", $data) . ")";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($events as $event) {
                $this->event_id[] = $event['eventid'];
            }
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function initEventUsers($event_id)
    {
        try {
            $sql = "SELECT euserid FROM " . $this->table_name . " WHERE eventid=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$event_id]);
            if ($stmt->rowCount() >= 1) {
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($users as $user) {
                    $this->users_id[] = $user['euserid'];
                }
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            return 0;
        }
    }


    // public function initEventUsers($events_ids)
    // {
    //     try {
    //         $sql = "SELECT euserid FROM " . $this->table_name . " WHERE eventid=?";
    //         $stmt = $this->conn->prepare($sql);
    //         if (count($events_ids) > 0) {
    //             foreach ($events_ids as $event_id) {
    //                 $stmt->execute([$event_id]);
    //                 if ($stmt->rowCount() >= 1) {
    //                     $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //                     foreach ($users as $user) {
    //                         $this->user_id[] = $user['euserid'];
    //                     }
    //                     $this->users_pro_event['event_' . $event_id] = $this->user_id;
    //                     $this->user_id = [];
    //                 } else {
    //                     return 0;
    //                 }
    //             }
    //             return 1;
    //         } else return 0;
    //     } catch (PDOException $e) {
    //         return 0;
    //     }
    // }


    public function insert($data)
    {
        try {
            $sql = "INSERT INTO " . $this->table_name . " (eventid, euserid) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            $res = $stmt->execute($data);
            return $res;
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function getEventId()
    {
        $data = array('user_event' => $this->event_id);
        $this->event_id = [];
        return $data;
    }

    public function getUsersId()
    {
        $data = array('event_users' => $this->users_id);
        $this->users_id = [];
        return $data;
    }

    public function connection_to_Event()
    {
        return new Event($this->conn);
    }
}
