<?php

namespace Application\Core;

class Router
{
	/**
	 * @var $routes
	 * @type array
	 */
	protected $routes = [];

	/**
	 * @var $params
	 * @type array
	 */
	protected $params = [];

	public function __construct()
	{

	}


	public function add(string $route,array $params): void
	{
		$route = '#^'.$route.'$#';
		$this->routes[$route] = $params;
	}

	public function match(): bool
	{
		$uri = trim($_SERVER['REQUEST_URI'],'/');
		foreach ($this->routes as $route => $params) {
			if (preg_match($route,$uri,$matches)) {
				$this->params = $params;
				return true;
			}
		}
		return false;
	}

	public function run(): void
	{
		if ($this->match()) {
			$controller = 'Application\Controllers\\'.ucfirst($this->params['controller']);
			if (class_exists($controller)) {
				$action = $this->params['action'];
				if (method_exists($controller,$action)) {
					$object = new $controller;
					$object->$action();
				} else {
					die('Not found method: '. $action);
				}
			} else {
				die('Not found class: '. $controller);
			}

		}
	}

	public function get(string $uri,string $action): void
	{
		$params = explode('@',$action);
		$paramsRoute = [
			'controller' => $params[0],
			'action' => $params[1]
		];
		$this->add($uri,$paramsRoute);
	}

}