<?php
function dbCon($user = 'root', $pass = '', $dbname = 'cinema_db', $host = 'localhost') {
    try {
        $dbCon = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        $dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbCon->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $dbCon;
    } catch (PDOException $err) {
        error_log("Database connection error: " . $err->getMessage(), 0);
        die('Database connection failed. Please try again later.');
    }
}