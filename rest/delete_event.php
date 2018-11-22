<?php

    require '../header_rest.php';
    $controllerRest = new ControllerRest();
    $controllerEvent = new ControllerEvent();
    $controllerUser = new ControllerUser();

    $event_id = 0;
    if( !empty($_POST['event_id']) )
        $event_id = $_POST['event_id'];

    $user_id = 0;
    if( !empty($_POST['user_id']) )
        $user_id = $_POST['user_id'];

    $login_hash = "";
    if( !empty($_POST['login_hash']) )
        $login_hash = $_POST['login_hash'];

    $api_key = "";
    if(!empty($_POST['api_key']))
        $api_key = $_POST['api_key'];

    if(!$controllerUser->isUserIdExistAndHash($user_id, $login_hash) || Constants::API_KEY != $api_key) {
        $jsonArray = array();
        $jsonArray['status'] = array('status_code' => "3", 'status_text' => "Invalid Access. Please relogin.");
        echo json_encode($jsonArray);
    }
    else if($event_id > 0) {
        $controllerEvent->deleteEvent($event_id, 1);
        
        $jsonArray = array();
        $jsonArray['status'] = array('status_code' => "-1", 'status_text' => "Success.");        
        $jsonArray['event'] = array('event_id' => $event_id);
        echo json_encode($jsonArray);
    }
    else {
        $jsonArray = array();
        $jsonArray['status'] = array('status_code' => "3", 'status_text' => "Invalid Access. Please relogin.");
        echo json_encode($jsonArray);
    }
    
?>