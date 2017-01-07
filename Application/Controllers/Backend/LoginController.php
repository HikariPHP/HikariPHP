<?php

namespace Application\Controllers\Backend;

use Application\Collections\AdminuserCollection;
use Application\Controllers\Backend\CommonController;
use Application\Models\AdminuserModel;
use Core\Auth;
use Core\Router;
use Core\View;

class LoginController extends CommonController {

	public $strPage = 'Login';

	public $blnSuccess;
	public $arrData;
	public $arrErrors;

	public function MainAction() {

		if ( Auth::IsLoggedIn() ) {
			Router::redirect( CONFIG_BASE_HREF . 'backend/event/list' );
			exit;
		}

		if ( ! empty( $_POST ) ) {
			$this->arrData = $_POST;
			if ( $this->validateForm() ) {
				$_SESSION['admin_auth']['id']       = $this->objAdminuser->id;
				$_SESSION['admin_auth']['username'] = $this->objAdminuser->username;
				$_SESSION['admin_auth']['name']     = $this->objAdminuser->name;

				AdminuserModel::$blnLoggedin  = true;
				AdminuserModel::$objAdminuser = $this->objAdminuser;

				Router::redirect( CONFIG_BASE_HREF . 'backend/event/list' );
				exit;
			}
		}
		//var_dump($_POST);
		$view = new View();
		$view->assign( 'blnSuccess', $this->blnSuccess );
		$view->assign( 'arrErrors', $this->arrErrors );
		$view->display( 'Backend/Login.php' );
	}

	public function validateForm() {
		if ( empty( $this->arrData['uname'] ) ) {
			$this->arrErrors['login'] = 'Completeaza numele utilizatorului';
		} else if ( empty( $this->arrData['upass'] ) ) {
			$this->arrErrors['login'] = 'Completeaza parola';
		} else {
			if (isset($this->arrData['save'])) {
				$save = true;
			} else {
				$save = false;
			}

			if (! Auth::Login($this->arrData['uname'], $this->arrData['upass'], $save )) {
				$this->arrErrors['login'] = 'Numele de utilizator si parola nu se potrivesc.';
			}
		}

		if ( empty( $this->arrErrors ) ) {
			return true;
		}

		return false;
	}

	public function HasRight() {
		return true;
	}
}