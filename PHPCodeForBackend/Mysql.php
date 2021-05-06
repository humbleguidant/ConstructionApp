<?php
/* 
Author: Aubrey Nickerson
Date: September 25th, 2020
Program: Mysql.php
Project: Construction App

This php class communicates with a MySQL database.
It grabs the credentials from the dbconfig.php and 
makes a connection to the database. Each function 
performs a certain query whether its an INSERT or 
SELECT query. 
*/
include("dbconfig.php");

class Mysql extends Dbconfig{

    // Create instance variables
    // connection status
    public $connectionString;
    // data set from select query results
    public $dataSet;
    // sql query string
    private $sqlQuery;
    // sql result
    public $result;

    // database credentials
    protected $databaseName;
    protected $hostName;
    protected $userName;
    protected $passCode;

    // Grab the credentials from Dbconfig.
    function Mysql()    {
        $this->connectionString = NULL;
        $this->sqlQuery = NULL;
        $this->dataSet = NULL;
        $this->result = NULL;
    
        $dbPara = new Dbconfig();
        $this->databaseName = $dbPara->dbName;
        $this->hostName = $dbPara->serverName;
        $this->userName = $dbPara->userName;
        $this->passCode = $dbPara->passCode;
        $dbPara = NULL;
    }

    // Make a connection to database.
    function dbConnect()    {
        $this->connectionString = mysqli_connect($this -> hostName, $this -> userName, $this -> passCode, $this -> databaseName);
        return $this->connectionString;
    }

    // Disconnect from database
    function dbDisconnect() {
        $this->connectionString = NULL;
        $this->sqlQuery = NULL;
        $this->dataSet = NULL;
        $this->databaseName = NULL;
        $this->hostName = NULL;
        $this->userName = NULL;
        $this->passCode = NULL;
    }

    // Creates a new employee or administrator into the EMPLOYEES table
    function insertQuery($email, $empPasswd, $empPosition, $acceptCondition){
        $this->sqlQuery = "SELECT * FROM EMPLOYEES
                           WHERE EMAIL = '".$email."'";
        $this->result = mysqli_query($this->connectionString, $this->sqlQuery);
        $num_result = $this->result->num_rows;
        if($num_result == 1) {
            return NULL;
        }
        $hashPassword = password_hash($empPasswd, PASSWORD_ARGON2I);
        $this->sqlQuery = "INSERT INTO EMPLOYEES VALUES('".$email."', '".$hashPassword."', '".$empPosition."', '".$acceptCondition."')";
        $this->result = mysqli_query($this->connectionString, $this->sqlQuery);
        return $this->result;
    }

    // Creates a new form into the FORMS table
    function insertForm($email, $name, $title, $location, $date, $problem, $proposal, $feasability, $advantage, $experience, $uploadFile, $employeeReward){
        $this->sqlQuery = "INSERT INTO FORMS (EMP_EMAIL, CREATOR_NAME, TITLE, LOCATION, DATE_SUBMITTED, PROBLEM, PROPOSAL, FEASABILITY, ADVANTAGE, EXPERIENCE, FILE_NAME, EMPLOYEE_REWARD)
                        VALUES('".$email."', '".$name."', '".$title."', '".$location."', '".$date."', '".$problem."', '".$proposal."', '".$feasability."', '".$advantage."', '".$experience."', '".$uploadFile."', '".$employeeReward."')";
        $this->result = mysqli_query($this->connectionString, $this->sqlQuery);
        return $this->result;
    }

    // Create a new row to the RATE_FORMS table
    function insertRateForm($email, $ideaRadioOne, $ideaRadioTwo, $ideaRadioThree, $ideaRadioFour, $ideaRadioFive, $addedValue, $costEstimate, $ideaRadioEight, $additionalComments, $totalWeight){
        $this->sqlQuery = "INSERT INTO RATE_FORMS (ADMINEMAIL, WEIGHTONE, WEIGHTTWO, WEIGHTTHREE, WEIGHTFOUR, WEIGHTFIVE, ADDEDVALUE, COSTESTIMATE, WEIGHTEIGHT, ADDITIONALCOMMENTS, WEIGHTTOTAL)
                        VALUES('".$email."', ".$ideaRadioOne.", ".$ideaRadioTwo.", ".$ideaRadioThree.", ".$ideaRadioFour.", ".$ideaRadioFive.", '".$addedValue."', '".$costEstimate."', ".$ideaRadioEight.", '".$additionalComments."', ".$totalWeight.")";
        $this->result = mysqli_query($this->connectionString, $this->sqlQuery);
        return $this->result;
    }

    // Grabs the Employees information from the EMPLOYEES table. This function
    // is called when a user successfully logs in.
    function selectEmployee($email){
        $this->sqlQuery = "SELECT * FROM EMPLOYEES
                           WHERE EMAIL = '".$email."'";
        $this->result = mysqli_query($this->connectionString, $this->sqlQuery);
        $num_result = $this->result->num_rows;
        if($num_result == 0) {
            return NULL;
        }
        $data = $this->result->fetch_assoc();
        $this->dataSet = array(stripslashes($data['EMAIL']), $data['EMPPASSWD'], stripslashes($data['POSITION']), stripslashes($data['ACCEPTCONDITIONS']));
        return $this->dataSet;
    }

    // Updates the users password in the EMPLOYEES table if the user forgot password
    function updatePassword($email, $eightCharPassword){
        $this->sqlQuery = "SELECT * FROM EMPLOYEES
                           WHERE EMAIL = '".$email."'";
        $this->result = mysqli_query($this->connectionString, $this->sqlQuery);
        $num_result = $this->result->num_rows;
        if($num_result == 0) {
            return NULL;
        }
        $hashPassword = password_hash($eightCharPassword, PASSWORD_ARGON2I);
        $this->sqlQuery = "UPDATE EMPLOYEES
                           SET EMPPASSWD = '".$hashPassword."'
                           WHERE EMAIL = '".$email."'";
        $this->result = mysqli_query($this->connectionString, $this->sqlQuery);
        return $eightCharPassword;
    }

    //return array of all admin emails
    function selectEmployeesByPosition($position){
        $query = "SELECT EMAIL FROM EMPLOYEES
                         WHERE POSITION = '".$position."'";
        $result = mysqli_query($this->connectionString, $query);
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $data;
    }

}
