<?php

namespace Core;

use Core\Misc;

class Router {
	/**
	 * Array of controllers
	 * @var array
	 * @deprecated
	 */
	private $controllers;

	/**
	 * Array that contains information regarding host and request query string.
	 * @var array
	 * @static
	 */
	public static $location;

	/**
	 * Is ajax call
	 * @var bool
	 */
	public static $isAjaxCall;

	public static $arrApps = array( 'www', 'backend', 'cron' );

	/**
	 * Constructor. Makes analisys of URI request and calls a specified controller to handle that request.
	 *
	 * @param string $host
	 * @param string $uri
	 */
	public function __construct( $host = null, $uri = null ) {

		if ( $uri == null ) {
			$uri = $_SERVER ['REQUEST_URI'];
		}

		if ( $host == null ) {
			$host = $_SERVER ['HTTP_HOST'];
		}

		self::$location ['uri'] = explode( '/', $uri );
		// remove get params
		$strLastUri = &self::$location['uri'][ count( self::$location['uri'] ) - 1 ];
		if ( strpos( $strLastUri, '?' ) !== false ) {
			$strLastUri = substr( $strLastUri, 0, strpos( $strLastUri, '?' ) );
		}
		// Daca se termina in "/"
		if ( empty( self::$location ['uri'][ count( self::$location ['uri'] ) - 1 ] ) ) {
			unset( self::$location ['uri'][ count( self::$location ['uri'] ) - 1 ] );
		}

		self::$location ['host'] = explode( '.', $host );
		//daca nu are nimic in fata domeniului adaug www

		if ( self::$location['host'][0] == CONFIG_DOMAIN ) {
			array_unshift( self::$location['host'], 'www' );
		}

		if ( self::$location['uri'][0] == "" ) {
			array_shift( self::$location['uri'] );
		}

		if ( end( self::$location ['uri'] ) == '' ) {
			unset ( self::$location ['uri'] [ count( self::$location ['uri'] ) - 1 ] );
		}

		// Pentru cazul cand e pus in folder(DEV)
		for ( $i = 0; $i < CONFIG_URI_OFFSET; $i ++ ) {
			array_shift( self::$location['uri'] );
		}

		/**
		 * Ajax call, mark as such and remove the key from
		 * the location array
		 */
		if ( isset( self::$location['uri'][0] ) && self::$location['uri'][0] == 'ajax' ) {
			self::$isAjaxCall      = true;
			self::$location['uri'] = array_merge( array_slice( self::$location['uri'], 1 )
			);
		} /*elseif ( isset( self::$location['uri'][2] ) && self::$location['uri'][2] == 'ajax' ) {
			self::$isAjaxCall      = true;
			self::$location['uri'] = array_merge(
				array_slice( self::$location['uri'], 0, 1 )
				, array_slice( self::$location['uri'], 2 )
			);
		} */ else {
			self::$isAjaxCall = false;
		}

		//echo dirname(__DIR__);

		$controllerDirectory = dirname( __DIR__ ) . DIRECTORY_SEPARATOR . CONFIG_DIR_APPLICATION . DIRECTORY_SEPARATOR . CONFIG_DIR_CONTROLLERS;

		if ( ! isset( self::$location['uri'][0] ) || ! in_array( self::$location['uri'][0], self::$arrApps ) ) {
			array_unshift( self::$location['uri'], 'www' );
		}

		$arrLocation = self::$location['uri'];

		array_walk( $arrLocation, function ( &$value ) {
			$value = implode( '', array_map( 'ucfirst', explode( '-', $value ) ) );
		} );

		$strName = $controllerDirectory . DIRECTORY_SEPARATOR . implode( DIRECTORY_SEPARATOR, $arrLocation );

		$fileName        = $strName . 'Controller.php';
		$defaultFileName = $strName . DIRECTORY_SEPARATOR . 'IndexController.php';
		//echo '<br/>--' . $fileName . '--<br/>';
		//echo '<br/>--' . $defaultFileName . '--<br/>';

		if ( is_file( $fileName ) ) {
			$file = 'Application\Controllers\\' . implode( '\\', $arrLocation ) . 'Controller';
			new  $file;
		} elseif ( is_file( $defaultFileName ) ) {
			$file = 'Application\Controllers\\' . implode( '\\', $arrLocation ) . '\IndexController';
			new  $file;
		} else {
			new \Application\Controllers\ErrorController();
		}

		/*// Sitemap
		if (isset(self::$location['uri'][1]) && self::$location['uri'][1] == 'sitemap.xml') {
			new Www_Sitemap_Controller();
			exit;
		}*/

		// Language
		//		if (isset(self::$location['uri'][1]) && isset(Language::$arrLanguageNames[self::$location['uri'][1]])) {
		//			Language::$strCurrentLang = self::$location['uri'][1];
		//			self::$location['uri'] = array_merge(
		//				array(self::$location['uri'][0])
		//				,array_slice(self::$location['uri'], 2)
		//			);
		//		} else {
		//			// Force language at the begining of the uri
		//			Core_Router::redirect($uri);
		//			exit;
		//		}

	}

	public static function redirect( $url = '/' ) {
		header( "Location: " . $url );
		exit ();
	}

	public function __destruct() {
		Misc::DisplayDebugInfo();
	}
}