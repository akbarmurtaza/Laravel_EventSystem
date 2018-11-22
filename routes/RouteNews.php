<?php 
	// THIS ROUTE USES THE ROOT DIRECTORY DECLARATION
	// ANY FILE ACCESS WILL BE POINTING TO THE ROOT DIRECTORY
	require_once 'header.php';

	function addNews() {
		$controllerNews = new ControllerNews();
		$news = new News();
	    $news->news_url = htmlspecialchars(trim(strip_tags($_POST['news_url'])));
	    $news->news_title = htmlspecialchars(trim(strip_tags($_POST['news_title'])));
	    $news_content = preg_replace('~[\r\n]+~', '', $_POST['news_content']);
	    $news->news_content = htmlspecialchars(trim(strip_tags($news_content)));
	    $news->updated_at = time();
	    $news->created_at = time();
	    $news->photo_url = trim(strip_tags($_POST['photo_url']));
	    if( !empty($_FILES["file_upload"]["name"]) ) {
			$news->photo_url = uploadFile("file_upload", "news_photo");
		}
	    $controllerNews->insertNews($news);
	    echo "<script type='text/javascript'>location.href='news.php';</script>";
	}

	// returns an array "param" => "value"
	function getParamsFromNews() {
		$extras = new Extras();
  		return $extras->decryptParams(KEY_SALT, $_SERVER['QUERY_STRING']);
	}

	function getNewsFromNewsId($news_id) {
		$controllerNews = new ControllerNews();
  		return $controllerNews->getNewsByNewsId($news_id);
	}

	function updateNews($news) {
		$controllerNews = new ControllerNews();
		$news->news_url = htmlspecialchars(trim(strip_tags($_POST['news_url'])));
	    $news->news_title = htmlspecialchars(trim(strip_tags($_POST['news_title'])));
	    $news_content = preg_replace('~[\r\n]+~', '', $_POST['news_content']);
	    $news->news_content = htmlspecialchars(trim(strip_tags($news_content)));
	    $news->updated_at = time();
	    $news->photo_url = trim(strip_tags($_POST['photo_url']));
	    if( !empty($_FILES["file_upload"]["name"]) ) {
			$news->photo_url = uploadFile("file_upload", "news_photo");
		}
	    $controllerNews->updateNews($news);
	    echo "<script type='text/javascript'>location.href='news.php';</script>";
	}

	function getSearchNews($search) {
		$controllerNews = new ControllerNews();
		return $controllerNews->getNewsBySearching($search);
	}

	function deleteNews($news_id) {
		$controllerNews = new ControllerNews();
		$controllerNews->deleteNews($news_id, 1);
		echo "<script type='text/javascript'>location.href='news.php';</script>";
	}

	function getNews() {
		$controllerNews = new ControllerNews();
  		return $controllerNews->getNews();
	}

	function getNewsFromEventId($event_id) {
		$controllerNews = new ControllerNews();
  		return $controllerNews->getNewsByEventId($event_id);
	}
?>