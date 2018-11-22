<?php 
	// THIS ROUTE USES THE ROOT DIRECTORY DECLARATION
	// ANY FILE ACCESS WILL BE POINTING TO THE ROOT DIRECTORY
	require_once 'header.php';

	function getFeaturedEvents() {
		$controllerEvent = new ControllerEvent();
		$array = $controllerEvent->getEventsFeatured();
		return $array;
	}

	function getAllEvents() {
		$controllerEvent = new ControllerEvent();
		$array = $controllerEvent->getEvents();
		return $array;
	}

	// returns an array "param" => "value"
	function getParams() {
		$extras = new Extras();
  		return $extras->decryptParams(KEY_SALT, $_SERVER['QUERY_STRING']);
	}
?>