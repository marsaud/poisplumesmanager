<?php
/**
 *
 */

/**
 * Description of Admin_View_Helper_ArticleOptions
 *
 * @author fabrice
 */
class Admin_View_Helper_ArticleOptions
{

    /**
     *
     * @param Article[]|stdClass[] $articles
     *
     * @return string
     */
    public function articleOptions(array $articles)
    {
        ob_start();

        foreach ($articles as $article):
            ?>
            <option value="<?php echo $article->reference; ?>"><?php echo $article->reference . ' : ' . $article->name; ?></option>
            <?php
        endforeach;

        return ob_get_clean();
    }

}
