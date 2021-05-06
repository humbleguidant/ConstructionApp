/* 
Author: Aubrey Nickerson
Date: September 21st, 2020
Program: register.js
Project: Construction App

This is the register script.
It handles the user input and makes 
an AJAX call to a remote server hosting a php
script. The user input gets sent to the remote server
and the php script handles the user input. The php script
sends the data back to register.js.
*/

$(document).ready(function() {
    $("#submit").click(function() {
        // Assign input values to variables
        var email = $("#empid").val();
        var employee = $("#employee").val();
        var admin = $("#admin").val();
        var checked = $("#check").val();
        
        // Check if its a valid email address
        var emailCheck = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
        // Create parameters for HTTP request
        var dataString = "empid=" + email + "&employee=" + employee + "&check="+ checked + "&submit=";
        
        if (document.getElementById("admin").checked){
            dataString = "empid=" + email + "&admin=" + admin + "&check="+ checked + "&submit=";
        }
        // Check if checkbox is checked
        if(!document.getElementById("check").checked){
            alert("You must accept terms and conditions.");
            return false;
        // Check if email is valid.    
        }else if(!emailCheck.test(email)){
            alert("You have entered an invalid email address");
            return false;
        }
        if($.trim(email).length > 0) {
            // Make the AJAX call to remote server
            $.ajax({
                type: "POST",
                url: "http://10.1.144.91/PHPCodeForBackend/register.php",
                data: dataString,
                crossDomain: true,
                cache: false,
                beforeSend: function () {
                    $("#submit").val('Loading...');
                },
                // If the AJAX call was successful then handle the repsonse.
                success: function (data) {
                    // if data equals 300 then everything went well and an email was sent to user
                    // with generated password.
                    if (data == 300) {
                        alert("Your password has been sent to the email you provided. Please check your inbox.")
                        window.location = "../index.html";
                    }
                    // If something went wrong then alert user.
                    else{
                        alert(data);
                    }
                }
            });
        }
        return false;
    });
});
