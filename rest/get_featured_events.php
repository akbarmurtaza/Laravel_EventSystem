<?php
    require '../header_rest.php';
    $controllerRest = new ControllerRest();

    $lat = 0;
    if(!empty($_GET['lat']))
        $lat = $_GET['lat'];

    $lon = 0;
    if(!empty($_GET['lon']))
        $lon = $_GET['lon'];

    $radius = 0;
    if(!empty($_GET['radius']))
        $radius = $_GET['radius'];

    $api_key = "";
    if(!empty($_GET['api_key']))
        $api_key = $_GET['api_key'];

    if($lat != 0 && $lon != 0 && $radius > 0 && Constants::API_KEY == $api_key) {  
        $results = $controllerRest->getResultFeaturedEvents($radius, $lat, $lon);
        $no_of_rows = $results->rowCount();
        
        $arrayJSON = array();
        $arrayJSON['result_count'] = "".$no_of_rows."";
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

            $arrayObs[$ind] = $arrayObj;
            $ind += 1;
        }

        $arrayStatus = array('status_code' => '-1', 'status_text' => 'Success');
        $arrayJSON['status'] = $arrayStatus;
        $arrayJSON['events'] = $arrayObs;
        echo json_encode($arrayJSON);
    }
    else {
        $arrayJSON = array();
        $arrayStatus = array('status_code' => '403', 'status_text' => 'Invalid Access');
        $arrayJSON['status'] = $arrayStatus;
        echo json_encode($arrayJSON);
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
?>