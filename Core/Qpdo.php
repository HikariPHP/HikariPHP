<?php
/**
 * @copyright      WebInteractiv
 * @author         Cotin Urse <costin.urse@gmail.com>
 * @desc           Sql Class
 * @package        core
 * since        April 16, 2010

 */

namespace Core;

use PDO;
use PDOException;

class Qpdo {

	private $type = 'mysql';
	private $host = CONFIG_DB_HOST;
	private $name = CONFIG_DB_NAME;
	private $user = CONFIG_DB_USER;
	private $pass = CONFIG_DB_PASS;
	private $timeout = 30;

	private $debug = CONFIG_DB_DEBUG;

	public $fetch_mode = PDO::FETCH_ASSOC;

	protected static $instance;

	private function __construct() {
		$dsn      = $this->type . ':host=' . $this->host .
		            ';dbname=' . $this->name .
		            ';connect_timeout=' . $this->timeout;
		$user     = $this->user;
		$password = $this->pass;
		try {
			self::$instance = new PDO( $dsn, $user, $password );
			self::$instance->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		} catch ( PDOException $e ) {
			if ( $this->debug ) {
				echo "Connection Error: " . $e->getMessage();
			}
		}
	}

	public static function getInstance() {
		if ( ! self::$instance ) {
			new Qpdo;
		}

		return self::$instance;
	}
}