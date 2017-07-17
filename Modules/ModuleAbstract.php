<?php

abstract class ModuleAbstract
{
  protected $db;
  protected $action;

  public abstract function execute();

  function __construct(Database $db)
  {
    $this->db = $db;
  }

  public function setAction($action)
  {
    $this->action = $action;
  }

  protected function getAction()
  {
    return $this->action;
  }

  protected function redirect($location) {
    header("Location: " . $location);
    exit();
  }



}