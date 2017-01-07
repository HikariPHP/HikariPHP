<?php
/**
 * @copyright	WebInteractiv
 * @author		Cotin Urse <costin.urse@gmail.com>
 * @desc		Referer Class
 * @package		core
 * since		April 16, 2010
 *
 */

namespace Core;

class Referer {
	public static function Start($location = null){
		Core::startSession();
		if(!isset($location)){
			$_SESSION['referer'] = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		}else {
			$_SESSION['referer'] = $location;	
		}	
	}	
}