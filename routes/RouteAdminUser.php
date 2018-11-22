<?php 
	// THIS ROUTE USES THE ROOT DIRECTORY DECLARATION
	// ANY FILE ACCESS WILL BE POINTING TO THE ROOT DIRECTORY
	require_once 'header.php';

	// returns an array "param" => "value"
	function getParamsFromAdminUser() {
		$extras = new Extras();
  		return $extras->decryptParams(KEY_SALT, $_SERVER['QUERY_STRING']);
	}

	function updateAccessUser($auth) {
		$controller = new ControllerAuthentication();
		if(!$controller->checkUsername($_POST['username'])) {
			$auth->name = trim(strip_tags($_POST['name']));
			$auth->username = trim(strip_tags($_POST['username']));

			$pass = trim(strip_tags($_POST['password']));
			$password_confirm = trim(strip_tags($_POST['password_confirm']));
			$auth->password = md5( $pass );

			if(strlen($pass) < 8) {
				echo "<script >alert('Password field must be atleast 8 alphanumeric characters.');</script>";
			}
			else if($pass != $password_confirm) {
				echo "<script >alert('Password does not match.');</script>";
			}
			else {
				$controller->updateAccessUser($auth);
				echo "<script type='text/javascript'>location.href='admin_access.php';</script>";
			}
    	}
	    else {
	          echo "<script >alert('Username already taken.');</script>";
	    }
	}

	function addAdminUser() {
		$controller = new ControllerAuthentication();
		if(!$controller->checkUsername($_POST['username'])) {
			$itm = new Authentication();
			$itm->name = trim(strip_tags($_POST['name']));
			$itm->username = trim(strip_tags($_POST['username']));

			$pass = trim(strip_tags($_POST['password']));
			$password_confirm = trim(strip_tags($_POST['password_confirm']));
			$itm->password = md5( $pass );

			if(strlen($pass) < 8) {
				echo "<script >alert('Password field must be atleast 8 alphanumeric characters.');</script>";
			}
			else if($pass != $password_confirm) {
				echo "<script >alert('Password does not match.');</script>";
			}
			else {
				$controller->insertAccessUser($itm);
				echo "<script type='text/javascript'>location.href='admin_access.php';</script>";
			}
    	}
	    else {
	          echo "<script >alert('Username already taken.');</script>";
	    }
	}

	function getAccessUserByAuthenticationId($authentication_id) {
		$controller = new ControllerAuthentication();
  		return $controller->getAccessUserByAuthenticationId($authentication_id);
	}

	function getAccessUsersBySearching($search) {
		$controller = new ControllerAuthentication();
		return $controller->getAccessUsersBySearching($search);
	}

	function getAccessUsers() {
		$controller = new ControllerAuthentication();
  		return $controller->getAccessUser();
	}

	function deleteAccessUser($authentication_id, $is_deleted) {
		$controller = new ControllerAuthentication();
		$controller->deleteAccessUser($authentication_id, $is_deleted);
		echo "<script type='text/javascript'>location.href='admin_access.php';</script>";
	}

	function updateAdminUserAccess($auth_id, $deny_access) {
		$controller = new ControllerAuthentication();
  		$controller->denyAccessUser($auth_id, $deny_access);
    	echo "<script type='text/javascript'>location.href='admin_access.php';</script>";
	}

	

?>