<?php
    include("Mysql.php");
    $my_sql = new Mysql();
    $my_sqli = $my_sql->dbConnect();
    function randomPassword($length){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!-.[]?*()';
        $password = '';
        $charactersListLength = mb_strlen($characters, '8bit') - 1;
        foreach (range(1, $length) as $i){
            $password .= $characters[random_int(0, $charactersListLength)];
        }
        return $password;
    }

    if(isset($_GET['empid'])){
        $email = $_GET['empid'];
        $eightCharPassword = randomPassword(8);
        $updatePassword = $my_sql->updatePassword($email, $eightCharPassword);
        if($updatePassword == NULL){
            echo "This email is not registered in the system. Please sign up or contact the administrator.";
            $my_sql->dbDisconnect();
            exit(1);
        }
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
        $sent = mail($to,$subject,$message,$headers);
        if($sent){
            echo "Your password has been updated. Please check your email.";
            $my_sql->dbDisconnect();
            exit(1);
        }
        echo "There was an error updating your password. Please contact the administrator.";
        $my_sql->dbDisconnect();
    }


