<?php
    include("Mysql.php");
    $my_sql = new Mysql();
    $my_sqli = $my_sql->dbConnect();

    if(isset($_GET['empid']))
    {
        $email = $_GET['empid'];
        $password = $_GET['password'];
        $sqlQueryData = $my_sql->selectEmployee($email);
        if($sqlQueryData != NULL)
        {
            if(password_verify($password, $sqlQueryData[1]) == false)
            {
                echo "The password is incorrect. Try again.";
                $my_sql->dbDisconnect();
                exit(1);
            }
            echo $sqlQueryData[1];
            $my_sql->dbDisconnect();
            exit(1);
        }
        echo "Email does not exist. Contact Administrator.";
        $my_sql->dbDisconnect();
        exit(1);
    }
