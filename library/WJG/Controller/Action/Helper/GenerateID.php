<?php

/**
 * This custom action helper just cuts down on the lengthy bit of typing we'd otherwise have
 * to do in order to retrieve the custom Entity Manager resource located in:
 * /library/WJG/Resource/Entitymanager.php 
 * 
 */
class WJG_Controller_Action_Helper_GenerateID extends Zend_Controller_Action_Helper_Abstract
{
  public function direct($length = 32)
  {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, strlen($characters) - 1)];
      }
      return $randomString;
  }
}

?>