<?php

Class Template
{
  protected $template;
  private $vars = [];

  private function getTemplateDirectory()
  {
    return "Templates/";
  }

  public function __construct($templateName)
  {
    $this->template = $templateName;
  }

  public function render(Array $vars = [])
  {
    $this->vars = $vars;

    $this->includeTemplate($this->template);
  }

  private function includeTemplate($templateName)
  {
    foreach ($this->vars as $key => $var) {
      $$key = $var;
    }

    if (!file_exists( $this->getTemplateDirectory() . "{$templateName}.html")) {
      throw new \Exception("{$templateName} not found.");
    }
    include($this->getTemplateDirectory() . "{$templateName}.html");
  }

}