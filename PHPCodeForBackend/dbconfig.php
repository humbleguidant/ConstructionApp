<?php
/* 
Author: Aubrey Nickerson
Date: September 24th, 2020
Program: dbconfig.php
Project: Construction App

This is the db configuration script
*/
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


