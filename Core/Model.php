<?php
/**
 * @copyright	WebInteractiv
 * @author		Cotin Urse <costin.urse@gmail.com>
 * @desc		Abstract Model Class
 * @package		core
 * since		April 16, 2010
 *
 */

namespace Core;

abstract class Model {
	
	protected $updated = array();
	//protected $errors = array();
	
	final public function __set( $name , $value ) {
		$method = "Set". ucfirst($name);
		if(method_exists($this,$method)) {
			//if($name != 'userid')
			$this->updated[] = $name;
			return $this->$method($value);
		} else throw new Qexception("MODEL :: Method ".get_class($this)."->$method() doesn't exist.");
	}
	
	final public function __get( $name ) {
		$method = "Get". ucfirst($name);
		if(method_exists($this,$method)) {
			return $this->$method();
		} else throw new Qexception("MODEL :: Method ".get_class($this)."->$method() doesn't exist.");
	}
		
	//abstract public function save();
	//abstract public static function delete($id);
}