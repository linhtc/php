<?php

use Symfony\Component\ClassLoader\ApcClassLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

//// Use APC for autoloading to improve performance.
//// Change 'sf2' to a unique prefix in order to prevent cache key conflicts
//// with other applications also using APC.
///*
//$apcLoader = new ApcClassLoader('sf2', $loader);
//$loader->unregister();
//$apcLoader->register(true);
//*/

$loader = require_once __DIR__.'/../app/bootstrap.php.cache';
Debug::enable();
require_once __DIR__.'/../app/AppKernel.php';
error_reporting(E_ALL);
ini_set("display_errors", 1);

//$kernel = new AppKernel('dev', true);
$kernel = new AppKernel('prod', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
