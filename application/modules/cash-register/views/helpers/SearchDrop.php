<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CashRegister_View_Helper_SearchDrop
 *
 * @author fabrice
 */
class CashRegister_View_Helper_SearchDrop extends Zend_View_Helper_Abstract
{

    /**
     * 
     * @param stdClass[] $answers
     */
    public function searchDrop(array $answers)
    {
        ob_start();

        foreach ($answers as $answer) :
            ?>
<li role="presentation"><a href="#" ref="<?php echo $answer->ref; ?>"><?php echo $answer->ref; ?> - <?php echo $answer->name; ?></a></li>
            <?php

        endforeach;
    }

}
