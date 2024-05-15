<?php
class Schema
{
    public static $check_schema_exists = false;

    public static function connect($servername, $username, $password)
    {
        try {
            $conn = new PDO("mysql:host=$servername", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully<br>";
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
        return $conn;
    }

    public static function createDB($servername, $username, $password, $dbname)
    {
        try {
            $conn = self::connect($servername, $username, $password);
            $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
            $conn->exec($sql);
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
        $conn = null;
    }

    public static function createHosts($servername, $username, $password, $dbname)
    {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        $sql = "CREATE TABLE IF NOT EXISTS Hosts (
  hid INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  hfullname VARCHAR(30) NOT NULL,
  hostname VARCHAR(30) NOT NULL,
  email VARCHAR(50) NOT NULL,
  passwort VARCHAR(100) NOT NULL, 
  foto VARCHAR(30) DEFAULT NULL, 
  tordiff INT(20) DEFAULT NULL, 
  winnloss INT(20) DEFAULT NULL,
  equality INT(20) DEFAULT NULL
  )";
        $conn->exec($sql);
        $conn = null;
    }

    public static function createEvents($servername, $username, $password, $dbname)
    {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        $sql = "CREATE TABLE IF NOT EXISTS Events (
  eid INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  heid INT(6) UNSIGNED NOT NULL,
  ename VARCHAR(200) NOT NULL
  )";
        $conn->exec($sql);
        $conn = null;
    }

    public static function createUsers($servername, $username, $password, $dbname)
    {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        $sql = "CREATE TABLE IF NOT EXISTS Users (
  userid INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(30) NOT NULL,
  useremail VARCHAR(50) NOT NULL,
  upasswort VARCHAR(100) NOT NULL 
  )";
        $conn->exec($sql);
        $conn = null;
    }

