
<?php

/**
 * This custom action helper just cuts down on the lengthy bit of typing we'd otherwise have
 * to do in order to retrieve the custom Entity Manager resource located in:
 * /library/WJG/Resource/Entitymanager.php 
 * 
 */
class WJG_Controller_Action_Helper_Initializer extends Zend_Controller_Action_Helper_Abstract
{
    
  public function init()
  {
      $auth = Zend_Auth::getInstance();
      if($auth->hasIdentity())
      {
          $identity = $auth->getIdentity();
          if(isset($identity))
          {
              $em = $this->getActionController()
                      ->getInvokeArg('bootstrap')
                      ->getResource('entityManager');
              
              // retrieve information about the logged in user
              $account = $em->getRepository('Entities\Account')
                      ->findOneByEmail($identity);
              Zend_Layout::getMvcInstance()->getView()->account = $account;
          }
      }
  }
}

?>


