<?php
/* 
Author: Aubrey Nickerson
Date: September 28th, 2020
Program: register.php
Project: Construction App

This php script receives an AJAX call from the 
register javascript file. It receives the data
and creates a connection to the database. The data from
the AJAX call will be handled with the database. 
The database will create a new user.
*/


// Create a MySQL object to make a connection and perform querys
    include("Mysql.php");
    $my_sql = new Mysql();
    $my_sqli = $my_sql->dbConnect();

// Create a random password generator
    function randomPassword($length){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!-.[]?*()';
        $password = '';
        $charactersListLength = mb_strlen($characters, '8bit') - 1;
        foreach (range(1, $length) as $i){
            $password .= $characters[random_int(0, $charactersListLength)];
        }
        return $password;
    }

// IF the button has been clicked then proceed.
    if(isset($_POST['submit'])){
        $email = $_POST['empid'];
        $employee = $_POST['employee'];
        $admin = $_POST['admin'];
        $acceptConditions = $_POST['check'];
        
        // Create the random password
        $eightCharPassword = randomPassword(8);
        
        // Prepare the email and send new password to user
        $from = "<donotreply@zueblin.com>";
        $to = $email;
        $subject = "Your Password";
        $message = "<html>
                        <head>
                        <title>Your Password</title>  
                        </head>
                        <body>
                            <p>This is your new password. Keep it in a safe place where you can remember.</p>           
                            <table>
                            <tr>
                            <th>".$eightCharPassword."</th>
                            </tr>
                            </table>   
                        </body>
                    </html>";
        $headers = "MIME-Version: 1.0"."\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8"."\r\n";
        $headers .= "From: DoNotReply".$from."\r\n".
                    "X-Mailer: PHP/".phpversion();
        
        // If the user is an admin then insert the user as an admin in the database.
        if(isset($admin)){
            $sqlQuery = $my_sql->insertQuery($email, $eightCharPassword, $admin, $acceptConditions);
            if($sqlQuery){
                mail($to, $subject, $message, $headers);
                echo 300;
                $my_sql->dbDisconnect();
                exit(1);
            }
            echo "This email is already registered. Please login or contact administrator.";
            $my_sql->dbDisconnect();
            exit(1);
        }
        // If the user is an employee then insert the user as an employee
        $sqlQuery = $my_sql->insertQuery($email, $eightCharPassword, $employee, $acceptConditions);
        if($sqlQuery){
            mail($to, $subject, $message, $headers);
            echo 300;
            $my_sql->dbDisconnect();
            exit(1);
        }
        
        // If the email already exists then alert the user.
        echo "This email is already registered. Please login or contact administrator.";
        $my_sql->dbDisconnect();
    }

