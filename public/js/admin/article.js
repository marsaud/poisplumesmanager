function articleInit()
{
    jQuery('#modref').change(requestForArticle);
    jQuery('#updatearticleform').submit(checkUpdateArticle);
}

function requestForArticle()
{
    var ref = jQuery(this).val();
    
    if ('' === ref)
    {
        defaultUpdateArticleForm();
    }
    else
    {
        jQuery.ajax({
            url: '/admin/article/get/format/json/ref/' + ref,
            type: "GET",
            dataType: "json",
            success: updateUpdateArticleForm,
            error: function(xhr, status) {
                alert('ERROR ' + status);
            }
        });
    }
}

function defaultUpdateArticleForm()
{
    jQuery('#modname').val('');
    jQuery('#modpriceht').val('');
    jQuery('#modtva').val(0);
    jQuery('#modstock').val(null);
    jQuery('#modunit').val('');
    jQuery('#modcat').val([]);
    jQuery('#modpromo').val([]);
}

function updateUpdateArticleForm(response)
{
    if (null !== response)
    {
        jQuery('#modname').val(response.name);
        jQuery('#moddesc').val(response.description);
        jQuery('#modpriceht').val(response.priceht);
        jQuery('#modtva').val(response.tax);
        
        if ('1' === response.stock)
        {
            jQuery('#modstock').prop('checked', true);
        }
        else
        {
            jQuery('#modstock').prop('checked', false);
        }
        jQuery('#modunit').val(response.unit);
        jQuery('#modcat').val(response.categories);
        jQuery('#modpromo').val(response.promos);

        jQuery('#modprovider').val(response.provider);
    }
    else
    {
        alert('No JSON response');
    }
}

function checkUpdateArticle(event)
{
    if ('' === jQuery('#modref').val())
    {
        alert('Choisissez l\'article à modifier');
        event.preventDefault();
    }
}