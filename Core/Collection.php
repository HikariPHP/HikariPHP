<?php
/**
 * Created by JetBrains PhpStorm.
 * User: costin.urse
 * Date: 9/9/12
 * Time: 5:01 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Core;

abstract class Collection {
	protected 	$intPage;
	protected	$intPerPage;
	protected 	$intDetailsLevel;
	protected 	$arrItems = array();
	protected 	$blnUseIdAsKey = false;

	protected 	$arrBinds;
	protected 	$strQuery;
	protected 	$strClassName;

	const DETAILS_CUSTOM				= 0;
	const DETAILS_LOW					= 100;
	const DETAILS_NORMAL				= 200;
	const DETAILS_FULL					= 300;

	/**
	 * Tipuri de query pentru query builder
	 */
	const QUERY_TYPE_SELECT = 1;
	const QUERY_TYPE_COUNT 	= 2;

	protected $intQueryType = self::QUERY_TYPE_SELECT;

	/**
	 * @var array Filtre
	 */
	protected $arrFilters = array();

	/**
	 * @var string Keyword cautare
	 */
	protected $strSearch;

	/**
	 * @var string Conditie cautare
	 */
	protected $strSearchCondition;

	/**
	 * @var array WhereQuery
	 */
	protected $arrWhere;

	protected $arrOrder = array();

	protected $arrJoin = array();

	protected function executeQuery() {
		$DB = Qpdo::getInstance();

		if (empty($this->strQuery)) {
			$this->getQuery($this->intQueryType);
		}

		if (!empty($this->intPerPage) && $this->intQueryType == self::QUERY_TYPE_SELECT) {
			if (empty($this->intPage)) {
				$this->intPage = 1;
			}
			$this->strQuery .= " LIMIT " . (($this->intPage - 1) * $this->intPerPage) . ", " . $this->intPerPage;
		}


		$this->objStmt = $DB->prepare($this->strQuery);
		if (!empty($this->arrBinds)) {
			foreach($this->arrBinds as $key => $value) {
				$this->objStmt->bindValue(':' . $key, $value);
			}
		}

		//print_r($this->objStmt);

		$this->objStmt->execute();
	}

	protected function process() {
		if ($this->intQueryType == self::QUERY_TYPE_COUNT) {
			while ($row = $this->objStmt->fetch(\PDO::FETCH_ASSOC)) {
				$this->arrItems = $row['CNT'];
			}
		} else {
			while ($row = $this->objStmt->fetch(\PDO::FETCH_ASSOC)) {
				$this->newItem($row);
			}
		}
	}

	protected function newItem($row) {
		if ($this->intDetailsLevel == self::DETAILS_CUSTOM) {
			$this->arrItems[] = $row;
		} else {
			if (isset($row['id'])) {
				$intId = $row['id'];
			} else if (isset($row['ID'])) {
				$intId = $row['ID'];
			} else {
				throw new Exception('Undefined Object ID');
			}

			$item = call_user_func("{$this->strClassName}::newObj", $intId, $this->intDetailsLevel, false);

			// Adaugam items din join ca obiecte pe itemul principal
			foreach($row as $strFieldAlias => $strValue) {
				if (strtolower($strFieldAlias) != 'id') {
					$arrFieldAlias = explode("_", $strFieldAlias);
					$joinedClass = $arrFieldAlias[0];
					$arrValues = explode(' ', $strValue);
					foreach($arrValues as $intValue) {
						$joinedItem = call_user_func("{$joinedClass}::newObj", $intValue, $this->intDetailsLevel, false);
						if (!empty($this->arrJoin[$joinedClass]['use_id_as_key'])) {
							$item->arrJoinedItems[$joinedClass][$intValue] = $joinedItem;
						} else {
							$item->arrJoinedItems[$joinedClass][] = $joinedItem;
						}
					}
				}
			}

			if ($this->blnUseIdAsKey) {
				$this->arrItems[$intId] = $item;
			} else {
				$this->arrItems[] = $item;
			}
		}
	}

	protected function prepareItems() {
		$this->executeQuery();
		$this->process();
	}
	
	public function debugQuery(){
		return $this->objStmt->debugQuery();
	}

	public function getResult() {
		$this->prepareItems();
		return $this->arrItems;
	}

	public function useIdAsKey($blnUseIdAsKey = true) {
		$this->blnUseIdAsKey = $blnUseIdAsKey;
	}

	/**
	 * Adauga o conditie
	 *
	 * @param $strCondition
	 * @param array $arrBinds
	 */
	public function addCondition($strCondition, $arrBinds = array()) {
		$this->arrWhere[] = $strCondition;
		if (!empty($arrBinds)) {
			foreach($arrBinds as $bindname => $bindvalue) {
				$this->arrBinds[$bindname] = $bindvalue;
			}
		}
	}


	public function setQueryType($intQueryType) {
		$this->intQueryType = $intQueryType;
	}

	public function getQueryType() {
		return $this->intQueryType;
	}

	/**
	 * Adauga filtru
	 * @param $strFieldName
	 * @param $value
	 * @param $strClass numele clase la care se pune filtrul (folositoare la join-uri)
	 */
	public function addFilter($strFieldName, $value, $strClass = false) {
		$this->arrFilters[$strFieldName] = array(
			'value' => $value,
			'class' => !empty($strClass) ? $strClass : '',
			'table' => !empty($strClass) ? call_user_func(array($strClass, "getTableName")) : '',
		);
	}

	/**
	 * Seteaza parametrul de cautare
	 * @param $strValue
	 */
	public function setSearch($strValue){
		$this->strSearch = $strValue;
	}

	/**
	 * Seteaza pagina
	 * @param $intValue
	 */
	public function setPage($intValue){
		$this->intPage = $intValue;
	}

	/**
	 * Pagina curenta
	 * @return int
	 */
	public function getPage(){
		return $this->intPage;
	}

	/**
	 * Seteaza numarul de elemente pe pagina
	 * @param $intValue
	 */
	public function setPerPage($intValue){
		$this->intPerPage = $intValue;
	}

	/**
	 * Numarul de elemente pe pagina
	 * @return int
	 */
	public function getPerPage(){
		return $this->intPerPage;
	}

	/**
	 * Seteaza ordinea
	 * @param $strValue constantele ORDER_
	 */
	public function setOrder($strValue, $strClass = ''){
		$this->arrOrder[] = array(
			'name' => $strValue,
			'class' => $strClass,
			'table' => !empty($strClass) ? call_user_func(array($strClass, "getTableName")) : '',
		);
	}

	/**
	 * Ordinea
	 * @return string
	 */
	public function getOrder(){
		return $this->arrOrder;
	}

	public function addJoin($strNewClass, $strOldClass, $strNewClassField, $strOldClassField, $blnGetObjectInResultSet = false, $blnUseIdAsKey = false) {
		$this->join('INNER', $strNewClass, $strOldClass, $strNewClassField, $strOldClassField, $blnGetObjectInResultSet, $blnUseIdAsKey);
	}

	public function addLeftJoin($strNewClass, $strOldClass, $strNewClassField, $strOldClassField, $blnGetObjectInResultSet = false, $blnUseIdAsKey = false) {
		$this->join('LEFT', $strNewClass, $strOldClass, $strNewClassField, $strOldClassField, $blnGetObjectInResultSet, $blnUseIdAsKey);
	}

	public function addRightJoin($strNewClass, $strOldClass, $strNewClassField, $strOldClassField, $blnGetObjectInResultSet = false, $blnUseIdAsKey = false) {
		$this->join('RIGHT', $strNewClass, $strOldClass, $strNewClassField, $strOldClassField, $blnGetObjectInResultSet, $blnUseIdAsKey);
	}

	public function join($strType, $strNewClass, $strOldClass, $strNewClassField, $strOldClassField, $blnGetObjectInResultSet = false, $blnUseIdAsKey = false) {
		$this->arrJoin[$strNewClass] = array(
			'query' => "$strType JOIN " . call_user_func(array($strNewClass, "getTableName")) . " ON " . call_user_func(array($strNewClass, "getTableName")) . ".$strNewClassField = " . call_user_func(array($strOldClass, "getTableName")) . ".$strOldClassField",
			'class' => $strNewClass,
			'table' => call_user_func(array($strNewClass, "getTableName")),
			'get_object' => $blnGetObjectInResultSet,
			'use_id_as_key' => $blnUseIdAsKey,
		);
	}

	/**
	 * Populeaza $this->strQuery si $this->arrBinds
	 * @param integer $intQueryType constantele QUERY_TYPE_
	 */
	protected function getQuery($intQueryType){

		$strWhere = "";
		$strJoin = "";
		$strJoinFields = "";
		$strJoinFieldsConcat = "";
		$strOrderBy = "";

		// WHERE ...
		if (!empty($this->arrFilters)) {
			foreach($this->arrFilters as $strField => $arrValue) {
				$strTable = "";
				if (!empty($arrValue['table'])) {
					$strTable = $arrValue['table'] . ".";
				}

				$mixValues = $arrValue['value'];
				$arrBinds = array();
				if (is_array($mixValues)) {
					$arrKeys = array();
					foreach($mixValues as $intkey => $strValue) {
						$strKey = 'filter_' . $strField . '_' . $intkey;
						$arrKeys[] = ":" . $strKey;
						$arrBinds[$strKey] = $strValue;
					}
					$this->addCondition($strTable . $strField . " IN (" . implode(', ', $arrKeys) . ")", $arrBinds);
				} else {
					$this->addCondition($strTable . $strField . " = :filter_" . $strField, array('filter_' . $strField => $mixValues));
				}
			}
		}

		if(strlen($this->strSearch) > 0 && !empty($this->strSearchCondition)) {
			$this->addCondition($this->strSearchCondition, array('search' => '%' . $this->strSearch . '%'));
		}

		if (!empty($this->arrWhere)) {
			$strWhere = "WHERE " . implode(" AND ", $this->arrWhere);
		}

		if (!empty($this->arrJoin)) {
			$arrJoinQuery = array();

			foreach($this->arrJoin as $arrJoin) {
				$arrJoinQuery[] = $arrJoin['query'];
				if (!empty($arrJoin['get_object'])) {
					$strJoinFields .= ", " . $arrJoin['table'] . ".id `" . $arrJoin['class'] . "_id`";
					$strJoinFieldsConcat .= ", GROUP_CONCAT(`" . $arrJoin['class'] . "_id` SEPARATOR ' ') `" . $arrJoin['class'] . "_id`";
				}
			}

			$strJoin = implode(" ", $arrJoinQuery);
		}

		if($intQueryType == self::QUERY_TYPE_SELECT){
			$arrOrderBy = array();
			foreach($this->arrOrder as $arrOrder) {
				if (!empty($arrOrder['class'])) {
					$strClass = str_replace('Model', 'Collection',$arrOrder['class']  );
					$objCol = new $strClass();
					$arrOrderMappings = $objCol->arrOrderMappings;
				} else {
					$arrOrderMappings = $this->arrOrderMappings;
				}

				if (!empty($arrOrderMappings[$arrOrder['name']])) {
					foreach($arrOrderMappings[$arrOrder['name']] as $field => $value) {
						$arrOrderBy[] = (!empty($arrOrder['table']) ? $arrOrder['table'] . "." : "") . $field . " " . $value;
					}
				}
			}



			if (!empty($arrOrderBy)) {
				$strOrderBy = "ORDER BY " . implode(", ", $arrOrderBy);
			}

			$strBaseQuery = "
				SELECT id" . $strJoinFieldsConcat . "
				FROM (
					SELECT " . $this->strTable . ".id, @curRow := @curRow + 1 AS row_number" . $strJoinFields . "
					FROM " . $this->strTable . "
					" . $strJoin . "
					JOIN    (SELECT @curRow := 0) r
					" . $strWhere . "
					" . $strOrderBy . "
				) x
				GROUP BY id
				ORDER BY row_number ASC
			";
			$this->strQuery = $strBaseQuery;
		}

		if($intQueryType == self::QUERY_TYPE_COUNT){
			$strBaseQuery = "
				SELECT COUNT(DISTINCT " . $this->strTable . ".id ) AS CNT
				FROM " . $this->strTable . "
				" . $strJoin . "
				" . $strWhere . "
			";
			$this->strQuery = $strBaseQuery;
		}

	}
}