<?php

/**
 * @author wjgilmore
 *
 *
 */

class IndexController extends Zend_Controller_Action
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
    public function indexAction()
    {
        /*$a = mail('fabnf@hotmail.com','test mail from z2d2', 'test');
        echo "mail";
        print_r($a);*/
    }

}

