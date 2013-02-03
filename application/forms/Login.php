<?php

class Application_Form_Login extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $this->setName('frmLogin');
        $this->setMethod('post');
        $this->setAction('/account/login');
        
        $email = new Zend_Form_Element_Text('email');
        $email->setAttrib('size', 35)
              ->removeDecorator('label')
              ->removeDecorator('htmlTag')
              ->addValidator('emailAddress')
              ->removeDecorator('Errors')
              ->setRequired(true)
              ->addErrorMessage('Email invalid');

        $options = array('1' => 'yes', '0' => 'no');
        $public = new Zend_Form_Element_Radio('public', array('value' => 1));
        $public->addMultiOptions($options);
        $public->removeDecorator('Label')
               ->removeDecorator('htmlTag')
               ->removeDecorator('Errors')
               /*->addDecorators(array('Label', array('class' => 'form-label')))*/
               ->setRequired(true);
        
        $pass = new Zend_Form_Element_Password('pswd');
        $pass->setAttrib('size', '10')
             ->removeDecorator('label')
             ->removeDecorator('htmlTag')
             ->removeDecorator('Errors')
             ->setRequired(true)
             ->addValidator('StringLength', false, array(4,10))
             ->addErrorMessage('Password must be between 4-10');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->removeDecorator('DtDdWrapper');

        $this->setDecorators(array(array('ViewScript',array('viewScript' => 'account/_form_login.phtml'))));
        
        $this->addElements(array($pass, $email, $public, $submit));
          
    }


}

