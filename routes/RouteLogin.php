<?php 
	// THIS ROUTE USES THE ROOT DIRECTORY DECLARATION
	// ANY FILE ACCESS WILL BE POINTING TO THE ROOT DIRECTORY
	function login() {
		$controllerAuthentication = new ControllerAuthentication();
		$auth = $controllerAuthentication->login($_POST['username'], md5($_POST['password']) );
		
		if($auth != null) {
			$_SESSION['name'] = $auth->name;
			header("Location: home.php");
		}
		else {
			echo "<script>alert('Invalid Username/Password.');</script>";
		}
	}
?>