    public static function createGames($servername, $username, $password, $dbname)
    {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        $sql = "CREATE TABLE IF NOT EXISTS Spiele (
  spid INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  espid INT(6) UNSIGNED NOT NULL,
  spielname VARCHAR(100) NOT NULL,
  spieldatum TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  teamAresult INT(20) DEFAULT NULL,
  teamBresult INT(20) DEFAULT NULL
  )";
        $conn->exec($sql);
        $conn = null;
    }

    public static function createTippsGenau($servername, $username, $password, $dbname)
    {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        $sql = "CREATE TABLE IF NOT EXISTS GenauTipps (
  gntippid INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  stippid INT(6) UNSIGNED NOT NULL,
  utippid INT(6) UNSIGNED NOT NULL,
  tipptordiff INT(20) NOT NULL,
  tippdatum TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
  )";
        $conn->exec($sql);
        $conn = null;
    }

    public static function createTippsTendenz($servername, $username, $password, $dbname)
    {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        $sql = "CREATE TABLE IF NOT EXISTS TendenzTipps (
  tdtippid INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  stippid INT(6) UNSIGNED NOT NULL,
  utippid INT(6) UNSIGNED NOT NULL,
  tippAteam ENUM('sieg', 'niederlage', 'unentschieden') NOT NULL,
  tippBteam ENUM('sieg', 'niederlage', 'unentschieden') NOT NULL,
  tippdatum TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
  )";
        $conn->exec($sql);
        $conn = null;
    }

    public static function createResults($servername, $username, $password, $dbname)
    {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        $sql = "CREATE TABLE IF NOT EXISTS Results (
  rid INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  urid INT(6) UNSIGNED NOT NULL,
  evrid INT(6) UNSIGNED NOT NULL,
  score INT(100) NOT NULL
  )";
        $conn->exec($sql);
        $conn = null;
    }

    public static function createUsersHosts($servername, $username, $password, $dbname)
    {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        $sql = "CREATE TABLE IF NOT EXISTS UsersHosts (
  hostid INT(6) UNSIGNED NOT NULL,
  huserid INT(6) UNSIGNED NOT NULL,
  PRIMARY KEY (hostid, huserid)
  )";
        $conn->exec($sql);
        $conn = null;
    }

    public static function createUsersEvents($servername, $username, $password, $dbname)
    {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        $sql = "CREATE TABLE IF NOT EXISTS UsersEvents (
  eventid INT(6) UNSIGNED NOT NULL,
  euserid INT(6) UNSIGNED NOT NULL,
  PRIMARY KEY (eventid, euserid)
  )";
        $conn->exec($sql);
        $conn = null;
    }

    public static function createForeignKeys($servername, $username, $password, $dbname)
    {

        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        $sql_events_host = "ALTER TABLE Events
ADD CONSTRAINT FK_HostEvents
FOREIGN KEY (heid)
REFERENCES Hosts (hid)
ON UPDATE CASCADE";


        $sql_events_spiele = "ALTER TABLE Spiele
ADD CONSTRAINT FK_EventSpiele
FOREIGN KEY (espid)
REFERENCES Events (eid)
ON UPDATE CASCADE";


        $sql_spiele_tipps_genau = "ALTER TABLE GenauTipps
ADD CONSTRAINT FK_SpieleTippsGenau
FOREIGN KEY (stippid)
REFERENCES Spiele (spid)
ON UPDATE CASCADE";


        $sql_user_tipps_genau = "ALTER TABLE GenauTipps
ADD CONSTRAINT FK_UserTippsGenau
FOREIGN KEY (utippid)
REFERENCES Users (userid)
ON UPDATE CASCADE";

        $sql_spiele_tipps_tendenz = "ALTER TABLE TendenzTipps
ADD CONSTRAINT FK_SpieleTippsTendenz
FOREIGN KEY (stippid)
REFERENCES Spiele (spid)
ON UPDATE CASCADE";


        $sql_user_tipps_tendenz = "ALTER TABLE TendenzTipps
ADD CONSTRAINT FK_UserTippsTendenz
FOREIGN KEY (utippid)
REFERENCES Users (userid)
ON UPDATE CASCADE";


        $sql_user_results = "ALTER TABLE Results
ADD CONSTRAINT FK_UserResults
FOREIGN KEY (urid)
REFERENCES Users (userid)
ON UPDATE CASCADE";


        $sql_event_results = "ALTER TABLE Results
ADD CONSTRAINT FK_EventResults
FOREIGN KEY (evrid)
REFERENCES Events (eid)
ON UPDATE CASCADE";


        $sql_hosts_users = "ALTER TABLE UsersHosts
ADD CONSTRAINT FK_Hosts
FOREIGN KEY (hostid)
REFERENCES Hosts (hid)
ON UPDATE CASCADE";


        $sql_users_hosts = "ALTER TABLE UsersHosts
ADD CONSTRAINT FK_UsersHosts
FOREIGN KEY (huserid)
REFERENCES Users (userid)
ON UPDATE CASCADE";


        $sql_events_users = "ALTER TABLE UsersEvents
ADD CONSTRAINT FK_Events
FOREIGN KEY (eventid)
REFERENCES Events (eid)
ON UPDATE CASCADE";


        $sql_users_events = "ALTER TABLE UsersEvents
ADD CONSTRAINT FK_UsersEvents
FOREIGN KEY (euserid)
REFERENCES Users (userid)
ON UPDATE CASCADE";

        $conn->exec($sql_events_host);
        $conn->exec($sql_events_spiele);
        $conn->exec($sql_spiele_tipps_genau);
        $conn->exec($sql_user_tipps_genau);
        $conn->exec($sql_spiele_tipps_tendenz);
        $conn->exec($sql_user_tipps_tendenz);
        $conn->exec($sql_user_results);
        $conn->exec($sql_event_results);
        $conn->exec($sql_hosts_users);
        $conn->exec($sql_users_hosts);
        $conn->exec($sql_events_users);
        $conn->exec($sql_users_events);

        $conn = null;
    }

    public function __construct($servername, $username, $password, $dbname)
    {
        self::createDB($servername, $username, $password, $dbname);
        self::createHosts($servername, $username, $password, $dbname);
        self::createEvents($servername, $username, $password, $dbname);
        self::createUsers($servername, $username, $password, $dbname);
        self::createGames($servername, $username, $password, $dbname);
        self::createTippsGenau($servername, $username, $password, $dbname);
        self::createTippsTendenz($servername, $username, $password, $dbname);
        self::createResults($servername, $username, $password, $dbname);
        self::createUsersHosts($servername, $username, $password, $dbname);
        self::createUsersEvents($servername, $username, $password, $dbname);
        self::createForeignKeys($servername, $username, $password, $dbname);
        self::schema_setup();
    }

    public static function schema_setup()
    {
        $settingsPath1 = "../Controller/schema_settings.txt";
        $settingsPath2 = "./Controller/schema_settings.txt";
        $settingsPath = file_exists($settingsPath1) ? $settingsPath1 : $settingsPath2;
        $settingsFile = fopen($settingsPath, "w") or die("Unable to open file");
        clearstatcache();
        if (!filesize($settingsPath)) {
            $flag = "Foreign Keys: 1";
            echo "Database created successfully<br>";
            echo "Table Hosts created <br>";
            echo "Table Events created <br>";
            echo "Table Users created <br>";
            echo "Table Games created <br>";
            echo "Table Tipps Genau created <br>";
            echo "Table Tipps Tendenz created <br>";
            echo "Table Results created <br>";
            echo "Table UsersHosts created <br>";
            echo "Table UsersEvents created <br>";
            echo "Foreign Keys created <br>";
            fwrite($settingsFile, $flag);
            fclose($settingsFile);
        } else {
            echo "";
        }
    }

    public static function verify_create_schema()
    {
        $settingsPath1 = "../Controller/schema_settings.txt";
        $settingsPath2 = "./Controller/schema_settings.txt";
        $settingsPath = file_exists($settingsPath1) ? $settingsPath1 : $settingsPath2;
        clearstatcache();

        $content_settings = file($settingsPath);
        $check = substr(implode(" ", $content_settings), -1);
        if ($check === "1") {
            echo "Connected successfully";
            self::$check_schema_exists = true;
        }
    }
}
