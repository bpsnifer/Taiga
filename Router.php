<?php

Class Router
{
  private $routes = [];

  public function __construct()
  {
  }

  /**
   * @param $route
   * @param $class
   * @param array $params
   */
  public function add($route, $class, Array $params = [])
  {
    $this->routes[$route] = [
      'class' => $class,
      'params' => $params
    ];
  }

  public function getModule()
  {
    $r = $_GET['m'];

    $route = null;
    $module = null;

    if (!isset($this->routes[$r])) {
      if (!isset($this->routes['default'])) {
        throw new \Exception("Unknown route {$r}");
      }
      $route = $this->routes['default'];
    } else {
      $route = $this->routes[$r];
    }

    $module = new $route['class'](...$route['params']);

    $action = isset($_GET['a'])? intval($_GET['a']) : Actions::LIST;
    switch ($action) {
      case Actions::CREATE:
      case Actions::UPDATE:
      case Actions::DELETE:
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
          throw new \Exception("Method not allowed");
        }
        break;
    }

    $module->setAction($action);

    return $module;
  }


}
