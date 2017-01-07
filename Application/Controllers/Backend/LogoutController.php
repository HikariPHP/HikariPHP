<?php

namespace Application\Controllers\Backend;

use Application\Collections\AdminuserCollection;
use Application\Controllers\Backend\CommonController;
use Application\Models\AdminuserModel;
use Core\Auth;
use Core\Router;
use Core\View;

class LogoutController extends CommonController {

	public $strPage = 'Logout';

	public function MainAction() {

		if ( Auth::IsLoggedIn()) {
			Auth::Logout();
		}

		Router::redirect( CONFIG_BASE_HREF . 'backend/login' );
		exit;
	}
}