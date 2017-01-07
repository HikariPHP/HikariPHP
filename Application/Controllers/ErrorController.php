<?php

namespace Application\Controllers;

use Core\Controller;
use Core\View;

class ErrorController extends Controller{

	public function MainAction() {
		$view = new View();
		$view->display('Errors/404.php');
		exit;
	}

}