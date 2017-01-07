<?php

ini_set( 'display_errors', 1 );
ini_set( 'error_reporting', E_ALL );

include_once( 'autoload.php' );
//set_exception_handler(array('Core_Misc','DisplayException'));

use Core\Core;
use Core\Referer;
use Core\Router;

Core::Initialize();

new Referer ();

new Router ();
