function categoryInit()
{
    var categoryBox = $('modcategoryref');
    categoryBox.observe('change', requestForCategory)
    var updateForm = $('updatecategoryform');
    updateForm.observe('submit', checkUpdateCategory);
}

function requestForCategory()
{
    var categoryBox = $('modcategoryref');
    var ref = $F(categoryBox);

    new Ajax.Request('/admin/category/get/format/json/ref/' + ref,
    {
        onSuccess: function (response){
            updateUpdateCategoryForm(response);
        },
        onFailure: function() {
            alert('ERROR');
        }
    }
    );
}

function updateUpdateCategoryForm(response)
{
    var category = eval(response.responseJSON);

    if (category != null)
    {
        $('modcategoryname').setValue(category.name);
        $('modcategorydesc').setValue(category.description);
        $('modparentcategory').setValue(category.parentReference);
    }
    else
    {
        alert('No JSON response');
    }
}

function checkUpdateCategory(event)
{
    var categoryBox = $('modcategoryref');
    var ref = $F(categoryBox);

    if (ref == '')
    {
        alert('Choisissez la catégorie à modifier');
        event.stop();
    }
}