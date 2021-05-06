/* 
Author: Aubrey Nickerson
Date: September 21st, 2020
Program: forgotPassword.js
Project: Construction App

This is the forgot password script.
It handles the user input and makes 
an AJAX call to a remote server hosting a php
script. The email gets sent to the remote server
and the php script handles the email. The php script
sends the data back to forgotPassword.js.
*/

$(document).ready(function() {
    $("#submit").click(function () {
        
        // assign email to variable.
        var email = $("#empid").val();       
        // Create parameters for HTTP request
        var dataString = "empid=" + email + "&submit=";
        
        // Make the AJAX call to remote server 
        $.ajax({
            type: "GET",
            url: "http://10.1.144.91/PHPCodeForBackend/forgotPassword.php",
            data: dataString,
            crossDomain: true,
            cache: false,
            beforeSend: function () {
                $("#submit").val('Loading...');
            },
            // If the AJAX call was successful then handle the repsonse. 
            success: function(data){
                // If the email does not exist then alert the user.
                if(data === "This email is not registered in the system. Please sign up or contact the administrator."){
                    alert(data);
                    return false;
                // If there was an error updating the password then alert the user.     
                } else if(data === "There was an error updating your password. Please contact the administrator."){
                    alert(data);
                    return false;
                }
                // If no issues then redirect to the login page.
                alert(data);
                window.location = "../index.html";
            }
        })
        return false;
    });
});
