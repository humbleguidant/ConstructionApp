<?php
    include("Mysql.php");
    session_start();
    $my_sql = new Mysql();
    $my_sqli = $my_sql->dbConnect();
    $response = array(
        'status' => 0,
        'message' => 'Form submission failed, you must fill in all fields.'
    );
    if(isset($_POST['idea1']) && isset($_POST['idea2']) && isset($_POST['idea3']) && isset($_POST['idea4']) && isset($_POST['idea5']) && isset($_POST['costEstimate']) && isset($_POST['idea8']) && isset($_POST['additionalComments'])){
        $email = $_GET['email'];
        $ideaRadioOne = ((($_POST['idea1'] * 20) / 100) * 0.11) * 100;
        $ideaRadioTwo = ((($_POST['idea2'] * 20) / 100) * 0.11) * 100;
        $ideaRadioThree = ((($_POST['idea3'] * 20) / 100) * 0.11) * 100;
        $ideaRadioFour = ((($_POST['idea4'] * 20) / 100) * 0.17) * 100;
        $ideaRadioFive = ((($_POST['idea5'] * 20) / 100) * 0.17) * 100;
        $addedValue = $_POST['addedValue'];
        $costEstimate = $_POST['costEstimate'];
        $ideaRadioEight = ((($_POST['idea8'] * 20) / 100) * 0.33) * 100;
        $additionalComments = $_POST['additionalComments'];
        $totalWeight = $ideaRadioOne + $ideaRadioTwo + $ideaRadioThree + $ideaRadioFour + $ideaRadioFive + $ideaRadioEight;
        $insert = $my_sql->insertRateForm($email, $ideaRadioOne, $ideaRadioTwo, $ideaRadioThree, $ideaRadioFour, $ideaRadioFive, mysqli_real_escape_string($my_sqli, $addedValue), mysqli_real_escape_string($my_sqli, $costEstimate), $ideaRadioEight, $additionalComments, $totalWeight) ;
        if($insert) {
            $response['status'] = 1;
            $response['message'] = 'Form data submitted successfully!';
            $my_sql->dbDisconnect();
        }
    }
echo json_encode($response);
