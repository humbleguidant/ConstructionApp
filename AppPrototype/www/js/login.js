/* 
Author: Aubrey Nickerson
Date: September 22nd, 2020
Program: login.js
Project: Construction App

This is the login script.
It handles the user input and makes 
an AJAX call to a remote server hosting a php
script. The email and password gets sent to the remote server
and the php script handles the email and password. The php script
sends the data back to login.js.
*/
$(document).ready(function() {
    sessionStorage.removeItem('email');
    $("#submit").click(function() {
        
        // Assign email and password input values to variables
        var email = $("#empid").val();
        var password = $("#password").val();
        
        // Create parameters for HTTP request
        var dataString = "empid=" + email + "&password=" + password + "&submit=";
        if(!document.getElementById("check").checked){
            alert("You must accept the basic data protection regulation.");
            return false;
        }
        
        // Make the AJAX call to remote server
        $.ajax({
            type: "GET",
            url: "http://10.1.144.91/PHPCodeForBackend/login.php",
            data: dataString,
            crossDomain: true,
            cache: false,
            beforeSend: function () {
                $("#submit").val('Loading...');
            },
            // If the AJAX call was successful then handle the repsonse.
            success: function(data){
                
                // If the email does not exist then alert the user
                if(data === "Email does not exist. Contact Administrator."){
                    alert(data);
                    return false;
                // If the password is incorrect then alert the user.       
                } else if(data === "The password is incorrect. Try again."){
                    alert(data);
                    return false;
                }
                // If no issues then redirect to the main menu.
                sessionStorage.setItem("email", email);
                window.location = "../pages/menu.html";
            }
        })
    return false;
    });
});
