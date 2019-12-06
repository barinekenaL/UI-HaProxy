<?php
    function mysqlConnect() {
        $host = '127.0.0.1';
        $port = '3307';
        $db   = 'clustDB';
        $user = 'h_user';
        $pass = 'h';
        $charset = 'utf8';

        $dsn = "mysql:host=$host;dbname=$db;port=$port;charset=$charset";
        try {
            static $pdo = null;
            if($pdo == null) {
                $pdo = new PDO($dsn, $user, $pass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            }
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
        return $pdo;
    }

    function getStudents() {
        $db = mysqlConnect();
        $stmt = $db->query("SELECT studentName from student");
        $result = array();
        while ($row = $stmt->fetch()) {
            array_push($result, $row[0]);
        }
        return $result;
    }

    function getServerId() {
        $db = mysqlConnect();
        $stmt = $db->query("SELECT @@SERVER_ID");
        $result = array();
        $sId = $stmt->fetch();
        return $sId[0];
    }

    function insert($studName) {
        $db = mysqlConnect();
        $stmt = $db->prepare("INSERT INTO student(studentName) VALUES(:nom)");
        $stmt->bindParam(':nom', $studName);
        $result = $stmt->execute();
        return $result;
    }
?>