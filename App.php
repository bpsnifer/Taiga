<?php

Class App
{

  private $db;
  private $router;
  private $module;

  /**
   * App constructor.
   */
  public function __construct()
  {
    $this->db = new Database('root', 'codemaster', 'taiga');
    $this->router = new Router();

    $params = [
      $this->db
    ];

    $this->router->add('clients', 'Clients', $params);
    $this->router->add('phones', 'Phones', $params);
    $this->router->add('default', 'Home', $params);

    $this->module = $this->router->getModule();
  }

  public function executeModule()
  {
    return $this->module->execute();
  }



}
