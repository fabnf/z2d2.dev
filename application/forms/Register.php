<?php

class Application_Form_Register extends Zend_Form
{

    public function init()
    {
        
        /* Form Elements & Other Definitions Here ... */
        $this->setName('frmLogin');
        $this->setMethod('post');
        $this->setAction('/account/register');
        
        $email = new Zend_Form_Element_Text('email');
        $email->setAttrib('size', 35)
              ->removeDecorator('label')
              ->removeDecorator('htmlTag')
              ->addValidator('emailAddress')
              ->removeDecorator('Errors')
              ->setRequired(true)
              ->addErrorMessage('Email invalid');

        $username = new Zend_Form_Element_Text('username');
        $username->setAttrib('size', 35)
                 ->removeDecorator('label')
                 ->removeDecorator('htmlTag')
                 ->removeDecorator('Errors')
                 ->setRequired(true)
                 ->addValidator('StringLength', false, array(6,40))
                 ->addErrorMessage('Username must be between 6-10');
        
        $zip = new Zend_Form_Element_Text('zip');
        $zip->setAttrib('size', 35)
                 ->removeDecorator('label')
                 ->removeDecorator('htmlTag')
                 ->removeDecorator('Errors')
                 ->setRequired(true)
                 ->addValidator('StringLength', false, array(4,40))
                 ->addErrorMessage('Zip Code must be between 4-10');
        
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

        $this->setDecorators(array(array('ViewScript',array('viewScript' => 'account/_form_register.phtml'))));
        
        $this->addElements(array($email, $pass, $zip, $username, $submit));

    }


}

