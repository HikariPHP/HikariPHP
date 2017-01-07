<?php

namespace Application\Collections;

use Core\Collection;

class AdminuserCollection extends Collection {

	public $intPerPage 		= 20;
	public $intPage 		= 1;

	protected $strTable = 'adminuser';

	/**
	 * @var string Conditie cautare
	 */
	protected $strSearchCondition = 'LOWER(name) LIKE :search';

	/**
	 * @var string Ordinea
	 */
	protected $strOrder = self::ORDER_ID_ASC;

	/**
	 * Ordinile posibile din listare
	 */
	const ORDER_ID_ASC 			= 'id_asc';
	const ORDER_ID_DESC 		= 'id_desc';

	/**
	 * @var array Maparile ordinilor catre coloane
	 */
	protected $arrOrderMappings = array(
		self::ORDER_ID_ASC		=>array('id'=>'asc')
		,self::ORDER_ID_DESC	=>array('id'=>'desc')
	);

	public function __construct() {
		$arrClass =  str_replace('Collection', 'Model', str_replace('Collections', 'Models', __CLASS__));

		$this->strClassName = $arrClass;
		$this->intDetailsLevel = Collection::DETAILS_LOW;
	}

	/**
	 * @return Attribute[]
	 */
	public function getResult() {
		return parent::getResult();
	}



}