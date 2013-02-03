<?php

class Application_Form_Lost extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $this->setName('frmLost');
        $this->setMethod('post');
        $this->setAction('/account/lost');
        
        $email = new Zend_Form_Element_Text('email');
        $email->setAttrib('size', 35)
              ->removeDecorator('label')
              ->removeDecorator('htmlTag')
              ->addValidator('emailAddress')
              ->removeDecorator('Errors')
              ->setRequired(true)
              ->addErrorMessage('Email invalid');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->removeDecorator('DtDdWrapper');

        $this->setDecorators(array(array('ViewScript',array('viewScript' => 'account/_form_lost.phtml'))));
        
        $this->addElements(array($email, $submit));
          
    }


}

