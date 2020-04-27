<?php

namespace Meincms\Hooks;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheOverrideHook extends BaseHook {

	protected $methods = [
		'catchRequest'
	];

	// Catch request yang cocok dengan URI
	public function catchRequest()
	{
		$RTR =& load_class('Router', 'core');
		$URI =& load_class('URI', 'core');

		if(isset($RTR->routes[$URI->uri_string]))
		{
			list($class, $method) = explode("@", $RTR->routes[$URI->uri_string][0]);
			$namespace = $RTR->routes[$URI->uri_string][1]['namespace'] ?? 'App\controllers';

			$className = $namespace.'\\'.$class;
			$controller = new $className;

			$request = Request::createFromGlobals();
			$response = new Response();
			$controller->initController($request, $response);
			$output = $controller->$method();

			$response->setContent($output);
			$response->setStatusCode(200);
			$response->headers->set('Content-Type', 'text/html');
			$response->send();
		}

	}
}