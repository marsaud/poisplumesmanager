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
    // cleanCheckBoxes('modcat');
    cleanMultiSelect('modcat');
    cleanMultiSelect('modpromo');
}

function cleanCheckBoxes(id)
{
    var fieldset = $(id);
    var checkBoxes = $A(fieldset.getElementsByTagName('input'));
    checkBoxes.each(function (item){
        item.setValue(null);
    });
}

function cleanMultiSelect(id)
{
    var options = $A($(id).options);
    options.each(function(option){
        option.selected = false;
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

        cleanMultiSelect('modcat');
        var categories = $A(article.categories);
        var catOptions = $A($('modcat').options);
        catOptions.each(function(option){
            categories.each(function (categorie){
                if (option.value == categorie)
                {
                    option.selected = true;
                }
            });
        });

        cleanMultiSelect('modpromo');
        var promos = $A(article.promos);
        var promoOptions = $A($('modpromo').options);
        promoOptions.each(function(option){
            promos.each(function (promo){
                if (option.value == promo)
                {
                    option.selected = true;
                }
            });
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