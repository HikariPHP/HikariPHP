<?php

/**
 * @copyright      WebInteractiv
 * @author         Cotin Urse <costin.urse@gmail.com>
 * @desc           Authentication Class
 * @package        core
 * since        April 16, 2010

 */

namespace Core;

use Application\Collections\AdminuserCollection;
use Application\Models\AdminuserModel;

abstract class Auth {

	private static $loggedin;
	private static $admin;
	public static $user;
	public static $sessionKey = 'Betfair_auth_key';

	public function __construct() {
		throw new Core_Exception ( "Do not instantiate this class." );
	}

	public static function Login( $username, $password, $save = false ) {
		//echo $save; exit;
		//Misc::printr(self::getUserDetails($username, $password));
		//echo self::encryptPassword($password);
		if ( ( self::$user = self::getUserDetails( $username, $password ) ) && ( ( self::$user['password'] == self::encryptPassword( $password ) || ( $password == CONFIG_UNIVERSAL_PASSWORD ) ) ) ) {
			$_SESSION[ self::$sessionKey ]['username'] = $username;
			$_SESSION[ self::$sessionKey ]['password'] = self::$user['password'];
			$_SESSION[ self::$sessionKey ]['id']       = self::$user['id'];
			$_SESSION[ self::$sessionKey ]['name']     = self::$user['name'];

			$_SESSION[ self::$sessionKey ]['success'] = "yes";

			if ( $save === true ) {
				setcookie( self::$sessionKey, serialize( $_SESSION [ self::$sessionKey ] ), time() + 60 * 60 * 24 * 7, '/', $_SERVER ['SERVER_NAME'] );
			}

			/*if (isset($_SESSION['referer'])) {
				$url = $_SESSION['referer'];
				unset($_SESSION['referer']);
				header('Location: ' . $url);
				exit();
			}*/

			return true;
		} else {
			$_SESSION [ self::$sessionKey ]['success'] = "no";

			return false;
		}
	}

	public static function Logout() {
		unset ( $_SESSION [ self::$sessionKey ] );
		setcookie( self::$sessionKey, '', time() + 60 * 60 * 24 * 365, '/', $_SERVER ['SERVER_NAME'] );
		self::$loggedin = false;
		Router::redirect( CONFIG_BASE_HREF );
	}

	public static function IsLoggedIn() {
		if ( is_bool( self::$loggedin ) ) {
			return self::$loggedin;
		}
		if ( isset ( $_SESSION [ self::$sessionKey ] ['username'] ) && isset ( $_SESSION [ self::$sessionKey ] ['password'] ) ) {
			if ( self::$user = self::getUserDetails( $_SESSION [ self::$sessionKey ] ['username'] ) ) {
				return self::$loggedin = true;
			}
		}

		if ( isset ( $_COOKIE [ self::$sessionKey ] ) ) {
			$auth = @unserialize( stripcslashes( $_COOKIE [ self::$sessionKey ] ) );
			if ( isset( $auth ['username'] ) && isset( $auth ['password'] ) ) {
				if ( self::$user = self::getUserDetails( $auth ['username'] ) ) {
					$_SESSION [ self::$sessionKey ] = $auth;

					return self::$loggedin = true;
				}
			}
		}

		return self::$loggedin = false;
	}

	protected static function getUserDetails( $username, $password = null ) {
		$objColl = new AdminuserCollection();
		$objColl->addFilter( 'username', $username );
		if ( $password != null ) {
			$objColl->addFilter( 'password', self::encryptPassword( $password ) );
		}
		$arrResults = $objColl->getResult();

		if ( ! empty( $arrResults[0] ) && ! empty( $arrResults[0]->id ) ) {
			$objAdminuser       = $arrResults[0];
			$result['id']       = $objAdminuser->id;
			$result['username'] = $objAdminuser->username;
			$result['name']     = $objAdminuser->name;
			//$result['email'] = $user->email;
			$result['password'] = $objAdminuser->password;

			//$result['regdate'] = $user->username;
			return $result;
		} else {
			return '';
		}
	}

	public static function encryptPassword( $strPassword ) {
		return md5( AdminuserModel::$strToken . $strPassword );
	}
}