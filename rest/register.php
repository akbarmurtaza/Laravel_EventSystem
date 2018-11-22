<?php

  require_once '../header_rest.php';
  $controllerUser = new ControllerUser();

  $full_name = "";
  if( !empty($_POST['full_name']) )
      $full_name = $_POST['full_name'];

  $email = "";
  if( !empty($_POST['email']) )
      $email = $_POST['email'];

  $facebook_id = "";
  if( !empty($_POST['facebook_id']) )
      $facebook_id = $_POST['facebook_id'];

  $twitter_id = "";
  if( !empty($_POST['twitter_id']) )
      $twitter_id = $_POST['twitter_id'];

  $google_id = "";
  if( !empty($_POST['google_id']) )
      $google_id = $_POST['google_id'];

  $thumb_url = "";
  if( !empty($_POST['thumb_url']) )
      $thumb_url = $_POST['thumb_url'];

  $api_key = "";
  if(!empty($_POST['api_key']))
      $api_key = $_POST['api_key'];

  if(Constants::API_KEY != $api_key) {
      $jsonArray = array();
      $jsonArray['status'] = errorCodeFormat( "3", "Invalid Access.");
      echo json_encode($jsonArray);
      return;
  }

  if( !empty($google_id) ) {
      if(!$controllerUser->isGoogleIdExist($google_id)) {
            $itm = new User();
            $itm->full_name = $full_name;
            $itm->email = $email;
            $itm->facebook_id = '';
            $itm->google_id = $google_id;
            $itm->twitter_id = '';
            $itm->thumb_url = $thumb_url;

            $user = $controllerUser->loginGooglePlus($google_id);
            if($user == null)
              $controllerUser->registerUser($itm);

            $user = $controllerUser->loginGooglePlus($google_id);
            if($user != null) {
                // update the hash
                $controllerUser->updateUserHash($user);
                $jsonArray = array();
                $jsonArray['user_info'] = translateJSON($user);
                $jsonArray['status'] = errorCodeFormat( "-1", "Success.");
                echo json_encode($jsonArray);
            }
      }
      else {
            $user = $controllerUser->loginFacebook($facebook_id);
            if($user != null) {
                // update the hash
                $controllerUser->updateUserHash($user);
                $jsonArray = array();
                $jsonArray['user_info'] = translateJSON($user);
                $jsonArray['status'] = errorCodeFormat( "-1", "Success.");
                echo json_encode($jsonArray);
            }
            else {
                $jsonArray = array();
                $jsonArray['status'] = errorCodeFormat( "1", "Google Login Invalid. User details is denied.");
                echo json_encode($jsonArray);
            }
      }
  }

  else if( !empty($facebook_id) ) {
      if(!$controllerUser->isFacebookIdExist($facebook_id)) {
            $itm = new User();
            $itm->full_name = $full_name;
            $itm->email = $email;
            $itm->facebook_id = $facebook_id;
            $itm->twitter_id = '';
            $itm->thumb_url = $thumb_url;
            $itm->google_id = '';

            $user = $controllerUser->loginFacebook($facebook_id);
            if($user == null)
              $controllerUser->registerUser($itm);

            $user = $controllerUser->loginFacebook($facebook_id);
            if($user != null) {
                // update the hash
                $controllerUser->updateUserHash($user);
                $jsonArray = array();
                $jsonArray['user_info'] = translateJSON($user);
                $jsonArray['status'] = errorCodeFormat( "-1", "Success.");
                echo json_encode($jsonArray);
            }
      }
      else {
            $user = $controllerUser->loginFacebook($facebook_id);
            if($user != null) {
                // update the hash
                $controllerUser->updateUserHash($user);
                $jsonArray = array();
                $jsonArray['user_info'] = translateJSON($user);
                $jsonArray['status'] = errorCodeFormat( "-1", "Success.");
                echo json_encode($jsonArray);
            }
            else {
                $jsonArray = array();
                $jsonArray['status'] = errorCodeFormat( "1", "Facebook Login Invalid. User details is denied.");
                echo json_encode($jsonArray);
            }
      }
  }
  else if( !empty($twitter_id) ) {
      if(!$controllerUser->isTwitterIdExist($twitter_id)) {
            $itm = new User();
            $itm->full_name = $full_name;
            $itm->email = $email;
            $itm->facebook_id = '';
            $itm->twitter_id = $twitter_id;
            $itm->thumb_url = $thumb_url;
            $itm->google_id = '';

            $controllerUser->registerUser($itm);
            $user = $controllerUser->loginTwitter($twitter_id);
            if($user != null) {
                // update the hash
                $controllerUser->updateUserHash($user);
                $jsonArray = array();
                $jsonArray['user_info'] = translateJSON($user);
                $jsonArray['status'] = errorCodeFormat( "-1", "Success.");
                echo json_encode($jsonArray);
            }
            else {
                $jsonArray = array();
                $jsonArray['status'] = errorCodeFormat( "1", "Twitter Login Invalid. User details is denied.");
                echo json_encode($jsonArray);
            }
      }
      else {
            $user = $controllerUser->loginTwitter($twitter_id);
            if($user != null) {
                // update the hash
                $controllerUser->updateUserHash($user);
                $jsonArray = array();
                $jsonArray['user_info'] = translateJSON($user);
                $jsonArray['status'] = errorCodeFormat( "-1", "Success.");
                echo json_encode($jsonArray);
            }
            else {
                $jsonArray = array();
                $jsonArray['status'] = errorCodeFormat( "1", "Twitter Login Invalid.");
                echo json_encode($jsonArray);
            }
      }
  }
  else {
      $jsonArray = array();
      $jsonArray['status'] = errorCodeFormat( "3", "Invalid Access.");
      echo json_encode($jsonArray);
  }

  function translateJSON($itm) {

      $controllerRest = new ControllerRest();
      $count = $controllerRest->getResultUserEventAttendedCount($itm->user_id);
      $jsonArray = array('user_id' => "$itm->user_id",
                          'login_hash' => "$itm->login_hash",
                          'facebook_id' => "$itm->facebook_id",
                          'twitter_id' => "$itm->twitter_id",
                          'google_id' => "$itm->google_id",
                          'full_name' => "$itm->full_name",
                          'thumb_url' => "$itm->thumb_url",
                          'email' => "$itm->email",
                          'no_of_events_joined' => $count);
      return $jsonArray;
  }

  function errorCodeFormat($status_code, $status_text) {
    $jsonArray = array('status_code' => "$status_code", 'status_text' => "$status_text");
    return $jsonArray;
  }

?>