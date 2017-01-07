<?php
/**
 * Created by JetBrains PhpStorm.
 * User: costin.urse
 * Date: 9/9/12
 * Time: 4:21 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Core;

/*class ObjectException extends Exception {
	public function __construct($message, $code = 0, Exception $previous = null) {
		parent::__construct($message, $code, $previous);
	}

	public function __toString() {
		return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
	}

}*/

use Core\Collection;
use Core\Qpdo;

abstract class Object {

	public $intDetailsLevel = Collection::DETAILS_LOW;
	private $arrRequiredFields = array();
	public static $class = "";
	public $arrJoinedItems = array();

	public static function newObj($id = null, $intDetailsLevel = Collection::DETAILS_LOW, $blnUseCache = true) {

		/*if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
			$blnUseCache = false;
		}*/
		$caller = self::$class;

		if ($id) {
			//echo "|$caller from db";
			$obj = new $caller($id, $intDetailsLevel);
			return $obj;
		} else {
			return new $caller();
		}
	}

	public function __set($name, $value) {
		if ($name != 'id') {
			$this->$name = $value;
		}
	}

	public function __get($name) {
		return $this->$name;
	}

	abstract protected function __construct($intId = null, $intDetailsLevel = Collection::DETAILS_LOW);

	abstract protected function getObjectById($intId);

	abstract protected function setLowDetails($arrRow);

	abstract protected function setNormalDetails();

	abstract protected function setFullDetails();

	public static function getTableName() {
		return '';
	}

	public function save() {
		try {
			if (!empty($this->id)) {
				$blnReturn = $this->update();
			} else {
				$blnReturn = $this->create();
			}
		} catch (ObjectException $e) {
			$blnReturn = false;
		}

		return $blnReturn;
	}

	protected function getUpdatedFieldsBinds() {
		$arrFielsdNames = array();
		$arrFielsdBinds = array();
		foreach($this->arrFields as $strField) {
			if (isset($this->$strField)) {
				$arrFielsdNames[] = $strField;
				$arrFielsdBinds[] = ":" . $strField;
			}
		}

		return array($arrFielsdNames, $arrFielsdBinds);
	}

	protected function update() {
		list($arrFielsdNames, $arrFielsdBinds) = $this->getUpdatedFieldsBinds();
		if (empty($this->id)) {
			return false;
		} else if (!empty($arrFielsdNames) && !empty($arrFielsdBinds)) {
			$arrSet = array();
			foreach($arrFielsdNames as $key => $value) {
				$arrSet[] = $arrFielsdNames[$key] . " = " . $arrFielsdBinds[$key];
			}
			$strSet = implode(", ", $arrSet);

			$objDb = Qpdo::getInstance();
			$strQuery = "
				UPDATE {$this->strTableName}
				SET {$strSet}
				WHERE id = :id
			";

			$objStmt = $objDb->prepare($strQuery);
			$objStmt->bindParam(':id', $this->id);
			foreach($arrFielsdNames as $value) {
				$objStmt->bindParam(':' . $value, $this->$value);
			}
			$objStmt->execute();

			if (!$objStmt) {
				return false;
			}
		}

		return true;
	}

	protected function create() {
		list($arrFielsdNames, $arrFielsdBinds) = $this->getUpdatedFieldsBinds();
		if (!empty($arrFielsdNames) && !empty($arrFielsdBinds)) {
			foreach($this->arrRequiredFields as $field) {
				if (!in_array($field, $arrFielsdNames)) {
					throw new Exception("`$field`` field id required");
				}
			}

			$strFields = implode(", ", $arrFielsdNames);
			$strBinds = implode(", ", $arrFielsdBinds);

			$objDb = Qpdo::getInstance();
			$strQuery = "
				INSERT INTO {$this->strTableName} ({$strFields})
				VALUES ({$strBinds})
			";

			$objStmt = $objDb->prepare($strQuery);
			foreach($arrFielsdNames as $value) {
				$objStmt->bindParam(':' . $value, $this->$value);
			}

			$objStmt->execute();

			if ($objStmt) {
				$intId = $objDb->lastInsertId();
				if ($intId) {
					$this->id = $intId;
					return true;
				}
			}

		}

		return false;
	}

	public function delete() {
		if (empty($this->id)) {
			return false;
		} else  {

			$objDb = Qpdo::getInstance();
			$strQuery = "
				DELETE FROM {$this->strTableName}
				WHERE id = :id
			";

			$objStmt = $objDb->prepare($strQuery);
			$objStmt->bindParam(':id', $this->id);

			$objStmt->execute();

			if (!$objStmt) {
				return false;
			}
		}

		return true;
	}

}