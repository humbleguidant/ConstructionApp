<?php
/* 
Author: Aubrey Nickerson
Date: September 27th, 2020
Program: rateForm.php
Project: Construction App

This php script receives an AJAX call from the 
rate form javascript file. It receives the data
and creates a connection to the database. The data from
the AJAX call will be handled with the database. The new 
rate form will be inserted into the database.
*/

// Create a MySQL object to make a connection and perform querys
    include("Mysql.php");
    session_start();
    $my_sql = new Mysql();
    $my_sqli = $my_sql->dbConnect();
// If some fields are empty then alert user.
    $response = array(
        'status' => 0,
        'message' => 'Form submission failed, you must fill in all fields.'
    );

// If all the fields are full then do some calculations to rate the relevancy of the suggestion form.
    if(isset($_POST['idea1']) && isset($_POST['idea2']) && isset($_POST['idea3']) && isset($_POST['idea4']) && isset($_POST['idea5']) && isset($_POST['costEstimate']) && isset($_POST['idea8']) && isset($_POST['additionalComments'])){
        $email = $_GET['email'];
        
        // Caclulate the relevancy of the form
        $ideaRadioOne = ((($_POST['idea1'] * 20) / 100) * 0.11) * 100;
        $ideaRadioTwo = ((($_POST['idea2'] * 20) / 100) * 0.11) * 100;
        $ideaRadioThree = ((($_POST['idea3'] * 20) / 100) * 0.11) * 100;
        $ideaRadioFour = ((($_POST['idea4'] * 20) / 100) * 0.17) * 100;
        $ideaRadioFive = ((($_POST['idea5'] * 20) / 100) * 0.17) * 100;
        $addedValue = $_POST['addedValue'];
        $costEstimate = $_POST['costEstimate'];
        $ideaRadioEight = ((($_POST['idea8'] * 20) / 100) * 0.33) * 100;
        $additionalComments = $_POST['additionalComments'];
        
        // Caclulate the total weight of the relevancy
        $totalWeight = $ideaRadioOne + $ideaRadioTwo + $ideaRadioThree + $ideaRadioFour + $ideaRadioFive + $ideaRadioEight;
        
        // Insert a new row to the RATE_FORM table
        $insert = $my_sql->insertRateForm($email, $ideaRadioOne, $ideaRadioTwo, $ideaRadioThree, $ideaRadioFour, $ideaRadioFive, mysqli_real_escape_string($my_sqli, $addedValue), mysqli_real_escape_string($my_sqli, $costEstimate), $ideaRadioEight, $additionalComments, $totalWeight) ;
        // If the insert was successful then let the user know that the submission was successful.
        if($insert) {
            $response['status'] = 1;
            $response['message'] = 'Form data submitted successfully!';
            $my_sql->dbDisconnect();
        }
    }
echo json_encode($response);
