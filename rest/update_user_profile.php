<?php

    require '../header_rest.php';
    $controllerRest = new ControllerRest();
    $controllerUser = new ControllerUser();

    $full_name = "";
    if( !empty($_POST['full_name']) )
        $full_name = $_POST['full_name'];

    $email = "";
    if( !empty($_POST['email']) )
        $email = $_POST['email'];

    $user_id ="";
    if( !empty($_POST['user_id']) )
        $user_id = $_POST['user_id'];

    $login_hash ="";
    if( !empty($_POST['login_hash']) )
        $login_hash = $_POST['login_hash'];

    $api_key = "";
    if(!empty($_POST['api_key']))
        $api_key = $_POST['api_key'];

    if(Constants::API_KEY != $api_key) {
        $jsonArray = array();
        $jsonArray['status'] = errorCodeFormat( "3", "Invalid Access.");
        echo json_encode($jsonArray);
        return;
    }

    if(!$controllerUser->isUserIdExistAndHash($user_id, $login_hash)) {
        $jsonArray = array();
        $jsonArray['status'] = errorCodeFormat( "3", "Invalid Access.");
        echo json_encode($jsonArray);
    }
    else {
        $itm = $controllerUser->getUserByUserId($user_id);
        if($itm != null) {
            $itm->full_name = $full_name;
            $controllerUser->updateUserFullName($itm);

            $itm = $controllerUser->getUserByUserId($user_id);
            $jsonArray = array();
            $jsonArray['user_info'] = translateJSON($itm);
            $jsonArray['status'] = errorCodeFormat( "-1", "Success.");
            echo json_encode($jsonArray);
        }
    
    }

    function translateJSON($itm) {
        $jsonArray = array('user_id' => "$itm->user_id", 
                            'login_hash' => "$itm->login_hash",
                            'facebook_id' => "$itm->facebook_id",
                            'twitter_id' => "$itm->twitter_id",
                            'google_id' => "$itm->google_id",
                            'full_name' => "$itm->full_name",
                            'thumb_url' => "$itm->thumb_url",
                            'email' => "$itm->email");
        return $jsonArray;
    }

    function errorCodeFormat($status_code, $status_text) {
      $jsonArray = array('status_code' => "$status_code", 'status_text' => "$status_text");
      return $jsonArray;
    }
?>