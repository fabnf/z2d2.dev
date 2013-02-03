<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Zend_View_Helper_Errors extends Zend_View_Helper_Abstract
{
    public function Errors($errors)
    {
        if(count($errors) > 0 )
        {
            echo "<div id=\"errors\">";
            echo "<ul>";
            foreach($errors as $error)
            {
                if(isset($error[0]) && $error[0] != "")
                {
                    printf("<li>%s</li>", $error[0]);
                }
            }
            echo "</ul></div>";
        }   
    }
}
?>
