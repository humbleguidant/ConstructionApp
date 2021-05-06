<?php
/* 
Author: Aubrey Nickerson
Date: September 27th, 2020
Program: login.php
Project: Construction App

This php script receives an AJAX call from the 
login javascript file. It receives the data
and creates a connection to the database. The data from
the AJAX call will be handled with the database. 
The database checks if the user exists.
*/

// Create a MySQL class to make a connection and perform querys
    include("Mysql.php");
    $my_sql = new Mysql();
    $my_sqli = $my_sql->dbConnect();

// If the email is not empty then check if the email exists or if the password is correct.
    if(isset($_GET['empid']))
    {
        $email = $_GET['empid'];
        $password = $_GET['password'];
        
        // Check if the user exists in the database
        $sqlQueryData = $my_sql->selectEmployee($email);
        // If the user exists then proceed.
        if($sqlQueryData != NULL)
        {
            // IF the password is incorrect then alert the user
            if(password_verify($password, $sqlQueryData[1]) == false)
            {
                echo "The password is incorrect. Try again.";
                $my_sql->dbDisconnect();
                exit(1);
            }
            // Otherwise allow the user to login
            echo $sqlQueryData[1];
            $my_sql->dbDisconnect();
            exit(1);
        }
        // IF the user does not exist in the database then alert the user.
        echo "Email does not exist. Contact Administrator.";
        $my_sql->dbDisconnect();
        exit(1);
    }
