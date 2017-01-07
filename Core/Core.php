<?php
/**
 * @copyright      WebInteractiv
 * @author         Cotin Urse <costin.urse@gmail.com>
 * @desc           Core Class
 * @package        core
 * since        April 16, 2010

 */

namespace Core;

final class Core {

	private static $initialized = false;
	public static $config = array();
	public static $lang = array();
	public static $lang_name;

	public static $Debug = null;

	/**
	 * @var DebugUtil
	 */

	public function __construct() {
		throw new Core_Exception ( "Core: this class doesn't support instances." );
	}

	public static function Initialize( $mode = 'std' ) {
		try {
			if ( self::$initialized == true ) {
				throw new Core_Exception ( "Core::Initialize() : Already initialized." );
			}
			self::startSession( $mode );
			self::loadConfig( $mode );
			self::loadSettings();
			//self::setAutoloadHandler ();
			//self::LoadAdditionalSettings ();
			//self::LoadLanguage();
			self::$initialized = true;
		} catch ( Core_Exception $exception ) {
			if ( defined( 'CONFIG_ERRORS_DEBUG' ) ) {
				Core_Misc::displayException( $exception );
			}
			exit ();
		}
	}

	/**
	 * Load config
	 */
	public static function loadConfig( $mode ) {
		if ( $mode == 'cli' ) {
			require_once( dirname(dirname(__FILE__)).'/Config/Cronconfig.php' );
			return;
		}

		require_once( dirname(dirname(__FILE__)).'/Config/Config.php' );

	}

	/**
	 * Misc settings
	 */
	public static function loadSettings() {
		ini_set( 'error_reporting', E_ALL | E_STRICT );
		if ( CONFIG_DISPLAY_ERRORS == true ) {
			ini_set( 'display_errors', true );
		} else {
			ini_set( 'display_errors', true );
		}
		date_default_timezone_set( 'Europe/Bucharest' );
	}

	/*public static function setAutoloadHandler() {
		function __autoload($classname) {
			Core::loadClass ( $classname );
		}
	}*/

	/**
	 * starts a session if not started
	 */
	public static function StartSession( $mode ) {
		if ( $mode == 'cli' ) {
			return;
		}
		$sessionId = session_id();
		if ( empty( $sessionId ) ) {
			session_start();
		}
	}

	public static function LoadAdditionalSettings() {
		$DB    = new Core_Sql();
		$query = "SELECT * from config";
		$stmt  = $DB->prepare( $query );
		try {
			$stmt->execute();
		} catch ( PDOException $e ) {
			echo $e->getMessage();
		}

		while ( $row = $stmt->fetch() ) {
			self::$config[ $row['setting'] ] = $row['value'];
		}
	}

	public static function LoadLanguage() {
		if ( isset( $_GET['cl'] ) && isset( Language::$arrLanguageNames[ $_GET['cl'] ] ) ) {
			Language::changeLanguage( $_GET['cl'] );
		}

		if ( ! empty( $_COOKIE['lang'] ) && isset( Language::$arrLanguageNames[ $_COOKIE['lang'] ] )
		     && ! empty( $_COOKIE['continent'] )
		     && ! empty( $_COOKIE['country'] )
		) {

			Language::$strCurrentLang      = $_COOKIE['lang'];
			Location::$intCurrentContinent = $_COOKIE['continent'];
			Location::$intCurrentCountry   = $_COOKIE['country'];
		} else {
			$objLocation = Location::getVisitorCountry();

			$strLanguage = Language::LANG_ENGLISH;

			$strContinent = $objLocation->geoplugin_continentCode;
			if ( isset( Location::$arrContinents[ $strContinent ] ) ) {
				setcookie( 'continent', $strContinent, time() + 3600 * 3, '/' );
				Location::$intCurrentContinent = Location::$arrContinents[ $strContinent ];

				if ( isset( Location::$arrContinentsLanguage[ Location::$arrContinents[ $strContinent ] ] ) ) {
					$strLanguage = Location::$arrContinentsLanguage[ Location::$arrContinents[ $strContinent ] ];
				}
			}

			$strCountry = $objLocation->geoplugin_countryName;
			if ( isset( Location::$arrCountries[ $strCountry ] ) ) {
				setcookie( 'country', $strCountry, time() + 3600 * 3, '/' );
				Location::$intCurrentCountry = Location::$arrCountries[ $strCountry ];

				if ( isset( Location::$arrCountriesLanguage[ Location::$arrCountries[ $strCountry ] ] ) ) {
					$strLanguage = Location::$arrCountriesLanguage[ Location::$arrCountries[ $strCountry ] ];
				}
			}

			// First time language set
			if ( empty( $_COOKIE['lang'] ) ) {
				$_SESSION['first_time'] = 1;
			}

			setcookie( 'lang', $strLanguage, time() + 3600 * 3, '/' );
			Language::$strCurrentLang = $strLanguage;
		}
	}
}