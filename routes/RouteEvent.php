<?php 
	// THIS ROUTE USES THE ROOT DIRECTORY DECLARATION
	// ANY FILE ACCESS WILL BE POINTING TO THE ROOT DIRECTORY
	require_once 'header.php';

	function getEventFromEventId($event_id) {
		$controllerEvent = new ControllerEvent();
  		return $controllerEvent->getEventByEventid($event_id);
	}

	// returns an array "param" => "value"
	function getParamsFromEvent() {
		$extras = new Extras();
  		return $extras->decryptParams(KEY_SALT, $_SERVER['QUERY_STRING']);
	}

	function addEvent() {
		$controllerEvent = new ControllerEvent();
		$controllerEventCategory = new ControllerEventCategory();

		$event = new Event();
		$event->title = htmlspecialchars(trim(strip_tags($_POST['title'])));
		$event->email_address = htmlspecialchars(trim(strip_tags($_POST['email_address'])));
		$event->event_desc = htmlspecialchars(trim(strip_tags($_POST['event_desc'])));
		$event->contact_no = htmlspecialchars(trim(strip_tags($_POST['contact_no'])));
		$event->ticket_url = htmlspecialchars(trim(strip_tags($_POST['ticket_url'])));
		$event->photo_url = htmlspecialchars(trim(strip_tags($_POST['photo_url'])));
		$event->gmt_date_set = $_POST['gmt_date_set'];
		$event->is_featured = htmlspecialchars(trim(strip_tags($_POST['is_featured'])));
		$event->lat = htmlspecialchars(trim(strip_tags($_POST['lat'])));
		$event->lon = htmlspecialchars(trim(strip_tags($_POST['lon'])));
		$event->address = htmlspecialchars(trim(strip_tags($_POST['address'])));
		$event->is_deleted = 0;
		$event->user_id = -1;
	    $event->created_at = time();
	    $event->updated_at = time();
	    $event->is_ticket_available = strlen($event->ticket_url) ? 1 : 0;

		if( !empty($_FILES["file_upload"]["name"]) ) {
			$event->photo_url = uploadFile("file_upload", "event_photo");
		}
	    $controllerEvent->insertEvent($event);

	    $event_id = $controllerEvent->getLastInsertedId();
	    if (isset($_POST['chk_categories'])) {
	        $array = $_POST['chk_categories'];
	        foreach ($array as $category_id)  {
	        	$catEvent = new EventCategory();
	        	$catEvent->category_id = $category_id;
			    $catEvent->event_id = $event_id;
			    $catEvent->created_at = time();
			    $catEvent->updated_at = time();
			    $catEvent->is_deleted = 0;
			    $controllerEventCategory->insertCategoryEvent($catEvent);
	        }
	    }

	    echo "<script type='text/javascript'>location.href='events.php';</script>";
	}

	function updateEvent($event) {
		$controllerEvent = new ControllerEvent();
		$controllerEventCategory = new ControllerEventCategory();

		$event->title = htmlspecialchars(trim(strip_tags($_POST['title'])));
		$event->email_address = htmlspecialchars(trim(strip_tags($_POST['email_address'])));
		$event->event_desc = htmlspecialchars(trim(strip_tags($_POST['event_desc'])));
		$event->contact_no = htmlspecialchars(trim(strip_tags($_POST['contact_no'])));
		$event->ticket_url = htmlspecialchars(trim(strip_tags($_POST['ticket_url'])));
		$event->photo_url = htmlspecialchars(trim(strip_tags($_POST['photo_url'])));
		$event->gmt_date_set = $_POST['gmt_date_set'];
		$event->is_featured = htmlspecialchars(trim(strip_tags($_POST['is_featured'])));
		$event->lat = htmlspecialchars(trim(strip_tags($_POST['lat'])));
		$event->lon = htmlspecialchars(trim(strip_tags($_POST['lon'])));
		$event->address = htmlspecialchars(trim(strip_tags($_POST['address'])));
	    $event->updated_at = time();

	    if (isset($_POST['chk_categories'])) {
	        $array = $_POST['chk_categories'];
	        $controllerEventCategory->deleteEventCategoriesByEventId($event->event_id);
	        foreach ($array as $category_id)  {
	        	$assoc = $controllerEventCategory->getCategoryEventByCategoryIdAndEventId($category_id, $event->event_id);
	        	if($assoc == null) {
	        		$catEvent = new EventCategory();
		        	$catEvent->category_id = $category_id;
				    $catEvent->event_id = $event->event_id;
				    $catEvent->created_at = time();
				    $catEvent->updated_at = time();
				    $catEvent->is_deleted = 0;
				    $controllerEventCategory->insertCategoryEvent($catEvent);
	        	}
	        	else if( $assoc->is_deleted == 1) {
	        		$controllerEventCategory->restoreCategoryEvent($category_id, $event->event_id);
	        	}
	        }
	    }
		if( !empty($_FILES["file_upload"]["name"]) ) {
			$event->photo_url = uploadFile("file_upload", "event_photo");
		}
	    $controllerEvent->updateEvent($event);
	    echo "<script type='text/javascript'>location.href='events.php';</script>";
	}

	function uploadFile($key, $prefix) {
    	$desired_dir = Constants::IMAGE_UPLOAD_DIR;
      	$errors = array();
		$file_name = $_FILES[$key]['name'];
		$file_size = $_FILES[$key]['size'];
		$file_tmp = $_FILES[$key]['tmp_name'];
		$file_type = $_FILES[$key]['type'];
		if($file_size > 5242880) {
			$errors[] = 'File size must be less than 5 MB';
		}    
		$timestamp =  uniqid();
		$temp = explode(".", $_FILES[$key]["name"]);
		$extension = end($temp);
		$new_file_name = $desired_dir."/".$prefix."_".$timestamp.".".$extension;
		if(empty($errors) == true) {
			if(is_dir($desired_dir) == false) {
				// Create directory if it does not exist
				mkdir("$desired_dir", 0700);        
			}
			if(is_dir($file_name) == false) {
				// rename the file if another one exist
				move_uploaded_file($file_tmp, $new_file_name);
			}
			else {                                  
				$new_dir = $new_file_name.time();
				rename($file_tmp, $new_dir);     
				$new_file_name = $new_dir;          
			}
			return Constants::ROOT_URL.$new_file_name;
		}
		return null;
	}

	function getSearchEvents($search) {
		$ControllerEvent = new ControllerEvent();
		return $ControllerEvent->getEventsBySearching($search);
	}

	function deleteEvent($event_id, $landing_page) {
		$ControllerEvent = new ControllerEvent();
		$ControllerEvent->deleteEvent($event_id, 1);
		echo "<script type='text/javascript'>location.href='$landing_page';</script>";
	}

?>