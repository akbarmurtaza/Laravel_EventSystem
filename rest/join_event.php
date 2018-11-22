<?php

    require '../header_rest.php';
    $controllerRest = new ControllerRest();
    $controllerEvent = new ControllerEvent();
    $controllerCategory = new ControllerCategory();
    $controllerEventCategory = new ControllerEventCategory();
    $controllerUser = new ControllerUser();
    $controllerAttendee = new ControllerAttendee();

    $lat = 0;
    if(!empty($_GET['lat']))
        $lat = $_GET['lat'];

    $lon = 0;
    if(!empty($_GET['lon']))
        $lon = $_GET['lon'];

    $user_id = 0;
    if( !empty($_GET['user_id']) )
        $user_id = $_GET['user_id'];


    $user_id = 0;
    if( !empty($_GET['user_id']) )
        $user_id = $_GET['user_id'];

    $event_id = 0;
    if( !empty($_GET['event_id']) )
        $event_id = $_GET['event_id'];

    $api_key = "";
    if(!empty($_GET['api_key']))
        $api_key = $_GET['api_key'];

    if(Constants::API_KEY != $api_key) {
        $jsonArray = array();
        $jsonArray['status'] = array('status_code' => "3", 'status_text' => "Invalid Access. Please relogin.");
        echo json_encode($jsonArray);
    }
    else if($event_id > 0 && $user_id > 0) {

        $itm = $controllerAttendee->getAttendeeByEventIdAndUserId($event_id, $user_id);
        if($itm == null) {
            $itm = new Attendee();
            $itm->event_id = $event_id;
            $itm->is_going = 1;
            $itm->user_id = $user_id;
            $itm->created_at = time();
            $itm->updated_at = time();
            $itm->is_deleted = 0;
            $controllerAttendee->insertAttendee($itm);
        }

        $results = $controllerRest->getResultEventsByEventId($event_id, $lat, $lon);
        $arrayJSON = array();

        $arrayObj = getArrayObjectJSON($results);
        $arrayObj['categories'] = "";
        $event_id = $arrayObj['event_id'];
        if($event_id > 0) {
            $resultsCategories = $controllerRest->getResultAllCategories($event_id);
            $arrayCategories = getArrayJSON($resultsCategories);
            $arrayObj['categories'] = json_encode($arrayCategories);

            $resultsUser = $controllerRest->getResultUserForEvent(0, 10, $event_id);
            $arrayAttendees = getArrayJSONUser($resultsUser);
            $arrayObj['attendees'] = json_encode($arrayAttendees);
            $arrayObj['attendees_total'] = $controllerRest->getResultUsersForEventTotal($event_id);
            $arrayObj['attendees_fetch_count'] = $resultsUser->rowCount();
            $arrayObj['is_going_count'] = $controllerRest->getResultUserForEventGoingCount($event_id);
            $arrayObj['is_invited_count'] = $controllerRest->getResultUserForEventInvitedCount($event_id);
            $arrayObj['is_interested_count'] = $controllerRest->getResultUserForEventInterestedCount($event_id);

            $resultsPosts = $controllerRest->getResultPosts(0, 2, $event_id);
            $arrayPosts = getArrayJSON($resultsPosts);
            $arrayObj['posts'] = json_encode($arrayPosts);

            $resultIds = $controllerRest->getResultUserIdsForEventWhoJoined($event_id);
            $arrayIds = getArrayJSONIds($resultIds);
            $arrayObj['user_ids'] = json_encode($arrayIds);
        }

        $arrayJSON['event'] = $arrayObj;
        $arrayStatus = array('status_code' => '-1', 'status_text' => 'Success');
        $arrayJSON['status'] = $arrayStatus;
        echo json_encode($arrayJSON);
    }
    else {
        $jsonArray = array();
        $jsonArray['status'] = array('status_code' => "3", 'status_text' => "Invalid Access. Please relogin.");
        echo json_encode($jsonArray);
    }
    
    function getArrayJSONIds($results) {
        $ind = 0;
        $arrayObj = array();
        foreach ($results as $row) {
            foreach ($row as $columnName => $field) {
                if(!is_numeric($columnName)) {
                    $val = trim(strip_tags($field));
                    $val = preg_replace('~[\r\n]+~', '', $val);
                    $val = htmlspecialchars(trim(strip_tags($val)));
                    $arrayObj[$ind] = "".$val."";
                }
            }
            $ind += 1;
        }
        return $arrayObj;
    }

    function getArrayJSON($results) {
        $ind = 0;
        $arrayObs = array();
        foreach ($results as $row) {
            $arrayObj = array();
            foreach ($row as $columnName => $field) {
                if(!is_numeric($columnName)) {
                    $val = trim(strip_tags($field));
                    $val = preg_replace('~[\r\n]+~', '', $val);
                    $val = htmlspecialchars(trim(strip_tags($val)));
                    $arrayObj[$columnName] = "".$val."";
                }
            }
            $arrayObs[$ind] = $arrayObj;
            $ind += 1;
        }
        return $arrayObs;
    }

    function getArrayJSONUser($results) {
        $controllerRest = new ControllerRest();
        $ind = 0;
        $arrayObs = array();
        foreach ($results as $row) {
            $arrayObj = array();
            foreach ($row as $columnName => $field) {
                if(!is_numeric($columnName)) {
                    $val = trim(strip_tags($field));
                    $val = preg_replace('~[\r\n]+~', '', $val);
                    $val = htmlspecialchars(trim(strip_tags($val)));
                    $arrayObj[$columnName] = "".$val."";
                }
            }
            $user_id = $arrayObj['user_id'];
            if($user_id > 0) {
                $arrayObj['events_attended_count'] = $controllerRest->getResultUserEventAttendedCount($user_id);
            }
            $arrayObs[$ind] = $arrayObj;
            $ind += 1;
        }
        return $arrayObs;
    }

    function getArrayObjectJSON($results) {
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
?>