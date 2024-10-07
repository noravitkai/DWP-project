<?php
function dbCon($user = 'root', $pass = '', $dbname = 'cinema_db', $host = 'localhost') {
    try {
        $dbCon = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        $dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbCon;
    } catch (PDOException $err) {
        echo "Error!: " . $err->getMessage() . "<br/>";
        die();
    }
}