<?php
/**
 * @copyright	WebInteractiv
 * @author		Cotin Urse <costin.urse@gmail.com>
 * @desc		Abstract Controller Class
 * @package		core
 * since		April 16, 2010
 *
 */

namespace Core;

use Application\Controllers\ErrorController;
use Core\Router;
use Core\Misc;

abstract class Controller {

	public function __construct() {

		if(method_exists($this, 'HasRight') && !$this->HasRight()) {
			if(method_exists($this, 'RenderError')) {
				$this->RenderError();
			} else {
				new ErrorController();
			}
		}

		if(Router::$isAjaxCall && method_exists($this, 'AjaxAction')) {
			if(func_num_args()) {
				call_user_func_array(array($this,'AjaxAction'), func_get_args());
			} else {
				$this->AjaxAction();
			}
			return true;
		} elseif(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST' && method_exists($this, 'PostAction')) {
			if(func_num_args()) {
				call_user_func_array(array($this,'PostAction'), func_get_args());
			} else {
				$this->PostAction();
			}
			return true;
		} else {
			if(!method_exists($this, 'MainAction')) {
				throw new Core_Exception('Method MainAction does not exist.');
			}

			if(func_num_args()) {
				call_user_func_array(array($this,'MainAction'), func_get_args());
			} else {
				$this->MainAction();
			}
		}
	}
}