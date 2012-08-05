function articleInit()
{
    var articleBox = $('modref');
    articleBox.observe('change', requestForArticle)
    var updateForm = $('updatearticleform');
    updateForm.observe('submit', checkUpdateArticle);
}

function requestForArticle()
{
    var articleBox = $('modref');
    var ref = $F(articleBox);

    if (ref == '')
    {
        defaultUpdateArticleForm();
    }
    else
    {

        new Ajax.Request('/admin/article/get/format/json/ref/' + ref,
        {
            onSuccess: function (response){
                updateUpdateArticleForm(response);
            },
            onFailure: function() {
                alert('ERROR');
            }
        }
        );
    }
}

function defaultUpdateArticleForm()
{
    $('modname').setValue('');
    $('moddesc').setValue('(r.a.s.)');
    $('modpriceht').setValue('');
    $('modtva').setValue(0);
    $('modstock').setValue(null);
    $('modunit').setValue('');
    cleanCheckBoxes('modcat');
}

function cleanCheckBoxes(id)
{
    var fieldset = $(id);
    var checkBoxes = $A(fieldset.getElementsByTagName('input'));
    checkBoxes.each(function (item){
        item.setValue(null);
    });
}

function updateUpdateArticleForm(response)
{
    var article = eval(response.responseJSON);

    if (article != null)
    {
        $('modname').setValue(article.name);
        $('moddesc').setValue(article.description);
        $('modpriceht').setValue(article.priceht);
        $('modtva').setValue(article.tax);
        if (article.stock == '1')
        {
            $('modstock').setValue('on');
        }
        else
        {
            $('modstock').setValue(null);
        }
        $('modunit').setValue(article.unit);
        cleanCheckBoxes('modcat');
        var categories = $A(article.categories);
        categories.each(function (item){
            $('mod' + item).setValue('on');
        });
        $('modprovider').setValue(article.provider);
    }
    else
    {
        alert('No JSON response');
    }
}

function checkUpdateArticle(event)
{
    var articleBox = $('modref');
    var ref = $F(articleBox);

    if (ref == '')
    {
        alert('Choisissez l\'article à modifier');
        event.stop();
    }
}