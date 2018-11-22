<?php

    require '../header_rest.php';
    $controllerRest = new ControllerRest();
    $controllerEvent = new ControllerEvent();
    $controllerCategory = new ControllerCategory();
    $controllerEventCategory = new ControllerEventCategory();
    $controllerUser = new ControllerUser();

    $categories = array();
    if( !empty($_POST['category_ids']) )
        $categories = explode(",", $_POST['category_ids']);

    $event_id = 0;
    if( !empty($_POST['event_id']) )
        $event_id = $_POST['event_id'];

    $event_desc = "";
    if( !empty($_POST['event_desc']) )
        $event_desc = $_POST['event_desc'];

    $address = "";
    if( !empty($_POST['address']) )
        $address = $_POST['address'];

    $photo_url = "";
    if( !empty($_POST['photo_url']) )
        $photo_url = $_POST['photo_url'];

    $gmt_date_set = "";
    if( !empty($_POST['gmt_date_set']) )
        $gmt_date_set = $_POST['gmt_date_set'];

    $ticket_url = "";
    if( !empty($_POST['ticket_url']) )
        $ticket_url = $_POST['ticket_url'];

    $email_address = "";
    if( !empty($_POST['email_address']) )
        $email_address = $_POST['email_address'];

    $contact_no = "";
    if( !empty($_POST['contact_no']) )
        $contact_no = $_POST['contact_no'];

    $user_id = 0;
    if( !empty($_POST['user_id']) )
        $user_id = $_POST['user_id'];

    $login_hash = "";
    if( !empty($_POST['login_hash']) )
        $login_hash = $_POST['login_hash'];

    $api_key = "";
    if(!empty($_POST['api_key']))
        $api_key = $_POST['api_key'];

    $title = "";
    if(!empty($_POST['title']))
        $title = $_POST['title'];

    $lat1 = "";
    if(!empty($_POST['lat']))
        $lat1 = $_POST['lat'];

    $lon1 = "";
    if(!empty($_POST['lon']))
        $lon1 = $_POST['lon'];

    $lat = str_replace(",", ".", $lat1);
    $lon = str_replace(",", ".", $lon1);

    if(isset($_FILES['uploaded_file']['name']))
        $photo_url = uploadPhoto();


    if(!$controllerUser->isUserIdExistAndHash($user_id, $login_hash) || Constants::API_KEY != $api_key) {
        $jsonArray = array();
        $jsonArray['status'] = array('status_code' => "3", 'status_text' => "Invalid Access. Please relogin.");
        echo json_encode($jsonArray);
    }
    else if($event_id > 0) {
        $itm = $controllerEvent->getEventByEventId($event_id);
        $itm->address = $address;
        $itm->event_desc = $event_desc;
        $itm->gmt_date_set = $gmt_date_set;
        $itm->is_ticket_available = strlen($ticket_url) == 0 ? 0 : 1;
        $itm->lat = $lat;
        $itm->lon = $lon;
        $itm->ticket_url = $ticket_url;
        $itm->email_address = $email_address;
        $itm->contact_no = $contact_no;
        $itm->title = $title;
        $itm->user_id = $user_id;
        $itm->photo_url = $photo_url;
        $itm->updated_at = time();

        $controllerEvent->updateEvent($itm);

        $len = count($categories);
        if($len > 0) {
            $controllerEventCategory->deleteEventCategoriesByEventId($event_id);
            for($x = 0; $x < $len; $x++) {
                $catId = $categories[$x];

                $assoc = $controllerEventCategory->getCategoryEventByCategoryIdAndEventId($catId, $event_id);
                if($assoc == null) {
                    $cat = new EventCategory();
                    $cat->event_id = $event_id;
                    $cat->category_id = $catId;
                    $cat->updated_at = time();
                    $cat->created_at = time();
                    $controllerEventCategory->insertCategoryEvent($cat);
                }
                else {
                    $controllerEventCategory->restoreCategoryEvent($catId, $event_id);
                }
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
        $itm = new Event();
        $itm->address = $address;
        $itm->event_desc = $event_desc;
        $itm->gmt_date_set = $gmt_date_set;
        $itm->is_ticket_available = strlen($ticket_url) == 0 ? 0 : 1;
        $itm->lat = $lat;
        $itm->lon = $lon;
        $itm->ticket_url = $ticket_url;
        $itm->email_address = $email_address;
        $itm->contact_no = $contact_no;
        $itm->title = $title;
        $itm->user_id = $user_id;
        $itm->is_featured = 0;
        $itm->photo_url = $photo_url;
        $itm->created_at = time();
        $itm->updated_at = time();
        $itm->is_deleted = 0;
        
        $controllerEvent->insertEvent($itm);
        $event_id = $controllerEvent->getLastInsertedId();

        $len = count($categories);
        if($len > 0) {
            for($x = 0; $x < $len; $x++) {
                $catId = $categories[$x];
                $cat = new Category();
                $cat->event_id = $event_id;
                $cat->category_id = $catId;
                $cat->updated_at = time();
                $cat->created_at = time();
                $controllerEventCategory->insertCategoryEvent($cat);
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

    function uploadPhoto() {
        $desired_dir = Constants::IMAGE_UPLOAD_DIR;
        $errors = array();
        $file_name = $_FILES['uploaded_file']['name'];
        $file_size = $_FILES['uploaded_file']['size'];
        $file_tmp = $_FILES['uploaded_file']['tmp_name'];
        $file_type= $_FILES['uploaded_file']['type'];

        $timestamp =  uniqid();
        $temp = explode(".", $_FILES["uploaded_file"]["name"]);
        $extension = end($temp);

        $new_file_name = $desired_dir."/"."photo_".$timestamp.".".$extension;
        $new_file_name1 = "../".$desired_dir."/"."photo_".$timestamp.".".$extension;
        if(empty($errors) == true){
          if(is_dir("../".$desired_dir) == false){
              // Create directory if it does not exist
              mkdir("$desired_dir", 0700);        
          }
          if(is_dir($file_name) == false){
              // rename the file if another one exist
              move_uploaded_file($file_tmp, $new_file_name1);
          }else{                                  
              $new_dir = $new_file_name1.time();
              rename($file_tmp, $new_dir);               
          }

          return Constants::ROOT_URL.$new_file_name;
        }
        else{
            return "";
        }
    }
?>