<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    realpath(APPLICATION_PATH . '/../application/models/Entities'),
    realpath(APPLICATION_PATH . '/../application/views/scripts'),
    realpath('/home/wjgilmorecom/libraries'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

require_once 'utils/functions.php';

if (defined('APPLICATION_PATH')) {
    // re-define include path
    $includes = array(
        'APPLICATION_PATH_LIB'            => '/../library',
        'APPLICATION_PATH_CTRL_RECEPTION' => '/../application/controllers',
    );
    
    $merged = array();
    foreach ($includes as $const => $path) {
        
        $path = realpath(APPLICATION_PATH.$path);
        if (!is_bool($path)) {
            define($const, $path.DIRECTORY_SEPARATOR);
            $merged[] = $path.DIRECTORY_SEPARATOR;
        }
    }
    
    $fullinclpath = get_include_path().
                    PATH_SEPARATOR.
                    implode(
                        PATH_SEPARATOR, 
                        $merged
                    );
    ini_set('include_path', $fullinclpath);
    
    /**
     * Remember to do: 
     *  $ chmod 777 {path}
     *  in order to allow Apache to write and modify files into that dir
     */
    define(
      'APPLICATION_PATH_FILE_OUT',
      realpath(constant('APPLICATION_PATH').
              '/../../public_shared/generated/').
              DIRECTORY_SEPARATOR
    );
    
}

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    realpath(APPLICATION_PATH),
    get_include_path(),
)));

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();
