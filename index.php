<?php

spl_autoload_register(function ($className) {

  $dirs = [
    '',
    'Modules/'
  ];

  foreach ($dirs as $dir) {
    $filename = $dir . $className . '.php';

    if (file_exists($filename)) {
      include $filename;
    }
  }
});

$app = new App();

echo $app->executeModule();