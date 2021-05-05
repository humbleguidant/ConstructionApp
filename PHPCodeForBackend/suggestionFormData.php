<?php
  include("Mysql.php");
  session_start();
  $uploadDir = 'uploads/';
  $my_sql = new Mysql();
  $my_sqli = $my_sql->dbConnect();
  $response = array(
    'status' => 0,
    'message' => 'Form submission failed, please try again.'
  );

  if(isset($_POST['idea']) && isset($_POST['title']) && isset($_POST['location']) && isset($_POST['date']) && isset($_POST['problem']) && isset($_POST['proposal']) && isset($_POST['feasability']) && isset($_POST['advantages']) && isset($_POST['reward'])) {
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

      foreach($advantages as $checked){
          $numOfChecks .= $checked.", ";
      }

      if (!empty($_FILES['file']['name'])) {
          $fileName= basename($_FILES['file']['name']);
          $filePath = $uploadDir.$fileName;
          $fileType = pathinfo($filePath, PATHINFO_EXTENSION);
          $allowTypes = array('jpg', 'png', 'jpeg');
          if (in_array($fileType, $allowTypes)) {
              if (move_uploaded_file($_FILES["file"]["tmp_name"], $filePath)) {
                  $uploadFile = $fileName;
              }else{
                  $uploadStatus = 0;
                  $response['message'] = 'Sorry, there was an error uploading your file.';
                  $my_sql->dbDisconnect();
              }
          }else{
              $uploadStatus = 0;
              $response['message'] = 'Sorry, you must upload a JPG, JPEG, or PNG image.';
              $my_sql->dbDisconnect();
          }
      }
      if($uploadStatus == 1){
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
