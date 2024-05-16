<?php
class User
{
    private $table_name;
    private $conn;

    private $u_id;
    private $u_name;
    private $u_email;
    private $u_password;
    private $u_event;
    private $u_host;
    private $all_u_ids = [];
    private $all_u_names = [];
    private $all_u_emails = [];

    public function __construct($db_conn)
    {
        $this->conn = $db_conn;
        $this->table_name = "users";
        $this->u_event = new UserEvent($this->conn);
        $this->u_host = new UserHost($this->conn);
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

    public function initUser($user_id)
    {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " WHERE userid=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$user_id]);
            if ($stmt->rowCount() == 1) {
                $user = $stmt->fetch();
                $this->u_id = $user['userid'];
                $this->u_name = $user['username'];
                $this->u_email = $user['useremail'];
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function initAllUsersProEvents($users_ids)
    {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " WHERE userid=?";
            $stmt = $this->conn->prepare($sql);
            if (count($users_ids) > 0) {
                foreach ($users_ids as $user_id) {
                    $stmt->execute([$user_id]);
                    if ($stmt->rowCount() == 1) {
                        $user = $stmt->fetch();
                        $this->all_u_ids[] = $user['userid'];
                        $this->all_u_names[] = $user['username'];
                        $this->all_u_emails[] = $user['useremail'];
                    } else return 0;
                }
                return 1;
            } else return 0;
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function insert($data)
    {
        try {
            $sql = "INSERT INTO " . $this->table_name . " (username, useremail, upasswort) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $res = $stmt->execute($data);
            return $res;
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function is_UserUnique($user_email)
    {
        try {
            $sql = "SELECT useremail FROM " . $this->table_name . " WHERE useremail=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$user_email]);
            if ($stmt->rowCount() > 0) {
                return 0;
            } else {
                return 1;
            }
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function get_CurrentUser($user_email)
    {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " WHERE useremail = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$user_email]);
            if ($stmt->rowCount() == 1) {
                $user = $stmt->fetch();
                $this->u_id = $user['userid'];
                $this->u_name = $user['username'];
                $this->u_email = $user['useremail'];
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function auth($user_email, $user_pass)
    {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " WHERE useremail=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$user_email]);
            if ($stmt->rowCount() == 1) {
                $user = $stmt->fetch();
                $db_user_id = $user['userid'];
                $db_user_name = $user['username'];
                $db_user_email = $user['useremail'];
                $db_user_pass = $user['upasswort'];
                if ($db_user_email === $user_email) {
                    if (password_verify($user_pass, $db_user_pass)) {
                        $this->u_id = $db_user_id;
                        $this->u_name = $db_user_name;
                        $this->u_email = $db_user_email;
                        $this->u_password = $db_user_pass;
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

    public function getUser()
    {
        $data = array(
            'user_id' => $this->u_id,
            'user_name' => $this->u_name,
            'user_email' => $this->u_email
        );
        return $data;
    }

    public function getAllUsers()
    {
        $data = array(
            'users_ids' => $this->all_u_ids,
            'users_names' => $this->all_u_names,
            'users_emails' => $this->all_u_emails
        );
        $this->all_u_ids = [];
        $this->all_u_names = [];
        $this->all_u_emails = [];
        return $data;
    }

    public function get_UserEvent()
    {
        return $this->u_event;
    }

    public function get_UserHost()
    {
        return  $this->u_host;
    }
}
