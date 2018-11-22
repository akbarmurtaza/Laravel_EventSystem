<?php 
	require 'routes/RouteHome.php';
	require 'routes/RoutePost.php';
	require 'routes/RouteEvent.php';

	$params = getParamsFromEvent();

    if($params == null) {
        header("Location: 403.php");
        return;
    }

    if(count($params) == 0) {
        header("Location: 403.php");
        return;
    }

    $posts = getPostsFromEventId($params['event_id']);

    if(!empty($_SERVER['QUERY_STRING'])) {
        $params = getParams();
        if($params == null) {
            header("Location: 403.php");
            return;
        }
        if(count($params) == 0) {
            header("Location: 403.php");
            return;
        }
        if(!empty($params['action_delete']) ) {

        	$extras = new Extras();
            $params1 = array();
      		$params1[0] = array( 'event_id', $params['event_id']);
            $url = $extras->encryptParams(KEY_SALT, $params1, 'event_posts.php');

            deletePost($params['post_id'], $url);
        }
    }
?>

<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="http://getbootstrap.com/assets/ico/favicon.ico">

    <title>Event Finder</title>
    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="bootstrap/css/navbar-fixed-top.css" rel="stylesheet">
    <link href="bootstrap/css/custom.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
  	<!-- Fixed navbar -->
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
      	<div class="container">
	        <div class="navbar-header">
	         	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
		            <span class="sr-only">Toggle navigation</span>
		            <span class="icon-bar"></span>
		            <span class="icon-bar"></span>
		            <span class="icon-bar"></span>
	          	</button>
	          	<a class="navbar-brand" href="#">Event Finder</a>
	        </div>

	        <div class="navbar-collapse collapse">
	          	<ul class="nav navbar-nav">
		            <li ><a href="home.php">Home</a></li>
		            <li ><a href="categories.php">Categories</a></li>
		            <li class="active"><a href="events.php"> Events</a></li>
		            <li ><a href="news.php">News</a></li>
		            <li><a href="admin_access.php">Admin Access</a></li>
		            <li ><a href="users.php">Users</a></li>
	          	</ul>
	          
	          	<ul class="nav navbar-nav navbar-right">
	            	<li ><a href="index.php">Logout</a></li>
	         	 </ul>
	        </div><!--/.nav-collapse -->
      	</div>
    </div><!-- /.Fixed navbar -->

    <div class="container">
    	<div class="row">
    		<div class="col-md-12">
		    	<div class="panel panel-default">
		            <!-- Default panel contents -->
		            <div class="panel-heading clearfix">
		            	<h4 class="panel-title pull-left" style="padding-top:7px;padding-bottom:6px;">Events</h4>
		                <div class="btn-group pull-right">
		                	<form method="POST" action="">
				                <a href="events.php" class="btn btn-default btn-sm"><span class='glyphicon glyphicon-arrow-left'></span></a>
				            </form>
		                </div>
					</div>

		            <!-- Table -->
		            <table class="table">
		                <thead>
		                    <tr>
		                        <th>#</th>
		                        <th>Post</th>
		                        <th>Full Name</th>
		                        <th>Date Added</th>
		                        <th>Action</th>
		                    </tr>
		                </thead>
			            <tbody>
			                <?php 
			                    if($posts != null) {
			                    	$ind = 1;
			                    	foreach ($posts as $post)  {
			                      		$extras = new Extras();

				                        $params = array();
			                      		$params[0] = array( 'post_id', $post->post_id );
			                      		$params[1] = array( 'action_delete', 1);
			                      		$params[2] = array( 'event_id', $post->event_id);
				                        $deleteUrl = $extras->encryptParams(KEY_SALT, $params, 'event_posts.php');

				                        $new_date = date("Y-m-d h:i A", strtotime($post->gmt_date_added));

			                            echo "<tr>";
			                            echo "<td>$ind</td>";
			                            echo "<td>$post->post</td>";
			                            echo "<td>$post->full_name</td>";
			                            echo "<td>$new_date</td>";
			                            echo "<td>
		                                    	<button class='btn btn-primary btn-xs' data-toggle='modal' data-target='#modal_$post->post_id'><span class='glyphicon glyphicon-remove'></span></button>
			                            	</td>";
			                            echo "</tr>";
			                            ++$ind;

			                            echo "<div class='modal fade' id='modal_$post->post_id' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
		                                    	<div class='modal-dialog'>
		                                          	<div class='modal-content'>
		                                              	<div class='modal-header'>
		                                                    <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
		                                                    <h4 class='modal-title' id='myModalLabel'>Deleting Post</h4>
		                                              	</div>
		                                              	<div class='modal-body'>
		                                                	<p>Deleting this is not irreversible. Do you wish to continue?
		                                              	</div>
		                                              	<div class='modal-footer'>
		                                                	<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
		                                                	<a type='button' class='btn btn-primary' href='$deleteUrl'>Delete</a>
		                                              	</div>
		                                          	</div>
		                                      </div>
		                                </div>";
			                      	}
			                    }
			                ?>
			            </tbody>
		            </table>
		        </div>
		    </div>
	    </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="bootstrap/js/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.js"></script>
    
  
</body>
</html>