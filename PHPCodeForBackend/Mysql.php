<?php
include("dbconfig.php");

class Mysql extends Dbconfig{

    public $connectionString;
    public $dataSet;
    private $sqlQuery;
    public $result;

    protected $databaseName;
    protected $hostName;
    protected $userName;
    protected $passCode;

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

    function dbConnect()    {
        $this->connectionString = mysqli_connect($this -> hostName, $this -> userName, $this -> passCode, $this -> databaseName);
        return $this->connectionString;
    }

    function dbDisconnect() {
        $this->connectionString = NULL;
        $this->sqlQuery = NULL;
        $this->dataSet = NULL;
        $this->databaseName = NULL;
        $this->hostName = NULL;
        $this->userName = NULL;
        $this->passCode = NULL;
    }

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

    function insertForm($email, $name, $title, $location, $date, $problem, $proposal, $feasability, $advantage, $experience, $uploadFile, $employeeReward){
        $this->sqlQuery = "INSERT INTO FORMS (EMP_EMAIL, CREATOR_NAME, TITLE, LOCATION, DATE_SUBMITTED, PROBLEM, PROPOSAL, FEASABILITY, ADVANTAGE, EXPERIENCE, FILE_NAME, EMPLOYEE_REWARD)
                        VALUES('".$email."', '".$name."', '".$title."', '".$location."', '".$date."', '".$problem."', '".$proposal."', '".$feasability."', '".$advantage."', '".$experience."', '".$uploadFile."', '".$employeeReward."')";
        $this->result = mysqli_query($this->connectionString, $this->sqlQuery);
        return $this->result;
    }

    function insertRateForm($email, $ideaRadioOne, $ideaRadioTwo, $ideaRadioThree, $ideaRadioFour, $ideaRadioFive, $addedValue, $costEstimate, $ideaRadioEight, $additionalComments, $totalWeight){
        $this->sqlQuery = "INSERT INTO RATE_FORMS (ADMINEMAIL, WEIGHTONE, WEIGHTTWO, WEIGHTTHREE, WEIGHTFOUR, WEIGHTFIVE, ADDEDVALUE, COSTESTIMATE, WEIGHTEIGHT, ADDITIONALCOMMENTS, WEIGHTTOTAL)
                        VALUES('".$email."', ".$ideaRadioOne.", ".$ideaRadioTwo.", ".$ideaRadioThree.", ".$ideaRadioFour.", ".$ideaRadioFive.", '".$addedValue."', '".$costEstimate."', ".$ideaRadioEight.", '".$additionalComments."', ".$totalWeight.")";
        $this->result = mysqli_query($this->connectionString, $this->sqlQuery);
        return $this->result;
    }

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
