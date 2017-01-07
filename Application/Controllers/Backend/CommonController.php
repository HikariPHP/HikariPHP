<?php

namespace Application\Controllers\Backend;

use Core\Auth;
use Core\Controller;
use Core\Router;
use Core\View;

class CommonController extends Controller {

	public $strPage = '';
	public $arrCss = array( 'cucu' );
	public $arrJs = array();

	public function MainAction() {
		$parentVars   = get_class_vars( __CLASS__ );
		$this->arrCss = array_merge( $parentVars['arrCss'], $this->arrCss );
	}

	public function HasRight() {
		if ( $this->strPage != 'Login' && !Auth::IsLoggedIn() ) {
			Router::redirect( CONFIG_BASE_HREF . 'backend/login' );
			return false;
		}
		 return true;
	}

	public function RenderError() {
		$objView = new View();
		$objView->display('Errors/rights.php');
		exit;
	}
}