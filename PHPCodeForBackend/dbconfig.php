<?php
header("Access-Control-Allow-Origin: *");
class Dbconfig {
    protected $serverName;
    protected $userName;
    protected $passCode;
    protected $dbName;

    function Dbconfig() {
        $this -> serverName = 'localhost';
        $this -> userName = 'cosc470student';
        $this -> passCode = 'COSC470202028Sept';
        $this -> dbName = 'AppDB';
    }
}


