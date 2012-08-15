var qty = 1;

function frontEndInit()
{
    var modificatorPad = $('modificators');
    var mButtons = $A(modificatorPad.getElementsByTagName('input'));
    mButtons.map(Element.extend);
    mButtons.each(function (item){
        $(item).observe('click', selectModificator);
    });

    var categoryPad = $('categorypad');
    var cButtons = $A(categoryPad.getElementsByClassName('category'));
    cButtons.map(Element.extend);
    cButtons.each(function (item){
        $(item).observe('click', selectCategory);
    });

    var subCategoryPad = $('subcategorypad');
    var scButtons = $A(subCategoryPad.getElementsByClassName('category'));
    scButtons.map(Element.extend);
    scButtons.each(function (item){
        $(item).observe('click', selectSubCategory);
    });

    var articlePad = $('articlepad');
    var buttons = $A(articlePad.getElementsByClassName('article'));
    buttons.map(Element.extend);
    buttons.each(function (item){
        $(item).observe('click', selectForBill);
    });


}

function selectModificator(event)
{
    cleanModificators();

    var element = event.element();
    qty = element.getAttribute('qty');
    element.addClassName('highlight');
}

function cleanModificators()
{
    qty = 1;
    var modificatorPad = $('modificators');
    var mButtons = $A(modificatorPad.getElementsByTagName('input'));
    mButtons.map(Element.extend);
    mButtons.each(function (item){
        $(item).removeClassName('highlight');
    });
}

function selectCategory(event)
{
    var element = event.element();
    var category = element.getAttribute('ref');
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
    var category = element.getAttribute('ref');
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

        var scButtons = $A(subCategoryPad.getElementsByClassName('category'));
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

        var buttons = $A(articlePad.getElementsByClassName('article'));
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
    var element = $(event.element());
    var target = $$('#billList div[ref=' + element.getAttribute('ref') + ']');
    if (target.size() == 0)
    {
        target = element.clone(true);
        target.textContent += ' x' + qty;
        target.observe('click', unselectForBill);

        target.appendChild(new Element('input', {
            type: 'hidden',
            name: target.getAttribute('ref'),
            value: qty
        }));

        var bill = $('billList');
        bill.appendChild(target);
    }
    else
    {
        target = $(target[0]);
        var input = target.getElementsByTagName('input');
        input = input[0];
        var oldValue = $F(input);
        input.setValue(parseInt(oldValue) + parseInt(qty));
        target.update(target.getAttribute('name') + ' x' + $F(input));
        target.appendChild(input);
    }
    cleanModificators();
}

function unselectForBill(event)
{
    var element = $(event.element());
    element.stopObserving();
    element.remove();
}
