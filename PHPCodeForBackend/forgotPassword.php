<?php
/* 
Author: Aubrey Nickerson
Date: September 26th, 2020
Program: forgotPassword.php
Project: Construction App

This php script receives an AJAX call from the 
forgotPassword javascript file. It receives the data
and creates a connection to the database. The data from
the AJAX call will be handled with the database. A new password
will be generated and inserted into the database. The new password will
then be sent by email to the user. 

*/
// Create a MySQL class to make a connection and perform querys
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

    // If the email is not empty then generate a random password and update it to the database.
    if(isset($_GET['empid'])){
        $email = $_GET['empid'];
        // Generate the password
        $eightCharPassword = randomPassword(8);
        // Update in database.
        $updatePassword = $my_sql->updatePassword($email, $eightCharPassword);
        
        // IF the email does not exist then alert the user
        if($updatePassword == NULL){
            echo "This email is not registered in the system. Please sign up or contact the administrator.";
            $my_sql->dbDisconnect();
            exit(1);
        }
        // Prepare the email to send to the user.
        $from = "<donotreply@zueblin.com>";
        $to = $email;
        $subject = "New Password";
        $message = "<htmL>
                        <head>
                        <title>New Password</title>
                        </head>
                        <body>
                            <p>This is your new password. Keep it in a safe place where you can remember.</p>
                            <table>
                            <tr>
                            <th>".$updatePassword."</th>
                            </tr>
                            </table>
                        </body>
                    </html>
                    ";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: DoNotReply" . $from . "\r\n" .
                    "X-Mailer: PHP/" . phpversion();
        
        // Send the mail
        $sent = mail($to,$subject,$message,$headers);
        
        // If the message was sent, then alert the user. 
        if($sent){
            echo "Your password has been updated. Please check your email.";
            $my_sql->dbDisconnect();
            exit(1);
        }
        // If something went wrong then alert the user.
        echo "There was an error updating your password. Please contact the administrator.";
        $my_sql->dbDisconnect();
    }


