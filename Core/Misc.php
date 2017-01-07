<?php
/**
 * @copyright      WebInteractiv
 * @author         Cotin Urse <costin.urse@gmail.com>
 * @desc           Utility Class
 * @package        core
 * since        April 16, 2010

 */
/**
 * Utility misc class
 * misc
 * @package        core
 */
namespace Core;

class Misc {

	private static $debug = array();

	/**
	 * dumps var
	 * @access    public
	 *
	 * @param    mixed
	 *
	 * @static
	 */
	public static function printr( $var, $return = false ) {
		if ( $return ) {
			ob_start();
		}
		echo "<strong><pre>";
		print_r( $var );
		echo "</pre></strong>";
		if ( $return ) {
			return ob_get_clean();
		}
	}

	/*
	 * dumps var
	 *
	 * @access	public
	 * @param	mixed
	 * @static
	 */
	public static function vardump( $var ) {
		echo "<strong><pre>";
		var_dump( $var );
		echo "</pre></strong>";
	}

	/**
	 * Display exception
	 *
	 * @param Exception $exception
	 */
	public static function DisplayException(QException $exception) {

		if ( CONFIG_ERRORS_DEBUG == false ) {
			exit ();
		}

		$view = new View();
		$view->assign('exception',$exception);
		$view->display('Errors/exception.php');
		Misc::DisplayDebugInfo();
		exit();
	}

	/**
	 * Static view class
	 * @access    public
	 *
	 * @param    string
	 * @param    mixed
	 *
	 * @static
	 */
	public static function View( $template = '', $assigns = array() ) {
		$view = new view ();
		if ( $assigns != null ) {
			$view->assign( $assigns );
		}

		return $view->fetch( $template );
	}

	public static $formatPageUrl = "";

	/**
	 * Function for getting link in a pager
	 *
	 * @param unknown_type $format
	 * @param unknown_type $page
	 *
	 * @return unknown
	 */
	public static function getPageUrl( $page, $format = "" ) {
		if ( $format == "" && self::$formatPageUrl != "" ) {
			$format = self::$formatPageUrl;
		}
		if ( $page == 1 ) {
			return str_replace( "/page-%page%", "", $format );
		}

		return str_replace( "%page%", $page, $format );
	}

	public static function IsInternalIp() {
		$knownIps = array( '85.204.197.200' );
		if ( in_array( $_SERVER ['REMOTE_ADDR'], $knownIps ) ) {
			return true;
		} else {
			return false;
		}
	}

	public static function DisplayDebugInfo() {
		if ( Router::$isAjaxCall || CONFIG_DISPLAY_DEBUG == false) {
			return;
		} else {

			$excludedFiles = array(
				'testetc'
			);

			foreach ($excludedFiles as $file) {
				if(strpos($_SERVER['REQUEST_URI'], $file) !== false) {
					return;
				}
			}

			if (count(self::$debug) > 0) {
				self::vardump( self::$debug );
			}
		}
	}

	public static function Debug($key, $text) {
		if (isset(self::$debug[$key])) {
			self::$debug[$key] = self::$debug[$key] + $text;
		} else {
			self::$debug[$key] = $text;
		}
	}
}