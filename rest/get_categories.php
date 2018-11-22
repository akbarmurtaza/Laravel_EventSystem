<?php
    require '../header_rest.php';
    $controllerRest = new ControllerRest();

    $api_key = "";
    if(!empty($_GET['api_key']))
        $api_key = $_GET['api_key'];

    if(Constants::API_KEY == $api_key) {  
        $results = $controllerRest->getResultCategories();
        $no_of_rows = $results->rowCount();
        
        $arrayJSON = array();
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
        $arrayJSON['categories'] = $arrayObs;
        echo json_encode($arrayJSON);
    }
    else {
        $arrayJSON = array();
        $arrayStatus = array('status_code' => '403', 'status_text' => 'Invalid Access');
        $arrayJSON['status'] = $arrayStatus;
        echo json_encode($arrayJSON);
    }

?>