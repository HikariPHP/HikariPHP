<?php
ini_set('display_errors',1);
ini_set('error_reporting',E_ALL);

//Core Config
if ($_SERVER['SERVER_NAME'] == 'b.webinteractiv.ro'){
	define ( 'CONFIG_DISPLAY_DEBUG', true );
	define ( 'CONFIG_ERRORS_DEBUG', true );

	define ( 'CONFIG_DB_HOST', 'localhost' );
	define ( 'CONFIG_DB_NAME', 'betfair' );
	define ( 'CONFIG_DB_USER', 'betfair');
	define ( 'CONFIG_DB_PASS', '!@betfair34' );
	define ( 'CONFIG_DB_DEBUG', true );

	define ( 'CONFIG_DISPLAY_ERRORS', true );
	define ( 'CONFIG_DISPLAY_TEMPLATE_DEBUG', true );
	define ( 'CONFIG_UPLOAD_DIR', '/images' );
} elseif ($_SERVER['SERVER_NAME'] == '92.222.24.24'){
	define ( 'CONFIG_DISPLAY_DEBUG', true );
	define ( 'CONFIG_ERRORS_DEBUG', true );

	define ( 'CONFIG_DB_HOST', 'localhost' );
	define ( 'CONFIG_DB_NAME', 'betfair' );
	define ( 'CONFIG_DB_USER', 'betfair');
	define ( 'CONFIG_DB_PASS', '!@betfair34' );
	define ( 'CONFIG_DB_DEBUG', true );

	define ( 'CONFIG_DISPLAY_ERRORS', true );
	define ( 'CONFIG_DISPLAY_TEMPLATE_DEBUG', true );
	define ( 'CONFIG_UPLOAD_DIR', '/images' );
} elseif ($_SERVER['SERVER_NAME'] == '178.62.148.87'){
	define ( 'CONFIG_DISPLAY_DEBUG', true );
	define ( 'CONFIG_ERRORS_DEBUG', true );

	define ( 'CONFIG_DB_HOST', 'localhost' );
	define ( 'CONFIG_DB_NAME', 'betfair' );
	define ( 'CONFIG_DB_USER', 'betfair');
	define ( 'CONFIG_DB_PASS', '!@betfair34' );
	define ( 'CONFIG_DB_DEBUG', true );

	define ( 'CONFIG_DISPLAY_ERRORS', true );
	define ( 'CONFIG_DISPLAY_TEMPLATE_DEBUG', true );
	define ( 'CONFIG_UPLOAD_DIR', '/images' );
} else {
	define ( 'CONFIG_DISPLAY_DEBUG', true );
	define ( 'CONFIG_ERRORS_DEBUG', true );

	define ( 'CONFIG_DB_HOST', 'localhost' );
	define ( 'CONFIG_DB_NAME', 'betfair' );
	define ( 'CONFIG_DB_USER', 'root');
	define ( 'CONFIG_DB_PASS', '' );
	define ( 'CONFIG_DB_DEBUG', true );

	define ( 'CONFIG_DISPLAY_ERRORS', true );
	define ( 'CONFIG_DISPLAY_TEMPLATE_DEBUG', true );
	define ( 'CONFIG_UPLOAD_DIR', '/images' );
}

define ( 'CONFIG_DIR_SEPARATOR', '/' );

define ( 'SCRIPT_PATH', str_replace('/index.php','',$_SERVER['SCRIPT_NAME']) );
define ( 'CONFIG_DOCUMENT_ROOT', str_replace('/index.php', '', $_SERVER['SCRIPT_FILENAME']) );
define ( 'CONFIG_URI_OFFSET', count(explode('/',$_SERVER['SCRIPT_NAME'])) - 2);
define ( 'CONFIG_BASE_HREF', 'http://'.$_SERVER ['SERVER_NAME'] . SCRIPT_PATH . CONFIG_DIR_SEPARATOR );
define ( 'CONFIG_LINK_HREF', 'http://'.$_SERVER ['SERVER_NAME'] . SCRIPT_PATH );

define ( 'CONFIG_DOMAIN', $_SERVER ['SERVER_NAME']  );

