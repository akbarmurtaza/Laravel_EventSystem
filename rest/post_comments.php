<?php

    require '../header_rest.php';
    $controllerUser = new ControllerUser();
    $controllerRest = new ControllerRest();
    $controllerPost = new ControllerPost();

    $post = "";
    if( !empty($_POST['post']) )
        $post = $_POST['post'];

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

    $max_count = 10;
    if(!empty($_POST['max_count']))
        $max_count = $_POST['max_count'];

    if(!$controllerUser->isUserIdExistAndHash($user_id, $login_hash) || Constants::API_KEY != $api_key) {
        $jsonArray = array();
        $jsonArray['status'] = array('status_code' => "3", 'status_text' => "Invalid Access. Please relogin.");
        echo json_encode($jsonArray);
    }
    else {
        $itm = new Post();
        $itm->event_id = $event_id;
        $itm->post = $post;
        $itm->gmt_date_added = gmdate('Y-m-d H:i:s');
        $itm->user_id = $user_id;
        $itm->created_at = time();
        $itm->updated_at = time();
        $itm->is_deleted = 0;
        
        $controllerPost->insertPost($itm);

        $results = $controllerRest->getResultPosts(0, $max_count, $event_id);
        $total_no_of_rows = $controllerRest->getResultPostsTotalCount($event_id);
        $no_of_rows = $results->rowCount();

        $arrayJSON = array();
        $arrayJSON['result_count'] = "".$no_of_rows."";
        $arrayJSON['total_no_of_rows'] = "".$total_no_of_rows."";
        $arrayJSON['max_count'] = "".$max_count."";
        $arrayJSON['min_count'] = "0";

        $arrayJSON['status'] = array('status_code' => "-1", 'status_text' => "Success.");
        $arrayJSON['posts'] = getArrayJSONObject($results);
        echo json_encode($arrayJSON);
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