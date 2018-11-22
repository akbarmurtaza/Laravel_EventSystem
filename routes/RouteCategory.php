<?php 
	// THIS ROUTE USES THE ROOT DIRECTORY DECLARATION
	// ANY FILE ACCESS WILL BE POINTING TO THE ROOT DIRECTORY
	require_once 'header.php';

	function addCategory() {
		$controllerCategory = new ControllerCategory();
		$category = new Category();
		$category->category = htmlspecialchars(trim(strip_tags($_POST['category'])));
		$category->category_icon = '';
	    $category->created_at = time();
	    $category->updated_at = time();
	    $controllerCategory->insertCategory($category);
	    echo "<script type='text/javascript'>location.href='categories.php';</script>";
	}

	// returns an array "param" => "value"
	function getParamsFromCategory() {
		$extras = new Extras();
  		return $extras->decryptParams(KEY_SALT, $_SERVER['QUERY_STRING']);
	}

	function getCategoryFromCategoryId($category_id) {
		$controllerCategory = new ControllerCategory();
  		return $controllerCategory->getCategoryByCategoryId($category_id);
	}

	function updateCategory($category) {
		$controllerCategory = new ControllerCategory();
		$category->category = htmlspecialchars(trim(strip_tags($_POST['category'])));
	    $category->updated_at = time();
	    $controllerCategory->updateCategory($category);
	    echo "<script type='text/javascript'>location.href='categories.php';</script>";
	}

	function getSearchCategories($search) {
		$controllerCategory = new ControllerCategory();
		return $controllerCategory->getCategoriesBySearching($search);
	}

	function deleteCategory($category_id) {
		$controllerCategory = new ControllerCategory();
		$controllerCategory->deleteCategory($category_id, 1);
		echo "<script type='text/javascript'>location.href='categories.php';</script>";
	}

	function getCategories() {
		$controllerCategory = new ControllerCategory();
  		return $controllerCategory->getCategories();
	}

	function getCategoriesFromEventId($event_id) {
		$controllerCategory = new ControllerCategory();
  		return $controllerCategory->getCategoriesByEventId($event_id);
	}
?>