<?php 
	require 'routes/RouteCategory.php';
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

    $event = getEventFromEventId($params['event_id']);
    $categories = getCategories();
    $selected_categories = getCategoriesFromEventId($params['event_id']);
    if( isset($_POST['submit']) )
    	updateEvent($event);
?>

<!DOCTYPE html>
<html lang="en"><head>
<link href="bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
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
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBB7Tce0Xd3GEb838FF5uRcIe8MQIRdQSo&sensor=false"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script type="text/javascript">
        $(function(){
            var mapDiv = document.getElementById('map');
                var map = new google.maps.Map(mapDiv, {
                	center: new google.maps.LatLng(<?php echo Constants::MAP_DEFAULT_LATITUDE . "," . Constants::MAP_DEFAULT_LONGITUDE; ?> ),
                	zoom: <?php echo Constants::MAP_DEFAULT_ZOOM_LEVEL; ?>,
                 	mapTypeId: google.maps.MapTypeId.ROADMAP,
                });

            var latLng = new google.maps.LatLng( <?php echo "$event->lat, $event->lon";?>, true );
            var marker = new google.maps.Marker({
                    position: latLng,
                    map: map,
                    title: 'Here!'
                });

            map.setCenter(latLng);

            google.maps.event.addListener(map, 'click', function (mouseEvent) {

                if(marker != null)
                	marker.setMap(null);

                var lat = document.getElementById('latitude');
                var longi = document.getElementById('longitude');
                lat.value = mouseEvent.latLng.lat();
                longi.value = mouseEvent.latLng.lng();

                marker = new google.maps.Marker({
                    position: mouseEvent.latLng,
                    map: map,
                    title: 'Here!'
                });

                geocodePosition(marker.getPosition());
            });

            var geocoder = new google.maps.Geocoder();
            function codeAddress(address) {
                //In this case it gets the address from an element on the page, 
                // but obviously you  could just pass it to the method instead
                geocoder.geocode( { 'address': address}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        //In this case it creates a marker, but you can get the lat and lng from the location.LatLng
                        if(marker != null)
                            marker.setMap(null);

                        map.setCenter(results[0].geometry.location);
                        marker = new google.maps.Marker({
                            map: map, 
                            position: results[0].geometry.location
                        });

                        var lat = document.getElementById('latitude');
                        var longi = document.getElementById('longitude');
                        lat.value = results[0].geometry.location.lat();
                        longi.value = results[0].geometry.location.lng();

                    } else {
                        alert("Geocode was not successful for the following reason: " + status);
                    }
                });
            }

            function geocodePosition(pos) {
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({
                    latLng: pos
                    }, function(responses) {
                        if (responses && responses.length > 0) {
                            var txtAddress = document.getElementById('txtAddress');
                            txtAddress.value = responses[0].formatted_address;
                        }
                    });
            }

            document.getElementById("btnGeocode").addEventListener("click", function(){
                var txtAddress = document.getElementById("txtAddress");
                codeAddress(txtAddress.value);
            });
        });

        function validateLatLng(evt) {
            var theEvent = evt || window.event;
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode( key );

            if(theEvent.keyCode == 8 || theEvent.keyCode == 127) { }
            else {
                var regex = /[0-9.]|\./;
                if( !regex.test(key) ) {
                	theEvent.returnValue = false;
                	if(theEvent.preventDefault) theEvent.preventDefault();
                }  
            }
        }

        function validateTextFieldPrice(evt) {
            var theEvent = evt || window.event;
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode( key );
            var txt = document.getElementById('txtPrice');
            var input = txt.value;

            if(theEvent.keyCode == 8 || theEvent.keyCode == 127) { }
            else {
                var regex = /[0-9.]|\./;
                var found = !regex.test(key);

                if(input.indexOf('.') != -1 && key == ".")
                	found = true;

                if( found ) {
                	theEvent.returnValue = false;
                	if(theEvent.preventDefault) theEvent.preventDefault();
                }  
            }
        }

        function validateTextField(evt) {
            var theEvent = evt || window.event;
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode( key );
            var txt = document.getElementById('txtRange');
            var input = txt.value + "" + key;

            if(theEvent.keyCode == 8 || theEvent.keyCode == 127) { }
            else {
                var regex = /[0-9]|\./;
                var found = !regex.test(key);

                if(parseInt(input) < 0 || parseInt(input) > 100)
                	found = true;

                if( found ) {
                	theEvent.returnValue = false;
                	if(theEvent.preventDefault) theEvent.preventDefault();
                }  
            }
        }

        function validateNumberFieldOnly(evt) {
            var theEvent = evt || window.event;
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode( key );
            var txt = document.getElementById('txtNoOfDays');
            var input = txt.value + "" + key;

            if(theEvent.keyCode == 8 || theEvent.keyCode == 127) { }
            else {
                var regex = /[0-9]|\./;
                var found = !regex.test(key);

                if( found ) {
                    theEvent.returnValue = false;
                    if(theEvent.preventDefault) theEvent.preventDefault();
                }  
            }
        }
    </script>
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
		            <li class="active"><a href="events.php">Events</a></li>
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
		            	<h4 class="panel-title pull-left" style="padding-top:7px;padding-bottom:6px;">Update Event</h4>
		                <div class="btn-group pull-right"></div>
					</div>

					<form action="" method="POST" enctype="multipart/form-data">
				        <div class="panel-body">
				            <div class="row">
				                <div class="col-md-7">
				                    <div class="input-group">
				                        <span class="input-group-addon"></span>
				                        <input type="text" class="form-control" placeholder="Title" name="title" required value="<?php echo $event->title; ?>">
				                    </div>

				                    <br />
				                    <div class="input-group">
				                        <span class="input-group-addon"></span>
				                        <input type="text" class="form-control" placeholder="Address" name="address" required id="txtAddress" value="<?php echo $event->address; ?>">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button" id="btnGeocode">Find</button>
                                        </span>
				                    </div>

                                    <br />
                                    <div class="input-group">
                                        <span class="input-group-addon"></span>
                                        <input type="text" class="form-control" placeholder="Email Address" name="email_address" required value="<?php echo $event->email_address; ?>">
                                    </div>

				                    <br />
				                    <div class="input-group">
				                        <span class="input-group-addon"></span>
				                        <input type="text" class="form-control" placeholder="Contact Number" name="contact_no" required value="<?php echo $event->contact_no; ?>">
				                    </div>

				                    <br />
				                    <div class="input-group">
				                        <span class="input-group-addon"></span>
				                        <input type="text" class="form-control" placeholder="Ticket URL" name="ticket_url" value="<?php echo $event->ticket_url; ?>">
				                    </div>

				                    <br />
				                    <div class="input-group">
				                        <span class="input-group-addon"></span>
				                        <input type="text" class="form-control" placeholder="Photo URL" name="photo_url" value="<?php echo $event->photo_url; ?>">
				                    </div>

				                    <div>
				                        <input type="hidden" placeholder="Latitude" name="lat" id="latitude" required value="<?php echo $event->lat; ?>">
				                        <input type="hidden" placeholder="Longitude" name="lon" id="longitude" required value="<?php echo $event->lon; ?>">
				                    </div>

				                    <br />
				                    <div class="input-group" style="width:100%;" >
				                        <select class="form-control" style="width:100%;" name="is_featured">
				                        	<option value="-1">Select if Event will be featured</option>
				                        	<option value="1" <?php echo $event->is_featured == 1 ? "selected" : ""; ?> >Event will be Featured</option>
				                        	<option value="0" <?php echo $event->is_featured == 1 ? "" : "selected"; ?> >Event will not be Featured</option>
				                        </select>
				                    </div>

                                    <br />
                                    <div class="form-group">
                                        <?php 
                                            $newDate = date("Y-m-d h:i A", strtotime($event->gmt_date_set));
                                        ?>
                                        <div class="input-group date form_datetime col-md-12" data-date-format="yyyy-mm-dd HH:ii" data-link-field="dtp_input1">
                                            <input class="form-control" size="16" type="text" value="<?php echo $newDate ?>" readonly>
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                        </div>
                                        <input type="hidden" id="dtp_input1" value="<?php echo $event->gmt_date_set; ?>" name="gmt_date_set"/>
                                    </div>

				                    <br />
				                    <div class="input-group">
				                        <span class="input-group-addon"></span>
				                        <textarea type="text" class="form-control" placeholder="Description" rows="10" name="event_desc" id="details"><?php echo $event->event_desc; ?></textarea>
				                    </div>

				                    <br /> 
				                    <p>
				                        <button type="submit" name="submit" class="btn btn-info"  role="button">Save</button> 
				                        <a class="btn btn-info" href="events.php" role="button">Cancel</a>
				                    </p>
				                </div>
				                <div class="col-md-5">
				                	<h4>Click the Map to get latitude/longitude:</h4>
                  					<div id="map" style="width:100%; height:400px"></div>
                  					<br />
                  					<br />
                  					<h4>Upload Photo File:</h4>
                  					<input type="file" name="file_upload" />
                                    <br />
                                    <div class="panel panel-default">
                                      <div class="panel-heading">
                                        <h3 class="panel-title">Select Categories</h3>
                                      </div>
                                      <div class="panel-body">
                                        <table width="100%">
                                            <thead>
                                                <tr>
                                                    <th width="50%"></th>
                                                    <th width="50%"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $ind = 1;
                                                    if($categories != null) {
                                                        foreach ($categories as $category)  {
                                                            $checked = "";
                                                            foreach ($selected_categories as $selCat) {
                                                                if($selCat->category_id == $category->category_id) {
                                                                    $checked = "checked";
                                                                    break;
                                                                }
                                                            }

                                                            if($ind == 1)
                                                                echo "<tr>";

                                                            echo "<td><input type='checkbox' $checked name='chk_categories[]' value='$category->category_id'> $category->category</td>";
                                                            if($ind == 2) {
                                                                $ind = 1;
                                                                echo "</tr>";
                                                            }
                                                            else 
                                                                $ind += 1;
                                                        }
                                                        if($ind == 2)
                                                            echo "</tr>";
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                      </div>
                                    </div>
				                </div>
				            </div>
				        </div>
				    </form>
		        </div>
		    </div>
	    </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="bootstrap/js/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.js"></script>
    <script type="text/javascript" src="bootstrap/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
    <script type="text/javascript" src="bootstrap/js/locales/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
    <script type="text/javascript">
        $('.form_datetime').datetimepicker({
            //language:  'fr',
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0,
            showMeridian: 1,
            format: 'yyyy-mm-dd HH:ii P'
        });
    </script>
  
</body>
</html>