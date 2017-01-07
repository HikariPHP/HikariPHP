<?php

namespace Application\Models;

use Core\Object;
use Core\Collection;
use Core\Qexception;
use Core\Qpdo;

/*class AdminuserException extends Qexception {
	public function __construct($message, $code = 0, Exception $previous = null) {
		parent::__construct($message, $code, $previous);
	}

	public function __toString() {
		return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
	}

}*/


class AdminuserModel extends Object {

	public static $strToken = "betfair";
	public static $objAdminuser;
	public static $blnLoggedin = false;

	public static $strTableName = 'adminuser';

	// Table fields
	public $id;
	public $username;
	public $password;
	public $regdate;
	public $name;

	public $arrFields = array(
		'id',
		'username',
		'password',
		'name',
	);

	/**
	 * @static
	 * @param $id
	 * @param int $intDetailsLevel
	 * @param bool $blnUseCache
	 * @return Carousel
	 */
	public static function newObj($id = null, $intDetailsLevel = Collection::DETAILS_LOW, $blnUseCache = true) {
		self::$class = get_class();
		return parent::newObj($id, $intDetailsLevel, $blnUseCache);
	}

	public static function getClass() {
		return __CLASS__;
	}

	protected function __construct($intId = null, $intDetailsLevel = Collection::DETAILS_LOW) {
		$this->strTableName = self::$strTableName;
		if ($intId) {
			$this->intDetailsLevel = $intDetailsLevel;
			$arrObject = $this->getObjectById($intId);

			switch ($this->intDetailsLevel) {
				case Collection::DETAILS_LOW:
					$this->setLowDetails($arrObject);
					break;
				case Collection::DETAILS_NORMAL:
					$this->setLowDetails($arrObject);
					$this->setNormalDetails();
					break;
				case Collection::DETAILS_FULL:
					$this->setLowDetails($arrObject);
					$this->setNormalDetails();
					$this->setFullDetails();
					break;
			}
		}

	}


	protected function getObjectById($intId) {
		$objDb = Qpdo::getInstance();
		$strQuery = "
			SELECT *
			FROM " . self::$strTableName . "
			WHERE id = :id
		";

		$objStmt = $objDb->prepare($strQuery);
		$objStmt->bindParam(':id', $intId);
		$objStmt->execute();

		$arrReturn = array();
		if ($objStmt) {
			if ($row = $objStmt->fetch(\PDO::FETCH_ASSOC)) {
				$arrReturn = $row;
			}
		}

		return $arrReturn;
	}

	protected function setLowDetails($arrRow) {
		foreach($arrRow as $strKey => $value) {
			if (property_exists(__CLASS__, $strKey)) {
				$this->$strKey = $value;
			}
		}
	}

	protected function setNormalDetails() {

	}

	protected function setFullDetails() {

	}

	public static function getTableName() {
		return self::$strTableName;
	}

}