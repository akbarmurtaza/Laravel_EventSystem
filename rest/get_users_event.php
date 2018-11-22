<?php
    require '../header_rest.php';
    $controllerRest = new ControllerRest();

    $min_count = 0;
    if(!empty($_GET['min_count']))
        $min_count = $_GET['min_count'];

    $max_count = 0;
    if(!empty($_GET['max_count']))
        $max_count = $_GET['max_count'];

    $event_id = 0;
    if(!empty($_GET['event_id']))
        $event_id = $_GET['event_id'];

    $api_key = "";
    if(!empty($_GET['api_key']))
        $api_key = $_GET['api_key'];

    if(Constants::API_KEY == $api_key) {  
        $results = $controllerRest->getResultUserForEvent($min_count, $max_count, $event_id);
        $total_no_of_rows = $controllerRest->getResultUsersForEventTotal($event_id);
        $no_of_rows = $results->rowCount();
        
        $arrayJSON = array();
        $arrayJSON['result_count'] = "".$no_of_rows."";
        $arrayJSON['total_no_of_rows'] = "".$total_no_of_rows."";
        $arrayJSON['max_count'] = "".$max_count."";
        $arrayJSON['min_count'] = "".$min_count."";
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

        $arrayStatus = array('status_code' => '-1', 'status_text' => 'Success');
        $arrayJSON['status'] = $arrayStatus;
        $arrayJSON['users'] = $arrayObs;
        echo json_encode($arrayJSON);
    }
    else {
        $arrayJSON = array();
        $arrayStatus = array('status_code' => '403', 'status_text' => 'Invalid Access');
        $arrayJSON['status'] = $arrayStatus;
        echo json_encode($arrayJSON);
    }
?>