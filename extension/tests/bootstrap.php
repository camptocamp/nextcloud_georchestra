<?php

if (!defined('PHPUNIT_RUN')) {
    define('PHPUNIT_RUN', 1);
}

xdebug_break();

require_once '/var/www/html/lib/base.php';

// Fix for "Autoload path not allowed: .../tests/lib/testcase.php"
\OC::$loader->addValidRoot(OC::$SERVERROOT . '/tests');

// Fix for "Autoload path not allowed: .../georchestra/tests/testcase.php"
\OC_App::loadApp('georchestra');

if(!class_exists('PHPUnit_Framework_TestCase')) {
    require_once('PHPUnit/Autoload.php');
}

OC_Hook::clear();
