<?php


define('BASE_PATH', realpath(dirname(__FILE__) . '/../../'));

set_include_path(
    '.'
    . PATH_SEPARATOR . BASE_PATH . '/library'
    . PATH_SEPARATOR . get_include_path()
);

define('APPLICATION_PATH', BASE_PATH . '/application');

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));


require_once 'controllers/ControllerTestCase.php';
require_once 'models/ModelTestCase.php';

