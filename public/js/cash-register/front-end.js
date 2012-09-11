/**
 * Active quantity modifier value
 */
var qty = 1;
/**
 * Active promotion reference
 */
var promo = null;
/**
 * Activate promotion inhibiter
 */
var removePromo = false;
/**
 * Category tree cash
 */
var categoryTreeCash = {};
/**
 * Article list cash
 */
var articlesCash = {};

/**
 * Init the cash-register pad at load or reload
 */
function frontEndInit()
{
    var modificatorPad = $('quantity');
    var mButtons = $A(modificatorPad.getElementsByTagName('input'));
    mButtons.map(Element.extend);
    mButtons.each(function (item){
        $(item).observe('click', toggleQuantity);
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

    var promoPad = $('promoPad');
    var pButtons = $A(promoPad.getElementsByTagName('input'));
    pButtons.map(Element.extend);
    pButtons.each(function (item){
        $(item).observe('click', togglePromo);
    });
    promoPad.toggle();
    $('promoToggle').observe('click', togglePromoPad);
    $('promoRemove').observe('click', togglePromoRemove);
}

/**
 * Event method
 */
function toggleQuantity(event)
{
    var element = event.element();

    if (element.hasClassName('highlight'))
    {
        qty = 1;
        element.removeClassName('highlight');
    }
    else
    {
        cleanQuantity();
        qty = element.getAttribute('qty');
        element.addClassName('highlight');
    }
}

/**
 * Event method
 */
function togglePromo(event)
{
    var element = event.element();

    if (element.hasClassName('highlight'))
    {
        promo = null;
        element.removeClassName('highlight');
    }
    else
    {
        cleanPromos();
        promo = {};
        promo['id'] = element.getAttribute('promoid');
        promo['ratio'] = element.getAttribute('promoratio');
        element.addClassName('highlight');
    }
}

/**
 * Event method
 */
function togglePromoRemove(event)
{
    var element = event.element();

    if (element.hasClassName('highlight'))
    {
        removePromo = false;
        element.removeClassName('highlight');
    }
    else
    {
        cleanPromos();
        removePromo = true;
        element.addClassName('highlight');
    }
}

/**
 * Resets an inactive quantity modficators pad
 */
function cleanQuantity()
{
    qty = 1;
    var modificatorPad = $('quantity');
    var mButtons = $A(modificatorPad.getElementsByTagName('input'));
    mButtons.map(Element.extend);
    mButtons.each(function (item){
        $(item).removeClassName('highlight');
    });
}

/**
 * Resets an inactive quantity modficators pad
 */
function cleanPromos()
{
    promo = null;
    var promoPad = $('promoPad');
    var pButtons = $A(promoPad.getElementsByTagName('input'));
    pButtons.map(Element.extend);
    pButtons.each(function (item){
        $(item).removeClassName('highlight');
    });
    removePromo = false;
    $('promoRemove').removeClassName('highlight');
}

/**
 * Event method
 *
 * Open/Close promotions touchpad
 */
function togglePromoPad()
{
    if (promo == null)
    {
        $('promoPad').toggle();
    }
}

/**
 * Event method
 *
 * Select a category to load its sub-subcategories and articles
 */
function selectCategory(event)
{
    var element = event.element();
    var category = element.getAttribute('ref');
    if (categoryTreeCash[category] != null)
    {
        _updateSubCategoryPad(category, categoryTreeCash[category]);
    }
    else
    {
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
}

/**
 * Event method
 */
function selectSubCategory(event)
{
    var element = event.element();
    var category = element.getAttribute('ref');
    if (categoryTreeCash[category] != null)
    {
        $('subcategorypad').update(categoryTreeCash[category]);
    }
    else
    {
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
}

/**
 * Ajax callback
 */
function updateSubCategoryPad(response, category)
{
    var subCategories = response.responseText;
    categoryTreeCash[category] = subCategories;

    if (subCategories != null)
    {
        _updateSubCategoryPad(category, subCategories);
    }
    else
    {
        alert('No HTML Response');
    }
}

function _updateSubCategoryPad(category, subCategories)
{
    var subCategoryPad = $('subcategorypad');
    subCategoryPad.update(subCategories);

    var scButtons = $A(subCategoryPad.getElementsByClassName('category'));
    scButtons.map(Element.extend);
    scButtons.each(function (item){
        $(item).observe('click', selectSubCategory);
    });

    if (articlesCash[category] != null)
    {
        _updateArticlePad(articlesCash[category])
    }
    else
    {
        new Ajax.Request('/cash-register/front-end/get-articles/category/' + category,
        {
            onSuccess: function (response){
                updateArticlePad(response, category);
            },
            onFailure: function() {
                alert('ERROR');
            }
        });
    }

}

/**
 * Event method
 */
function updateArticlePad(response, category)
{
    var articles = response.responseText;
    articlesCash[category] = articles;

    if (articles != null)
    {
        _updateArticlePad(articles);
    }
    else
    {
        alert('No HTML Response');
    }
}

function _updateArticlePad(articles)
{
    var articlePad = $('articlepad');
    articlePad.update(articles);

    var buttons = $A(articlePad.getElementsByClassName('article'));
    buttons.map(Element.extend);
    buttons.each(function (item){
        $(item).observe('click', selectForBill);
    });
}

/**
 * Event method
 */
function selectForBill(event)
{
    var element = $(event.element());
    var target = $$('#billList div[ref=' + element.getAttribute('ref') + ']');

    if (target.size() == 0)
    {
        target = element.clone(true);
        target.observe('click', unselectForBill);
        var bill = $('billList');
        bill.appendChild(target);
    }
    else
    {
        target = $(target[0]);
    }

    var inputText = target.textContent;
    var input = $A(target.getElementsByTagName('input'));
    input.each(function (item) {
        var itemClass = item.className;
        if (itemClass == 'qty')
        {
            item.setValue(parseInt($F(item)) + parseInt(qty));
            inputText = target.getAttribute('name') + ' x' + $F(item); // @todo Ça me fait grave chier
        }
        if (itemClass == 'promoid' && promo != '')
        {
            if (removePromo)
            {
                item.setValue('');
                target.setAttribute('ratiobuffer', '0');
            }
            else if (promo != null)
            {
                item.setValue(promo['id']);
                target.setAttribute('ratiobuffer', promo['ratio']);
                inputText += '<br />' + promo['ratio'] + '%';
            }
            else if ($F(item) != '')
            {
                inputText += '<br />' + item.getAttribute('ratiobuffer') + '%';
            }
        }
    });

    target.update(inputText);
    input.each(function (item){
        target.appendChild(item);
    });

    cleanQuantity();
    cleanPromos();
}

/**
 * Event method
 */
function unselectForBill(event)
{
    var element = $(event.element());
    element.stopObserving();
    element.remove();
}
