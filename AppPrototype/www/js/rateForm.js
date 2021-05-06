/* 
Author: Aubrey Nickerson
Date: September 23rd, 2020
Program: rateForm.js
Project: Construction App

This is the rate form script.
It handles the user input and makes 
an AJAX call to a remote server hosting a php
script. The user input gets sent to the remote server
and the php script handles the user input. The php script
sends the data back to forgotPassword.js.
*/

$(document).ready(function() {
    var emailFromLoginPage = sessionStorage.getItem('email');

    $("#submitRateForm").on('submit', function(e) {
        e.preventDefault();
        
        // Grab all user input values and assign to FormData object.
        var formData = new FormData(this);
        // Make the AJAX call to remote server
        $.ajax({
            type: "POST",
            url: "http://10.1.144.91/PHPCodeForBackend/rateForm.php?email=" + emailFromLoginPage,
            data: formData,
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            crossDomain: true,
            beforeSend: function () {
                $("#submit").val('Loading...');
            },
            // If the AJAX call was successful then handle the repsonse. 
            success: function(response){
                // If the response status is 1 then all went well and
                // the user is redirected to the menu.
                if(response.status === 1){
                    alert(response.message);
                    window.location = "../pages/menu.html";
                    return false;
                }
                // If something went wrong the alert the user.
                alert(response.message);
                return false;
            }
        })
        return false;
    });
});
