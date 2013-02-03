<?php

class Application_Form_Game extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $this->setMethod('post');
        
        $this->addElement('text', 'name', array(
            'label' => 'Name: ',
        ));
        $this->addElement('text', 'publisher', array(
            'label' => 'Publisher: ',
        ));
       
        $this->addElement('text', 'price', array(
            'label' => 'Price: ',
        ));
        
    }


}

