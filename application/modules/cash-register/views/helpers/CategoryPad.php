<?php
/**
 *
 */

/**
 * Description of CashRegister_View_Helper_CategoryPad
 *
 * @author fabrice
 */
class CashRegister_View_Helper_CategoryPad
{

    /**
     *
     * @param Category[] $categoryList
     *
     * @return string
     */
    public function categoryPad(array $categoryList)
    {
        ob_start();
        foreach ($categoryList as $category) :
            /* @var $category Category */
            ?><div class="category button btn btn-primary" ref="<?php echo $category->reference; ?>"><?php echo $category->name; ?></div><?php
        endforeach;

        return ob_get_clean();
    }

}
