<?php

class Application_Form_ProductSearch extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $this->setName('frmPtoductSearch');
        $this->setMethod('post');
        $this->setAction('/account');
        
        $product = new Zend_Form_Element_Text('product');
        $product->setAttrib('size', 50)
              ->removeDecorator('label')
              ->removeDecorator('htmlTag')
              ->removeDecorator('Errors')
              ->setRequired(true)
              ->addErrorMessage('Email invalid');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->removeDecorator('DtDdWrapper');

        $this->setDecorators(array(array('ViewScript',array('viewScript' => 'account/_form_product_search.phtml'))));
        
        $this->addElements(array($product, $submit));
          
    }


}

