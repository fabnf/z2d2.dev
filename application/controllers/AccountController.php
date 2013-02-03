<?php

class AccountController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
                /* Initialize action controller here */
        if($this->_helper->FlashMessenger->hasMessages())
        {
            $this->view->messages = $this->_helper->FlashMessenger->getMessages();
        }
    
    }

    public function mapAction()
    {
      // $this->layout->bodyScripts  = 'onload="initialize()';
    }
    
    public function indexAction()
    {
        $results = array();
        
        $oForm = new Application_Form_ProductSearch();
        if($this->getRequest()->isPost())
        {
            // if form is valid, make sure e-mail address is associated with an account
            if($oForm->isValid($this->_request->getPost()))
            {
                $product = $oForm->getValue('product');
                
                // action body
                $amazonPublicKey  = Zend_Registry::get('config')->amazon->product_advertising->acccess->key;
                $amazonPrivateKey = Zend_Registry::get('config')->amazon->product_advertising->secret->key;
                $amazonCountry    = Zend_Registry::get('config')->amazon->product_advertising->country;
                $amazonAccId      = Zend_Registry::get('config')->amazon->product_advertising->account->id;
                
                $amazon = new Zend_Service_Amazon_Query($amazonPublicKey, $amazonCountry, $amazonPrivateKey);
                $amazon->Category('Books')->Keywords($product)->AssociateTag($amazonAccId);
                $results = $amazon->search();
            }
            else
            {
                $this->view->errors = $oForm->getErrors();
            } 
        }
        $this->view->form = $oForm; 

        /*
        $amazon = new Zend_Service_Amazon($amazonPublicKey, $amazonCountry, $amazonPrivateKey);

        $params = array(
            'AssociateTag' => $amazonAccId,
            'SearchIndex' => 'All',
            'Keywords' => 'iphone'
        );
        
        echo "<br>";
        $results = $amazon->itemSearch($params);
        foreach ($results as $result) {
            echo $result->Title . '<br />';
        }

        $item = $amazon->itemLookup('B000FRU0NU', array('ResponseGroup' => 'Medium', 'AssociateTag' => $amazonAccId));
        echo "<br/>Title: {$item->Title}<br/>";
        echo "Publisher: {$item->Manufacturer}<br/>";
        echo "Category: {$item->ProductGroup}<br/>";*/
        


        $this->view->aBooks = $results;

    }

    public function showAction()
    {
        $asin = $this->_request->getParam('asin');
        
        $amazonPublicKey  = Zend_Registry::get('config')->amazon->product_advertising->acccess->key;
        $amazonPrivateKey = Zend_Registry::get('config')->amazon->product_advertising->secret->key;
        $amazonCountry    = Zend_Registry::get('config')->amazon->product_advertising->country;
        $amazonAccId      = Zend_Registry::get('config')->amazon->product_advertising->account->id;
        
        $amazon = new Zend_Service_Amazon($amazonPublicKey, $amazonCountry, $amazonPrivateKey);
        $item = $amazon->itemLookup($asin, array('ResponseGroup' => 'Medium', 'AssociateTag' => $amazonAccId));
        $this->view->item = $item;
    }
    
    public function recoverAction()
    {
        $key = $this->_request->getParam('key');
        
        if( $key != '' )
        {
            $em = $this->_helper->EntityManager();
            $account = $em->getRepository('Entities\Account')->findOneByRecovery($key);
            if($account) 
            {
                // generate a random password
                $password = $this->_helper->generateID(8);
                $account->setPassword($password);
                
                // Eerase the recovery key
                $account->setRecovery('');
                try
                {

                    $em->persist($account);
                    $em->flush();
                    
                    // create a new mail object
                    $mail = new Zend_Mail();

                    // set e-mail address
                    $mail->setFrom(Zend_Registry::get('config')->email->support);
                    $mail->addTo($account->getEmail(), "{$account->getUsername()}" );
                    $mail->setSubject('GameNomad.com: Your password has been re-set'); 

                    // Retrieve the e-mail message text 
                    include "_email_recover_password.phtml";

                    //set body
                    $mail->setBodyText(str_replace('<<password>>',$email,$password));

                    // sebd
                    $mail->send();

                    // set the flash message
                    $this->_helper->flashMessenger->addMessage(Zend_Registry::get('config')->messages->password->reset);

                    // redirect the user to the home page
                    $this->_helper->redirector('login', 'account');
                } 
                catch( Exception $e )
                {
                    $this->view->errors = array(array("There was a problem re-setting your password " . $e->getMessage()));
                }
            }  
            else
            {
                $this->view->errors = array(array("Key not found."));
            }
        }
    }
    
    public function lostAction()
    {
        $oForm = new Application_Form_Lost();
        if($this->getRequest()->isPost())
        {
            // if form is valid, make sure e-mail address is associated with an account
            if($oForm->isValid($this->_request->getPost()))
            {
                $em = $this->_helper->EntityManager();
                // check if account exists
                $account = $em->getRepository('Entities\Account')->findOneByEmail($oForm->getValue('email'));
                if($account) 
                {
                    // generate recovery key and e-mail
                    $account->setRecovery($this->_helper->generateID(32));
                    try
                    {
                        // Save the account to the database
                        $em->persist($account);
                        $em->flush();
                        
                        // create a new mail object
                        $mail = new Zend_Mail();
                        
                        // set e-mail address
                        $mail->setFrom(Zend_Registry::get('config')->email->support);
                        $mail->addTo($account->getEmail(), "{$account->getUsername()}" );
                        $mail->setSubject('GameNomad.com: Generate a new password'); 
                        
                        // Retrieve the e-mail message text 
                        include "_email_lost_password.phtml";
                        
                        //set body
                        $mail->setBodyText($email);
                        
                        // sebd
                        $mail->send();
                        
                        // set the flash message
                        $this->_helper->flashMessenger->addMessage(Zend_Registry::get('config')->messages->password->retrieve_instructions);
                        
                        // redirect the user to the home page
                        $this->_helper->redirector('login', 'account');
                        
                    } 
                    catch( Exception $e )
                    {
                        $this->view->errors = array(array("There was a problem generating your token " . $e->getMessage()));
                    }
                }
                else
                {
                    $this->view->formError = Zend_Registry::get('config')->messages->password->retrieve_failed;
                    $this->view->errors = $oForm->getErrors();
                }
            }
            else
            {
                $this->view->errors = $oForm->getErrors();
            }    
        }
        $this->view->form = $oForm;        
    }
    
    public function registerAction()
    {
        // action body
        $this->view->pageTitle = 'Register';
        $oForm = new Application_Form_Register();
        
        if( $this->getRequest()->isPost() )
        {
            if( $oForm->isValid($this->getRequest()->getPost()) )
            {
                $em = $this->_helper->EntityManager();
                // check if account exists
                $account = $em->getRepository('Entities\Account')->findOneByUsernameOrEmail($oForm->getValue('username'), $oForm->getValue('email'));

                if(!$account) 
                {
                    $account = new \Entities\Account;
                    
                    // Assign the account attributes 
                    $account->setUsername($oForm->getValue('username'));
                    $account->setEmail($oForm->getValue('email'));
                    $account->setPassword($oForm->getValue('pswd'));
                    $account->setZip($oForm->getValue('zip'));
                    $account->setConfirmed(0);
                    
                    // set the confirmation key
                    $account->setRecovery($this->_helper->generateID(32));
                    
                    try 
                    {
                        // Save the account to the database
                        $em->persist($account);
                        $em->flush();
                        
                        // create a new mail object
                        $mail = new Zend_Mail();
                        
                        // set e-mail address
                        $mail->setFrom(Zend_Registry::get('config')->email->support);
                        $mail->addTo($account->getEmail(), "{$account->getUsername()}" );
                        $mail->setSubject('GameNomad.com: Confirm Your account');
                        
                        // Retrieve the e-mail message text 
                        include "_email_confirm_email_address.phtml";

                        //set body
                        $mail->setBodyText($email);
                        
                        // sebd
                        $mail->send();
                        
                        // set the flash message
                        $this->_helper->flashMessenger->addMessage(Zend_Registry::get('config')->messages->register->successful);
                        
                        // redirect the user to the home page
                        return $this->_helper->redirector('login', 'account');
                        
                    }
                    catch( Exception $e )
                    {
                        $this->view->errors = array(array("There was a problem creating the account " . $e->getMessage()));
                    }
                }
                else
                {
                    $oForm->addErrorMessage('account already exists');
                }
            }
            else
            {
                $this->view->errors = $oForm->getErrorMessages();
            }    
        }

        $this->view->errors = array_merge(array('general' => $oForm->getErrorMessages()), $oForm->getErrors());
        $this->view->form = $oForm;
    }

    public function confirmAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $key = $this->_request->getParam('key');
        
        // Key should no be blank
        if( $key != '' )
        {
            $em = $this->getInvokeArg('bootstrap')->getResource('entityManager');
            $account = $em->getRepository('Entities\Account')->findOneByRecovery($this->_request->getParam('key'));
            if($account)
            {
                // account found, confirm and reset recovery attribute
                $account->setConfirmed(1);
                $account->setRecovery('');
                
                // Save the account to the database
                $em->persist($account);
                $em->flush();
                // Set the flash message and redirect the user to the login page
                $this->_helper->flashMessenger->addMessage(Zend_Registry::get('config')->messages->register->confirm->successful);
                $this->_helper->redirector('login','account');
            }
            else
            {
                // Set flash message and redirect user to the login page
                $this->_helper->flashMessenger->addMessage(Zend_Registry::get('config')->messages->register->confirm->failed);
                $this->_helper->redirector('login','account');
       
            }
        }
    }
    
    public function loginAction()
    {
        $auth = Zend_Auth::getInstance();
        if($auth->hasIdentity())
        {
            $identity = $auth->getIdentity();
            if(isset($identity)) 
            {
                // Generate flash message and redirect user
                $this->_helper->flashMessenger->addMessage(sprintf("Welcome back, %s", $identity));
                return $this->_helper->redirector('index', 'account');                
            }

        }
        $form = new Application_Form_Login();
        
        // has login form been posted?
        if($this->getRequest()->isPost())
        {
            // if valid attempt to authenticate user
            if($form->isValid($this->getRequest()->getPost()))
            {
                // Did the user successfully login?
                if($this->_authenticate($this->getRequest()->getPost()))
                {
                    $em = $this->getInvokeArg('bootstrap')->getResource('entityManager');
                    $account = $em->getRepository('Entities\Account')->findOneByEmail($form->getValue('email'));
                    
                    $em->persist($account);
                    $em->flush();
                    // Generate flash message and redirect user
                    $this->_helper->flashMessenger->addMessage(Zend_Registry::get('config')->messages->login->successful);
                    return $this->_helper->redirector('index', 'account');
                }
                else
                {
                    $this->view->formError = Zend_Registry::get('config')->messages->login->failed;
                    $this->view->errors = $form->getErrors();
                }
            }
            else
            {
                $this->view->errors = $form->getErrors();
            }
        }
        
        $this->view->form = $form;
    }
    
    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->flashMessenger->addMessage('You are logout of your account');
        $this->_helper->redirector('index', 'index');
    }
    
    protected function _authenticate($data)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $authAdapter = new Zend_Auth_Adapter_DbTable($db);
        $authAdapter->setTableName('accounts');
        $authAdapter->setCredentialColumn('password');
        $authAdapter->setCredentialTreatment('MD5(?) AND confirmed = 1' );
        $authAdapter->setIdentityColumn('email');

        $authAdapter->setIdentity($data['email']);
        $authAdapter->setCredential($data['pswd']);
        $auth = Zend_Auth::getInstance();

        $result = $auth->authenticate($authAdapter); 
        
        if($result->isValid())
        {
            if($data['public'] == "1")
            {
                Zend_Session::rememberMe(1209600);
            }
            else
            {
                Zend_Session::forgetMe();
            }
            
            return true;
        }
        else
        {
            return false;
        }
    }

}



