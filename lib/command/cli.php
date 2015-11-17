<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once(dirname(__FILE__).'/../autoload/sfCoreAutoload.class.php');
sfCoreAutoload::register();

function cli_exception_handler(Exception $e)
{
  global $application;

  if (!isset($application))
  {
    throw $e;
  }

  $application->renderException($e);
  $statusCode = $e->getCode();

  exit(is_numeric($statusCode) && $statusCode ? $statusCode : 1);
}

set_exception_handler('cli_exception_handler');

$dispatcher = new sfEventDispatcher();
$logger = new sfCommandLogger($dispatcher);

$application = new sfSymfonyCommandApplication($dispatcher, null, array('symfony_lib_dir' => realpath(__DIR__.'/..')));
$statusCode = $application->run();

exit(is_numeric($statusCode) ? $statusCode : 0);
