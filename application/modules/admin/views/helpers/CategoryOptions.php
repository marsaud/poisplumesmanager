<?php
/**
 *
 */

/**
 * Description of Admin_View_Helper_CategoryOptions
 *
 * @author fabrice
 */
class Admin_View_Helper_CategoryOptions
{

    /**
     *
     * @param Category[] $categoryTree
     * @param boolean $parentCategoriesOnly
     *
     * @return string
     */
    public function categoryOptions(array $categoryTree, $parentCategoriesOnly = false)
    {
        ob_start();

        foreach ($categoryTree as $category) :
            ?>
            <option value="<?php echo $category->reference; ?>"><?php echo $category->name; ?></option>
            <?php
            if (!$parentCategoriesOnly)
            {
                echo $this->_subcategoryOptions($category);
            }
        endforeach;

        return ob_get_clean();
    }

    protected function _subcategoryOptions(Category $category)
    {
        ob_start();

        foreach ($category as $subCategory) :
            ?>
            <option value="<?php echo $subCategory->reference; ?>">&gt; <?php echo $subCategory->name; ?></option>
            <?php
        endforeach;

        return ob_get_clean();
    }

}
