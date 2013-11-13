function categoryInit()
{
    jQuery('#modcategoryref').on('change', requestForCategory);
    jQuery('#updatecategoryform').on('submit', checkUpdateCategory);
}

function requestForCategory()
{
    var ref = jQuery('#modcategoryref').val();

    if ('' == ref)
    {
        defaultUpdateCategoryForm();
    }
    else
    {
        jQuery.ajax({
            url: '/admin/category/get/format/json/ref/' + ref,
            type: "GET",
            dataType: "json",
            success: updateUpdateCategoryForm,
            error: function(xhr, status) {
                alert('ERROR ' + status);
            }
        });
    }
}

function defaultUpdateCategoryForm()
{
    jQuery('#modcategoryname').val('');
    jQuery('#modcategorydesc').val('');
    jQuery('#modparentcategory').val('');
}

function updateUpdateCategoryForm(response)
{
    if (null != response)
    {
        jQuery('#modcategoryname').val(response.name);
        jQuery('#modcategorydesc').val(response.description);
        jQuery('#modparentcategory').val(response.parentReference);
    }
    else
    {
        alert('No JSON response');
    }
}

function checkUpdateCategory(event)
{
    if ('' == jQuery('#modcategoryref').val())
    {
        alert('Choisissez la catégorie à modifier');
        event.preventDefault();
    }
}