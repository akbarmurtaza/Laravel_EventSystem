<?php
    require_once '../header_rest.php';
    $controllerUser = new ControllerUser();
    $controllerEvent = new ControllerEvent();
    $controllerRest = new ControllerRest();

    $user_id = 0;
    if( !empty($_POST['user_id']) )
        $user_id = $_POST['user_id'];

    $login_hash = "";
    if( !empty($_POST['login_hash']) )
        $login_hash = $_POST['login_hash'];

    $api_key = "";
    if(!empty($_POST['api_key']))
        $api_key = $_POST['api_key'];

    $event_id = 0;
    if(!empty($_POST['event_id']))
        $event_id = $_POST['event_id'];

    if( ( !empty($login_hash) && !empty($user_id) ) && Constants::API_KEY == $api_key ) {

        $user = $controllerUser->getUserByUserId($user_id);
        $login_hash = str_replace(" ", "+", $login_hash);
        if($user != null && $user->login_hash == $login_hash) {
            $count = count($_FILES["file"]["name"]);
            if( $count > 0 ) {
                $desired_dir = Constants::IMAGE_UPLOAD_DIR;
                $errors = array();
                for($key = 0; $key < $count; $key++){

                    $file_name = $_FILES['file']['name'][$key];
                    $file_size = $_FILES['file']['size'][$key];
                    $file_tmp = $_FILES['file']['tmp_name'][$key];
                    $file_type= $_FILES['file']['type'][$key];

                    // if($file_size > 2097152){
                    //     $errors[]='File size must be less than 2 MB';
                    // }    

                    $timestamp =  uniqid();
                    $temp = explode(".", $_FILES["file"]["name"][$key]);
                    $extension = end($temp);

                    $new_file_name = $desired_dir."/"."photo_".$timestamp.".".$extension;
                    $new_file_name1 = "../".$desired_dir."/"."photo_".$timestamp.".".$extension;
                    if(empty($errors)==true){
                      if(is_dir("../".$desired_dir)==false){
                          // Create directory if it does not exist
                          mkdir("$desired_dir", 0700);        
                      }
                      if(is_dir($file_name)==false){
                          // rename the file if another one exist
                          move_uploaded_file($file_tmp, $new_file_name1);
                      }else{                                  
                          $new_dir = $new_file_name1.time();
                          rename($file_tmp, $new_dir) ;               
                      }

                      $itm = $controllerEvent->getEventByEventId($event_id);
                      $itm->photo_url = Constants::ROOT_URL.$new_file_name;
                      $controllerEvent->updateEvent($itm);

                    }else{
                        print_r($errors);
                    }
                }

                $results = $controllerRest->getEventByEventId($event_id);
                $dealObj = getJSONObject($results);

                $resultsCategories = $controllerRest->getResultAllCategories($event_id);
                $arrayCategories = $controllerRest->getArrayJSON($resultsCategories);
                
                $jsonArray = array();
                $jsonArray['status'] = array('status_code' => "-1", 'status_text' => "Success.");
                $dealObj['categories'] = json_encode($arrayCategories);
                $jsonArray['event'] = $dealObj;
                echo json_encode($jsonArray);
            }
            else {
                $jsonArray = array();
                $jsonArray['status'] = array('status_code' => "5", 'status_text' => "It seems you are out of sync. Please relogin again.");
                echo json_encode($jsonArray);
            }    
        }
        else {
            $jsonArray = array();
            $jsonArray['status'] = array('status_code' => "5", 'status_text' => "It seems you are out of sync. Please relogin again.");
            echo json_encode($jsonArray);
        }
    }
    else {
        $jsonArray = array();
        $jsonArray['status'] = array('status_code' => "3", 'status_text' => "Invalid Access.");
        echo json_encode($jsonArray);
    }

    function getJSONObject($results) {
        $arrayObj = array();
        foreach ($results as $row) {
            foreach ($row as $columnName => $field) {
                if(!is_numeric($columnName)) {
                    $val = trim(strip_tags($field));
                    $val = preg_replace('~[\r\n]+~', '', $val);
                    $val = htmlspecialchars(trim(strip_tags($val)));
                    $arrayObj[$columnName] = "".$val."";
                }
            }
        }
        return $arrayObj;
    }

    function getArrayJSONObject($results) {

        $arrayObjs = array();
        $ind = 0;
        foreach ($results as $row) {
            $arrayObj = array();
            foreach ($row as $columnName => $field) {
                if(!is_numeric($columnName)) {
                    $val = trim(strip_tags($field));
                    $val = preg_replace('~[\r\n]+~', '', $val);
                    $val = htmlspecialchars(trim(strip_tags($val)));
                    $filter = $val;
                    $arrayObj[$columnName] = "".$filter."";
                }
            }
            $arrayObjs[$ind] = $arrayObj;
            $ind += 1;
        }

        return $arrayObjs;
    }

?>