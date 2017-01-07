<?php
class Core_Pdo_Statement extends PDOStatement {
	const NO_MAX_LENGTH = - 1;
	protected $connection;
	protected $bound_params = array ();
	
	protected function __construct(PDO $connection) {
		$this->connection = $connection;
	}
	
	public function bindParam($paramno, &$param, $type = PDO::PARAM_STR, $maxlen = null, $driverdata = null) {
		$this->bound_params [$paramno] = array (
				'value' => &$param,
				'type' => $type,
				'maxlen' => (is_null ( $maxlen )) ? self::NO_MAX_LENGTH : $maxlen);
		
		$result = parent::bindParam ( $paramno, $param, $type, $maxlen, $driverdata );
	}
	
	public function bindValue($parameter, $value, $data_type = PDO::PARAM_STR) {
		$this->bound_params [$parameter] = array (
				'value' => $value,
				'type' => $data_type,
				'maxlen' => self::NO_MAX_LENGTH 
		);
		parent::bindValue ( $parameter, $value, $data_type );
	}
	
	public function debugQuery($values = array()) {
		$sql = $this->queryString;
		
		if (sizeof ( $values ) > 0) {
			foreach ( $values as $key => $value ) {
				$sql = str_replace ( $key, $this->connection->quote ( $value ), $sql );
			}
		}
		
		if (sizeof ( $this->bound_params )) {
			foreach ( $this->bound_params as $key => $param ) {
				$value = $param ['value'];
				if (! is_null ( $param ['type'] )) {
					$value = self::cast ( $value, $param ['type'] );
				}
				if ($param ['maxlen'] && $param ['maxlen'] != self::NO_MAX_LENGTH) {
					$value = self::truncate ( $value, $param ['maxlen'] );
				}
				if (! is_null ( $value )) {
					$sql = str_replace ( $key, $this->connection->quote ( $value ), $sql );
				} else {
					$sql = str_replace ( $key, NULL, $sql );
				}
			}
		}
		echo $sql;
		//return $sql;
	}
	
	static protected function cast($value, $type) {
		switch ($type) {
			case PDO::PARAM_BOOL :
				return ( bool ) $value;
				break;
			case PDO::PARAM_NULL :
				return null;
				break;
			case PDO::PARAM_INT :
				return ( int ) $value;
			case PDO::PARAM_STR :
			default :
				return $value;
		}
	}
	static protected function truncate($value, $length) {
		return substr ( $value, 0, $length );
	}
}