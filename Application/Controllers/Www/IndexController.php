<?php

namespace Application\Controllers\Www;

use Core\Controller;
use Core\Router;

class IndexController extends Controller{

	public function MainAction() {
		Router::redirect('/backend/event/list');
	}

}