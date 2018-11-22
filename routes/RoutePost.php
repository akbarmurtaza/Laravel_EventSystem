<?php 
	// THIS ROUTE USES THE ROOT DIRECTORY DECLARATION
	// ANY FILE ACCESS WILL BE POINTING TO THE ROOT DIRECTORY
	require_once 'header.php';

	function getPostsFromEventId($post_id) {
		$controllerPost = new ControllerPost();
  		return $controllerPost->getPostsByEventId($post_id);
	}

	function deletePost($post_id, $landing_page) {
		$controllerPost = new ControllerPost();
		$controllerPost->deletePost($post_id, 1);
		echo "<script type='text/javascript'>location.href='$landing_page';</script>";
	}

?>