<?php
/**
 * This source file is part of the Bad Debt project
 *
 * PHP version 5
 * 
 * @category   Zend
 * @package    Zend_Application
 * @subpackage AuthenicatedControllerPlugin
 * @author     Neil Critchell <neil.critchell@evolvingagency.com>
 * @copyright  2011 Evolving Media
 * @license    http://www.evolvingagency.com/ Propertiary
 * @version    SVN: <svn_id>
 * @link       http://www.evolvingagency.com/
 */

/**
 * Front Controller Plugin to handle language settings, translations and locale's.
 * 
 * @category ControllerPlugin
 * @package  Authenticated
 * @author   Neil Critchell <neil.critchell@evolvingagency.com>
 * @license  http://www.evolvingagency.com/ Propertiary
 * @link     http://www.evolvingagency.com/
 */
class Controllers_Plugin_Authenticated extends Zend_Controller_Plugin_Abstract
{
    private $_auth = null;
    /**
     * Constructor stores passed values into own object.
     * 
     * @param Zend_Acl  $acl  acl object created at bootstrap
     * @param Zend_Auth $auth auth object created at bootstrap
     * 
     * @return void
     */
    public function __construct(Zend_Auth $auth)
    {
        $this->_auth = $auth;
    }
    
    
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        
        
        if( $request->getActionName() == 'index' && $request->getControllerName() == 'account') {
        
            if(!$this->_auth->hasIdentity()) {
                $request->setControllerName('account')->setActionName('login');
            }
        }
    }
}
