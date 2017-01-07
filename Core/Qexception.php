<?php

/**
 * @copyright      WebInteractiv
 * @author         Cotin Urse <costin.urse@gmail.com>
 * @desc           Exception Class
 * @package        core
 * since        April 16, 2010

 */

/**
 * class        commonException
 * @package        core
 */

namespace Core;

use \Exception;
use Core\Misc;

class Qexception extends Exception {
	public function __construct( $message = null, $code = 0 ) {
		parent::__construct( $message, $code );

		//Core::$Debug->exceptionHandler( $this, true );

		throw $this;
	}

	public function __toString() {
		return Misc::DisplayException( $this );
	}
}