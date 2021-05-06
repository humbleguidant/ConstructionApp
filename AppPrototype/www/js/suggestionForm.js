/* 
Author: Aubrey Nickerson
Date: September 22nd, 2020
Program: suggestionForm.js
Project: Construction App

This is the suggestion form script.
It handles the user input and makes 
an AJAX call to a remote server hosting a php
script. The user input gets sent to the remote server
and the php script handles the user input. The php script
sends the data back to suggestionForm.js.
*/

$(document).ready(function() {
    var emailFromLoginPage = sessionStorage.getItem('email');

    $("#submitForm").on('submit', function(e) {
        e.preventDefault();
        
        // Grab all user input values and assign to FormData object.
        var formData = new FormData(this);
        if($.trim($("#title").val()).length > 0 & $.trim($("#location").val()).length > 0 & $.trim($("#problem").val()) != null & $("#date").val() != null & $.trim($("#improvement").val()) != null & $.trim($("#proposal").val()) != null){
            
            // Make the AJAX call to remote server
            $.ajax({
                type: "POST",
                url: "http://10.1.144.91/PHPCodeForBackend/suggestionFormData.php?email=" + emailFromLoginPage,
                data: formData,
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                crossDomain: true,
                
                // If the AJAX call was successful then handle the repsonse.
                success: function(response){
                    // If the response status is 1 then all went well and
                    // the user is redirected to the menu.
                    if(response.status === 1){
                        alert(response.message);
                        window.location = "../pages/menu.html";
                 // If something went wrong then alert the user.       
                    }else {
                        alert(response.message);
                    }
                }
            });
        }
        return false;
    });
});
