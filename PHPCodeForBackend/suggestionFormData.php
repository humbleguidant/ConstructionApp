<?php
/* 
Author: Aubrey Nickerson
Date: September 28th, 2020
Program: suggestionFormData.php
Project: Construction App

This php script receives an AJAX call from the 
suggestion form javascript file. It receives the data
and creates a connection to the database. The data from
the AJAX call will be handled with the database. 
The database will create a new suggestion form in the database
and notify the administration.
*/

// Create a MySQL object to make a connection and perform querys
  include("Mysql.php");
  session_start();
  $uploadDir = 'uploads/';
  $my_sql = new Mysql();
  $my_sqli = $my_sql->dbConnect();

// If some fields are empty then alert user.
  $response = array(
    'status' => 0,
    'message' => 'Form submission failed, please try again.'
  );

// iF all fields are full then proceed.
  if(isset($_POST['idea']) && isset($_POST['title']) && isset($_POST['location']) && isset($_POST['date']) && isset($_POST['problem']) && isset($_POST['proposal']) && isset($_POST['feasability']) && isset($_POST['advantages']) && isset($_POST['reward'])) {
      
    // Assign all input values to variables. 
      $email = $_GET['email'];
      $name = $_POST['ideaName'];
      $title = $_POST['title'];
      $location = $_POST['location'];
      $date = $_POST['date'];
      $problem = $_POST['problem'];
      $proposal = $_POST['proposal'];
      $feasability = $_POST['feasability'];
      $advantages = $_POST['advantages'];
      $experience = $_POST['experience'];
      $employeeReward = $_POST['reward'];
      $numOfChecks = "";
      $uploadStatus = 1;
      $uploadFile = '';

    // For each checkbox that was checked, append to large string.
      foreach($advantages as $checked){
          $numOfChecks .= $checked.", ";
      }

    // If the user added an image to the suggestion form
    // then save the image in the server and insert the name
    // of the image in the database. 
      if (!empty($_FILES['file']['name'])) {
        
        // Save image to path in server. 
          $fileName= basename($_FILES['file']['name']);
          $filePath = $uploadDir.$fileName;
          $fileType = pathinfo($filePath, PATHINFO_EXTENSION);
        
        //Image must be jpg, png, or jpeg.
          $allowTypes = array('jpg', 'png', 'jpeg');
          if (in_array($fileType, $allowTypes)) {
            // If no errors then save file
              if (move_uploaded_file($_FILES["file"]["tmp_name"], $filePath)) {
                  $uploadFile = $fileName;
                
                // Alert user that something went wrong
              }else{
                  $uploadStatus = 0;
                  $response['message'] = 'Sorry, there was an error uploading your file.';
                  $my_sql->dbDisconnect();
              }
            // Alert user to upload only jpg, png, or jpeg.
          }else{
              $uploadStatus = 0;
              $response['message'] = 'Sorry, you must upload a JPG, JPEG, or PNG image.';
              $my_sql->dbDisconnect();
          }
      }
    
    // If the image succesfully saved in the server then proceed.
      if($uploadStatus == 1){
        // If the name, image, and experience text field were not empty 
        // then insert a new row in the FORMS table with all columns filled.
          if($experience != NULL && !empty($_FILES['file']['name'])){
              if($name != NULL){
                  $insert = $my_sql->insertForm($email,
                                                mysqli_real_escape_string($my_sqli, $name),
                                                mysqli_real_escape_string($my_sqli, $title),
                                                mysqli_real_escape_string($my_sqli, $location),
                                                $date,
                                                mysqli_real_escape_string($my_sqli, $problem),
                                                mysqli_real_escape_string($my_sqli, $proposal),
                                                $feasability,
                                                $numOfChecks,
                                                mysqli_real_escape_string($my_sqli, $experience),
                                                $uploadFile,
                                                $employeeReward);
                
             // If the name is empty but not the image or experience then insert a new row without the name
              }else{
                  $insert = $my_sql->insertForm($email,
                                                'Myself',
                                                mysqli_real_escape_string($my_sqli, $title),
                                                mysqli_real_escape_string($my_sqli, $location),
                                                $date,
                                                mysqli_real_escape_string($my_sqli, $problem),
                                                mysqli_real_escape_string($my_sqli, $proposal),
                                                $feasability,
                                                $numOfChecks,
                                                mysqli_real_escape_string($my_sqli, $experience),
                                                $uploadFile,
                                                $employeeReward);
              }
            // If the experience and image are empty but not the name then insert
            // a new row without the image and experience
          }else{
              if($name != NULL){
                  $insert = $my_sql->insertForm($email,
                                                mysqli_real_escape_string($my_sqli, $name),
                                                mysqli_real_escape_string($my_sqli, $title),
                                                mysqli_real_escape_string($my_sqli, $location),
                                                $date,
                                                mysqli_real_escape_string($my_sqli, $problem),
                                                mysqli_real_escape_string($my_sqli, $proposal),
                                                $feasability,
                                                $numOfChecks,
                                                'Nothing',
                                                'No file uploaded',
                                                $employeeReward);
              // If the name, image, and experience are empty then insert a new row without the 
              // name, image, or experience. 
              }else{
                  $insert = $my_sql->insertForm($email,
                                                'Myself',
                                                mysqli_real_escape_string($my_sqli, $title),
                                                mysqli_real_escape_string($my_sqli, $location),
                                                $date,
                                                mysqli_real_escape_string($my_sqli, $problem),
                                                mysqli_real_escape_string($my_sqli, $proposal),
                                                $feasability,
                                                $numOfChecks,
                                                'Nothing',
                                                'No file uploaded',
                                                $employeeReward);
              }
          }

        // If the insert was successful then send an email to all administrators
        // that a new suggestion form has been submitted.
          if($insert){
              $response['status'] = 1;
              $response['message'] = 'Form data submitted successfully!';
              //setup message and subject
              $subject = 'New suggestion has been created';
              $message = 'An employee has submitted a new suggestion.';
              //select all employees emails with position of 'Admin'
              $data = $my_sql->selectEmployeesByPosition('Administrator');
              foreach($data as $row){
                 foreach ($row as $email){
                      mail($email, $subject, $message);
                 }
              }
              $my_sql->dbDisconnect();
          }
      }
  }
  echo json_encode($response);