define ( 'CONFIG_ADMIN', 'admin' );
define ( 'CONFIG_WWW', 'www' );
define ( 'CONFIG_ADMIN_BASE', 'http://'.$_SERVER ['SERVER_NAME'] . SCRIPT_PATH . CONFIG_DIR_SEPARATOR . CONFIG_ADMIN . CONFIG_DIR_SEPARATOR );

define ( 'CONFIG_UNIVERSAL_PASSWORD', '1qa@WS' );
define ( 'CONFIG_PASSWORD_SALT', '1qa@WS' );

define ('IMAGE_PATH', 'public/images/www/');

define ('CONFIG_MAIL_FROM', '');
define ('CONFIG_MAIL_FROM_NAME', '');

define ( 'CONFIG_DIR_CORE', 'Core' );
define ( 'CONFIG_DIR_APPLICATION', 'Application' );
define ( 'CONFIG_DIR_CONTROLLERS', 'Controllers' );
define ( 'CONFIG_DIR_UTILS', 'Utils' );
define ( 'CONFIG_DIR_HELPERS', 'Helpers' );
define ( 'CONFIG_DIR_COLLECTIONS', 'Collections' );
define ( 'CONFIG_DIR_VIEWS', 'Views' );
define ( 'CONFIG_DIR_MODELS', 'Models' );
define ( 'CONFIG_DIR_PUBLIC', 'public' );
define ( 'CONFIG_DIR_CACHE', 'cache' );
define ( 'CONFIG_DIR_IMAGES', 'images' );
define ( 'CONFIG_DIR_PHOTOS', 'photos' );
define ( 'CONFIG_DIR_LANG', 'public/lang' );
define ( 'CONFIG_ADMIN_CSS_PATH', CONFIG_BASE_HREF . 'css/admin/' );
define ( 'CONFIG_ADMIN_JS_PATH',  CONFIG_BASE_HREF . 'js/admin/' );
define ( 'CONFIG_CSS_PATH',  'css/' );
define ( 'CONFIG_JS_PATH',  'js/' );

define ( 'CONFIG_MINIFY_CSS', false );
define ( 'CONFIG_MINIFY_JS', false );
define ( 'CONFIG_MINIFY_HTML', false );

// dir paths
define ( 'CONFIG_PATH_IMGES', CONFIG_DOCUMENT_ROOT . CONFIG_DIR_SEPARATOR . CONFIG_DIR_PUBLIC . CONFIG_DIR_SEPARATOR . CONFIG_DIR_IMAGES . CONFIG_DIR_SEPARATOR);
define ( 'CONFIG_PATH_PHOTOS', CONFIG_DOCUMENT_ROOT . CONFIG_DIR_SEPARATOR . CONFIG_DIR_PUBLIC . CONFIG_DIR_SEPARATOR . CONFIG_DIR_PHOTOS . CONFIG_DIR_SEPARATOR);

// urls
define ( 'CONFIG_URL_PHOTOS', CONFIG_BASE_HREF . CONFIG_DIR_SEPARATOR . CONFIG_DIR_PUBLIC . CONFIG_DIR_SEPARATOR . CONFIG_DIR_PHOTOS . CONFIG_DIR_SEPARATOR);

define ( 'DEFAUT_LANGUAGE', 'ro' );

define ( 'DEFAUT_LANGUAGE_CONTROLLER', 'en' );

define ( 'FACEBOOK_APPID', '' );
define ( 'FACEBOOK_SECRET', '' );

define ('CONTACT_EMAIL', '');
define ('CONTACT_PHONE', '');
define ('CONTACT_FACEBOOK', '');
define ('CONTACT_TWITTER', '');


define ('CONFIG_BETFAIR_APP_KEY', 'toY5FaHItlYSLn0g');    // toY5FaHItlYSLn0g  // UJVLg2Voq3mCYnHI
define ('CONFIG_BETFAIR_USERNAME', 'alexebogdan');
define ('CONFIG_BETFAIR_PASSWORD', 'bogdan23');

define ('CONFIG_USE_VISITOR_TIMEZONE', true);