<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

  /**
   *
   *
   *
   *
   */
  protected function _initConfig()
  {

    $config = new Zend_Config($this->getOptions());
    Zend_Registry::set('config', $config);

  }


    
  /**
   *
   *
   *
   *
   */
  protected function _initDoctrine() {

    require_once('Doctrine/Common/ClassLoader.php');
    
    $autoloader = Zend_Loader_Autoloader::getInstance();
    $classLoader = new \Doctrine\Common\ClassLoader('Entities',
      realpath(Zend_Registry::get('config')->resources->entityManager->connection->entities), 'loadClass');

    $autoloader->pushAutoloader(array($classLoader, 'loadClass'), 'Entities');
    
    $classLoader = new \Doctrine\Common\ClassLoader('Repositories',
      realpath(Zend_Registry::get('config')->resources->entityManager->connection->entities), 'loadClass');

    $autoloader->pushAutoloader(array($classLoader, 'loadClass'), 'Repositories');    
    /*
    require_once ('Zend/Loader/StandardAutoloader.php');
    
$autoloader = new Zend_Loader_StandardAutoloader(array(
    'namespaces' => array(
        'Zend'        => dirname(__FILE__) . '/Zend',
        'ZendRest'    => dirname(__FILE__) . '/ZendRest',
        'ZendService' => dirname(__FILE__) . '/ZendService',
     ),
     'fallback_autoloader' => true));

$autoloader->register();    */

  }
  
  protected function _initGlobalVars()
  {
      Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH.'/../library/WJG/Controller/Action/Helper');
      $initializer = Zend_Controller_Action_HelperBroker::addHelper(new WJG_Controller_Action_Helper_Initializer());
  }

  
      /**
     * _initAuthentication
     *
     * @return void
     */
    protected function _initAuthentication()
    {
        $this->_auth = Zend_Auth::getInstance();
        require_once 'controllers/plugin/Authenticated.php';
        $fc = Zend_Controller_Front::getInstance();
        $fc->registerPlugin(new Controllers_Plugin_Authenticated($this->_auth));
    }
    
}

