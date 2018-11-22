<?php 
	require_once 'application/Config.php';
	require_once 'application/Globals.php';
	require_once 'application/Extras.php';
	
	// Debugging status
	if (DEBUG) 
	{
		// Report all errors, warnings, interoperability and compatibility
		error_reporting(E_ALL|E_STRICT);
		// Show errors with output
		ini_set("display_errors", "on");
	}
	else 
	{
		error_reporting(0);
		ini_set("display_errors", "off");
	}

	require_once '../application/DB_Connect.php';
 
    require_once '../models/User.php';
    require_once '../controllers/ControllerUser.php';
    
    require_once '../controllers/ControllerRest.php';
    
    require_once '../models/Category.php';
    require_once '../controllers/ControllerCategory.php';

    require_once '../models/Event.php';
    require_once '../controllers/ControllerEvent.php';

    require_once '../models/EventCategory.php';
    require_once '../controllers/ControllerEventCategory.php';

    require_once '../models/Attendee.php';
    require_once '../controllers/ControllerAttendee.php';

    require_once '../models/Post.php';
    require_once '../controllers/ControllerPost.php';

    require_once '../models/News.php';
    require_once '../controllers/ControllerNews.php';

?>