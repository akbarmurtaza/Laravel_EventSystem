<?php 
	// THIS ROUTE USES THE ROOT DIRECTORY DECLARATION
	// ANY FILE ACCESS WILL BE POINTING TO THE ROOT DIRECTORY
	require_once 'header.php';

	function getUsers() {
		$controller = new ControllerUser();
		return $controller->getUsers();
	}

	function searchUser($search) {
		$controller = new ControllerUser();
		return $controller->getUsersBySearching($search);
	}

	// returns an array "param" => "value"
	function getParamsFromUser() {
		$extras = new Extras();
  		return $extras->decryptParams(KEY_SALT, $_SERVER['QUERY_STRING']);
	}

	function updateUserAccess($user_id, $deny_access) {
		$controller = new ControllerUser();
  		$controller->updateUserAccess($user_id, $deny_access);
    	echo "<script type='text/javascript'>location.href='users.php';</script>";
	}
?>