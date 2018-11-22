<?php 
    require_once 'routes/RouteAdminUser.php';    

    $params = getParamsFromAdminUser();
    if($params == null) {
        header("Location: 403.php");
        return;
    }

    if(count($params) == 0) {
        header("Location: 403.php");
        return;
    }

    $auth = getAccessUserByAuthenticationId($params['authentication_id']);
    if($auth == null) {
        header("Location: 403.php");
        return;
    }

    if( isset($_POST['submit']) )
      updateAccessUser($auth);

?>


<!DOCTYPE html>
<html lang="en">
<head>
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
    <script type="text/javascript">
        function validateField(evt) {
            var theEvent = evt || window.event;
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode( key );
            if(theEvent.keyCode == 8 || theEvent.keyCode == 127 || theEvent.keyCode == 9) { }
            else {
                var regex = /^([a-z0-9]+-)*[a-z0-9]+$/i;
                if( !regex.test(key) ) {
                  theEvent.returnValue = false;
                  if(theEvent.preventDefault) theEvent.preventDefault();
                }  
            }
        }
    </script>
    
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
                    <li ><a href="events.php">Events</a></li>
                    <li ><a href="news.php">News</a></li>
                    <li class="active"><a href="admin_access.php">Admin Access</a></li>
                    <li ><a href="users.php">Users</a></li>
                </ul>
              
                <ul class="nav navbar-nav navbar-right">
                    <li ><a href="index.php">Logout</a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <!-- Example row of columns -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Update Access User</h3>
                    </div>

                    <div class="panel-body">
                        <div class="row">
                              <div class="col-md-12">
                                  <form action="" method="POST">
                                      <div class="input-group">
                                          <span class="input-group-addon"></span>
                                          <input type="text" class="form-control" placeholder="Username" name="username" onkeypress='validateField(event)' required value="<?php echo $auth->username; ?>">
                                      </div>

                                      <br />
                                      <div class="input-group">
                                          <span class="input-group-addon"></span>
                                          <input type="password" class="form-control" placeholder="Password" name="password" onkeypress='validateField(event)' required>
                                      </div>

                                      <br />
                                      <div class="input-group">
                                          <span class="input-group-addon"></span>
                                          <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirm" onkeypress='validateField(event)' required>
                                      </div>

                                      <br />
                                      <div class="input-group">
                                          <span class="input-group-addon"></span>
                                          <input type="text" class="form-control" placeholder="Full Name" name="name" required value="<?php echo $auth->name; ?>">
                                      </div>

                                      <br /> 
                                      <p>
                                          <button type="submit" name="submit" class="btn btn-info"  role="button">Save</button> 
                                          <a class="btn btn-info" href="admin_access.php" role="button">Cancel</a>
                                      </p>
                                  </form> 
                              </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="bootstrap/js/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.js"></script>
    
  

</body></html>