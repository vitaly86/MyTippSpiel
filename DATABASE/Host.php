<?php
class Host
{
    private $table_name;
    private $conn;

    private $h_id;
    private $h_full_name;
    private $h_name;
    private $h_email;
    private $h_password;
    private $h_foto;
    private $tordiff;
    private $winnloss;
    private $equality;

    public function __construct($db_conn)
    {
        $this->conn = $db_conn;
        $this->table_name = "hosts";
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

    public function initHost($host_id)
    {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " WHERE hid=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$host_id]);
            if ($stmt->rowCount() == 1) {
                $host = $stmt->fetch();
                $this->h_id = $host['hid'];
                $this->h_full_name = $host['hfullname'];
                $this->h_name = $host['hostname'];;
                $this->h_email = $host['email'];
                $this->h_foto = $host['foto'];
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function initHostUser($host_id)
    {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " WHERE hid=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$host_id]);
            if ($stmt->rowCount() == 1) {
                $host = $stmt->fetch();
                $this->h_id = $host['hid'];
                $this->h_full_name = $host['hfullname'];
                $this->h_name = $host['hostname'];;
                $this->h_email = $host['email'];
                $this->h_foto = $host['foto'];
                $this->tordiff = $host['tordiff'];
                $this->winnloss = $host['winnloss'];
                $this->equality = $host['equality'];
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function initHostPunkte($host_id)
    {
        try {
            $sql = "SELECT tordiff, winnloss, equality FROM " . $this->table_name . " WHERE hid=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$host_id]);
            if ($stmt->rowCount() == 1) {
                $punkte = $stmt->fetch();
                $this->tordiff = $punkte['tordiff'];
                $this->winnloss = $punkte['winnloss'];
                $this->equality = $punkte['equality'];
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
            $sql = "INSERT INTO " . $this->table_name . " (hfullname, hostname, email, passwort, foto) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $res = $stmt->execute($data);
            return $res;
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function is_HostUnique($host_name)
    {
        try {
            $sql = "SELECT hostname FROM " . $this->table_name . " WHERE hostname=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$host_name]);
            echo $stmt->rowCount();
            if ($stmt->rowCount() > 0) {
                return 0;
            } else {
                return 1;
            }
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function auth($host_name, $host_pass)
    {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " WHERE hostname=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$host_name]);
            if ($stmt->rowCount() == 1) {
                $host = $stmt->fetch();
                $db_host_id = $host['hid'];
                $db_host_fullname = $host['hfullname'];
                $db_host_name = $host['hostname'];
                $db_host_email = $host['email'];
                $db_host_pass = $host['passwort'];
                $db_host_foto = $host['foto'];
                if ($db_host_name === $host_name) {
                    if (password_verify($host_pass, $db_host_pass)) {
                        $this->h_id = $db_host_id;
                        $this->h_full_name = $db_host_fullname;
                        $this->h_name = $db_host_name;
                        $this->h_email = $db_host_email;
                        $this->h_password = $db_host_pass;
                        $this->h_foto = $db_host_foto;
                        return 1;
                    } else return 0;
                } else return 0;
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function getHost()
    {
        $data = array(
            'host_id' => $this->h_id,
            'host_fullname' => $this->h_full_name,
            'host_name' => $this->h_name,
            'host_email' => $this->h_email,
            'host_foto' => $this->h_foto
        );
        return $data;
    }

    public function getHostUser()
    {
        $data = array(
            'host_id' => $this->h_id,
            'host_fullname' => $this->h_full_name,
            'host_name' => $this->h_name,
            'host_email' => $this->h_email,
            'host_foto' => $this->h_foto,
            'host_tordiff' => $this->tordiff,
            'host_winnloss' => $this->winnloss,
            'host_equality' => $this->equality
        );
        return $data;
    }

    public function getHostPunkte()
    {
        $data = array(
            'p_tordiff' => $this->tordiff,
            'p_winnloss' => $this->winnloss,
            'p_equality' => $this->equality
        );
        return $data;
    }
}
