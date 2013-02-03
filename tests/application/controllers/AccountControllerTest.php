<?php

class AccountControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{

    public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
    }

    public function testIndexAction()
    {
        $params = array('action' => 'index', 'controller' => 'Account', 'module' => 'default');
        $urlParams = $this->urlizeOptions($params);
        $url = $this->url($urlParams);
        $this->dispatch($url);
        
        // assertions
        $this->assertModule($urlParams['module']);
        $this->assertController($urlParams['controller']);
        $this->assertAction($urlParams['action']);
        $this->assertQueryContentContains(
            'div#view-content p',
            'View script for controller <b>' . $params['controller'] . '</b> and script/action name <b>' . $params['action'] . '</b>'
            );
    }

    public function testRegisterAction()
    {
        $params = array('action' => 'register', 'controller' => 'Account', 'module' => 'default');
        $urlParams = $this->urlizeOptions($params);
        $url = $this->url($urlParams);
        $this->dispatch($url);
        
        // assertions
        $this->assertModule($urlParams['module']);
        $this->assertController($urlParams['controller']);
        $this->assertAction($urlParams['action']);
        $this->assertQueryCount(
            'form#frmRegister', 1);
    }

    public function testLoginActionContainsLoginForm()
    {
        $params = array('action' => 'login', 'controller' => 'Account', 'module' => 'default');
        $urlParams = $this->urlizeOptions($params);
        $url = $this->url($urlParams);
        $this->dispatch($url);
        
        // assertions
        $this->assertModule($urlParams['module']);
        $this->assertController($urlParams['controller']);
        $this->assertAction($urlParams['action']);
        $this->assertQueryCount('form#frmLogin', 1);
        $this->assertQueryCount('input[name~="email"]', 1);
        $this->assertQueryCount('input[name~="pswd"]', 1);
        $this->assertQueryCount('input[name~="submit"]', 1);
    }
    
    private function _loginValidUser()
    {
        $this->request->setMethod('POST')->setPost(array('email'  => 'fabnf@hotmail.com',
                                                 'pswd'   => 'frhNk8cM',
                                                 'public' => 0));
        $this->dispatch('account/login');
        $this->assertController('account');
        $this->assertAction('login');
        $this->assertRedirectTo('/account');
        $this->assertTrue(Zend_Auth::getInstance()->hasIdentity());
    }

    public function testValidLoginRedirectsToHomePage()
    {
        $this->_loginValidUser();
    }
    
    public function testLogoutPageAvailableToLoggedInUser()
    {
        $this->_loginValidUser();
        $this->dispatch('account/logout');
        $this->assertController('account');
        $this->assertAction('logout');
        $this->assertNotRedirectTo('account/login');
    }
    
    public function testUsersCanRegisterWhenUsingValidData()
    {
        $this->request->setMethod('POST')->setPost(array('email'    => 'fab8nf3@hotmail.com',
                                                         'username' =>  'fab12426',
                                                         'zip'      => '4311',
                                                         'pswd'     => '12345678'));
        $this->dispatch('/account/register');
        $this->assertController('account');
        $this->assertAction('register');
       // print_r($this->getResponse());
       // $this->assertRedirectTo('/account/login'); 
    }
}





