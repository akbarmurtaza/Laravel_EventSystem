<?php 
	require 'routes/RouteHome.php';
	require 'routes/RouteCategory.php';
	$featuredEvents = getFeaturedEvents();
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
		            <li class="active"><a href="home.php">Home</a></li>
		            <li ><a href="categories.php">Categories</a></li>
		            <li ><a href="events.php">Events</a></li>
		            <li ><a href="news.php">News</a></li>
		            <li ><a href="admin_access.php">Admin Access</a></li>
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
    		<div class="col-md-8">
		    	<div class="panel panel-default">
		            <!-- Default panel contents -->
		            <div class="panel-heading clearfix">
		            	<h4 class="panel-title pull-left" style="padding-top:7px;padding-bottom:6px;">Featured Events</h4>
		                <div class="btn-group pull-right"></div>
					</div>

		            <!-- Table -->
		            <table class="table">
		                <thead>
		                    <tr>
		                        <th>#</th>
		                        <th>Event Title</th>
		                        <th>Address</th>
		                    </tr>
		                </thead>
			            <tbody>
			                <?php 
			                    if($featuredEvents != null) {
			                      $ind = 1;
			                      foreach ($featuredEvents as $event)  {
			                            echo "<tr>";
			                            echo "<td>$ind</td>";
			                            echo "<td>$event->title</td>";
			                            echo "<td>$event->address</td>";
			                            echo "</tr>";
			                            ++$ind;
			                      	}
			                    }
			                ?>
			            </tbody>
		            </table>
		        </div>
		    </div>
		    <div class="col-md-4">
		    	<div class="panel panel-default">
		            <!-- Default panel contents -->
		            <div class="panel-heading clearfix">
		            	<h4 class="panel-title pull-left" style="padding-top:7px;padding-bottom:6px;">Info</h4>
		                <div class="btn-group pull-right"></div>
					</div>

		            <!-- Table -->
		            <table class="table">
		                <thead>
		                    <tr>
		                        <th>Info</th>
		                        <th>#</th>
		                        <th>Action</th>
		                    </tr>
		                </thead>
			            <tbody>
			                <?php 
	                            $events = getAllEvents();
			                	$count = count($events);

			                    echo "<tr>";
	                            echo "<td>Events</td>";
	                            echo "<td>$count</td>";
	                            echo "<td><a class='btn btn-primary btn-xs' href='events.php'><span class='glyphicon glyphicon-th'></span></a></td>";
	                            echo "</tr>";

	                            $categories = getCategories();
			                	$count = count($categories);

			                    echo "<tr>";
	                            echo "<td>Categories</td>";
	                            echo "<td>$count</td>";
	                            echo "<td><a class='btn btn-primary btn-xs' href='categories.php'><span class='glyphicon glyphicon-th'></span></a></td>";
	                            echo "</tr>";
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