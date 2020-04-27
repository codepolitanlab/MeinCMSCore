<?php 

namespace Meincms\Libraries;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController {

	protected $request;

	protected $response;

	public $load;

	public function initController(Request $request, Response $response)
	{
		$this->request  = $request;
		$this->response = $response;
	}

}
