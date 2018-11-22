<?php
	session_start();
    require_once 'application/Config.php';
    require_once 'application/DB_Connect.php';
	require_once 'application/Globals.php';
	require_once 'application/Extras.php';

	// Debugging status
	if (DEBUG)
	{
		// Report all errors, warnings, interoperability and compatibility
		error_reporting(0);
		// Show errors with output
		ini_set("display_errors", "on");
	}
	else
	{
		error_reporting(0);
		ini_set("display_errors", "off");
	}

	if( empty($_SESSION['name']) )
	{
		header("Location:expire.php");
	}

	/*** nullify any existing autoloads ***/
    spl_autoload_register(null, false);

    /*** specify extensions that may be loaded ***/
    spl_autoload_extensions('.php');

    /*** class Loader ***/
    function classLoader($class)
    {
        $filename = $class . '.php';
        $file ='controllers/' . $filename;
        if (!file_exists($file))
        {
            return false;
        }
        include $file;
    }

    function classModelLoader($class)
    {
        $filename = $class . '.php';
        $file ='models/' . $filename;
        if (!file_exists($file))
        {
            return false;
        }
        include $file;
    }

    /*** register the loader functions ***/
    spl_autoload_register('classLoader');
    spl_autoload_register('classModelLoader');
?>
