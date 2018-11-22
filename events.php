<?php 
	require 'routes/RouteHome.php';
	require 'routes/RouteEvent.php';
	$events = getAllEvents();

	$search_criteria = "";
  	if( isset($_POST['button_search']) ) {
    	$search_criteria = trim(strip_tags($_POST['search']));
    	$events = getSearchEvents($search_criteria);
    }

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
            deleteEvent($params['event_id'], "events.php");
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
				                <input type="text" style="height:100%;color:#000000;padding-left:5px;" placeholder="Search" name="search" value="<?php echo $search_criteria; ?>">
				                <button type="submit" name="button_search" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-search"></span></button>
				                <button type="submit" class="btn btn-default btn-sm" name="reset"><span class="glyphicon glyphicon-refresh"></span></button>
				                <a href="event_insert.php" class="btn btn-default btn-sm"><span class='glyphicon glyphicon-plus'></span></a>
				            </form>
		                </div>
					</div>

		            <!-- Table -->
		            <table class="table">
		                <thead>
		                    <tr>
		                        <th>#</th>
		                        <th>Title</th>
		                        <th>Action</th>
		                    </tr>
		                </thead>
			            <tbody>
			                <?php 
			                    if($events != null) {
			                    	$ind = 1;
			                    	foreach ($events as $event)  {
			                      		$extras = new Extras();

			                      		$params = array();
			                      		$params[0] = array( 'event_id', $event->event_id );
				                        $updateUrl = $extras->encryptParams(KEY_SALT, $params, 'event_update.php');

				                        $params = array();
			                      		$params[0] = array( 'event_id', $event->event_id );
			                      		$params[1] = array( 'action_delete', 1);
				                        $deleteUrl = $extras->encryptParams(KEY_SALT, $params, 'events.php');

				                        $params = array();
			                      		$params[0] = array( 'event_id', $event->event_id );
				                        $postsUrl = $extras->encryptParams(KEY_SALT, $params, 'event_posts.php');
				                        
			                            echo "<tr>";
			                            echo "<td>$ind</td>";
			                            echo "<td>$event->title</td>";
			                            echo "<td>
					                            <a class='btn btn-primary btn-xs' href='$updateUrl'><span class='glyphicon glyphicon-pencil'></span></a>
		                                    	<button class='btn btn-primary btn-xs' data-toggle='modal' data-target='#modal_$event->event_id'><span class='glyphicon glyphicon-remove'></span></button>
			                            		<a class='btn btn-primary btn-xs' href='$postsUrl'><span class='glyphicon glyphicon-comment'></span></a>
			                            	</td>";
			                            echo "</tr>";
			                            ++$ind;

			                            echo "<div class='modal fade' id='modal_$event->event_id' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
		                                    	<div class='modal-dialog'>
		                                          	<div class='modal-content'>
		                                              	<div class='modal-header'>
		                                                    <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
		                                                    <h4 class='modal-title' id='myModalLabel'>Deleting Event</h4>
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