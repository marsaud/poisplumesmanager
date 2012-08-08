function frontEndInit()
{
    var categoryPad = $('categorypad');
    var cButtons = $A(categoryPad.getElementsByTagName('input'));
    cButtons.map(Element.extend);
    cButtons.each(function (item){
        $(item).observe('click', selectCategory);
    });

    var subCategoryPad = $('subcategorypad');
    var scButtons = $A(subCategoryPad.getElementsByTagName('input'));
    scButtons.map(Element.extend);
    scButtons.each(function (item){
        $(item).observe('click', selectSubCategory);
    });

    var articlepad = $('articlepad');
    var buttons = $A(articlepad.getElementsByTagName('input'));
    buttons.map(Element.extend);
    buttons.each(function (item){
        $(item).observe('click', selectForBill);
    });
}

function selectCategory(event)
{
    var element = event.element();
    var category = $F(element);
    new Ajax.Request('/cash-register/front-end/get-categories/category/' + category,
    {
        onSuccess: function (response){
            updateSubCategoryPad(response, category);
        },
        onFailure: function() {
            alert('ERROR');
        }
    });
}

function selectSubCategory(event)
{
    var element = event.element();
    var category = $F(element);
    new Ajax.Request('/cash-register/front-end/get-articles/category/' + category,
    {
        onSuccess: function (response){
            updateArticlePad(response);
        },
        onFailure: function() {
            alert('ERROR');
        }
    });
}

function updateSubCategoryPad(response, category)
{
    var subCategories = response.responseText;

    if (subCategories != null)
    {
        var subCategoryPad = $('subcategorypad');
        subCategoryPad.update(subCategories);

        var scButtons = $A(subCategoryPad.getElementsByTagName('input'));
        scButtons.map(Element.extend);
        scButtons.each(function (item){
            $(item).observe('click', selectSubCategory);
        });

        new Ajax.Request('/cash-register/front-end/get-articles/category/' + category,
        {
            onSuccess: function (response){
                updateArticlePad(response);
            },
            onFailure: function() {
                alert('ERROR');
            }
        });
    }
    else
    {
        alert('No HTML Response');
    }
}

function updateArticlePad(response)
{
    var articles = response.responseText;

    if (articles != null)
    {
        var articlePad = $('articlepad');
        articlePad.update(articles);

        var buttons = $A(articlePad.getElementsByTagName('input'));
        buttons.map(Element.extend);
        buttons.each(function (item){
            $(item).observe('click', selectForBill);
        });
    }
    else
    {
        alert('No HTML Response');
    }
}

function selectForBill(event)
{
    var bill = $('billList');

    var element = $(event.element());
    var duplicate = element.clone();
    duplicate.observe('click', unselectForBill);

    bill.appendChild(duplicate);
}

function unselectForBill(event)
{
    var element = $(event.element());
    element.stopObserving();
    element.remove();
}
