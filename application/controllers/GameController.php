<?php
use Doctrine\ORM\Query;
class GameController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        $em = $this->_helper->EntityManager();
        $games = $em->getRepository('Entities\Game')->findAll(Query::HYDRATE_SIMPLEOBJECT);
        
        $this->view->games = $games;
    }

    public function addAction()
    {
        // action body
        
        $request = $this->getRequest();
        $form    = new Application_Form_Game();
        
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {
                $em = $this->_helper->EntityManager();
                
                $game = new \Entities\Game;
                foreach($form->getValues() as $key => $value) 
                {
                    $method = 'set'.ucfirst($key); 
                    $game->$method($value);
                }
                $em->persist($game);
                $em->flush();

                return $this->_helper->redirector('add');
            }
        }
 
        $this->view->form = $form;    
    }


}



