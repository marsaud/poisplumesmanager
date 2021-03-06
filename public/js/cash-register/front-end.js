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
 * A global timer for total price refereshing
 */
var totalUpdateTimer = null;

var searchTimer;

var loaderImageUrl;

var searchMinLength;

/**
 * Init the cash-register pad at load or reload
 */
function frontEndInit(myLoaderImageUrl, mySearchMinLength)
{
    loaderImageUrl = myLoaderImageUrl;
    searchMinLength = mySearchMinLength;

    jQuery('#quantity').find('input').on('click', toggleQuantity);
    jQuery('#categorypad').find('.category').on('click', selectCategory);
    jQuery('#subcategorypad').find('.category').on('click', selectSubCategory);
    jQuery('#articlepad').find('.article').on('click', selectForBill);

    var promoPad = jQuery('#promoPad');
    promoPad.find('input').on('click', togglePromo);
    promoPad.hide();

    jQuery('#promoToggle').on('click', togglePromoPad);
    jQuery('#promoRemove').on('click', togglePromoRemove);

    jQuery('#total').val(currency(0) + ' €');
    jQuery('#search input').on('keyup', watchSearch);
    jQuery('#search').on('show.bs.dropdown', checkSearchShow);
}

/**
 * Event method
 */
function toggleQuantity(event)
{
    var element = jQuery(this);

    if (element.is('.highlight'))
    {
        qty = 1;
        element.removeClass('highlight');
    }
    else
    {
        cleanQuantity();
        qty = element.attr('qty');
        element.addClass('highlight');
    }
}

/**
 * Event method
 */
function togglePromo(event)
{
    var element = jQuery(this);

    if (element.is('.highlight'))
    {
        promo = null;
        element.removeClass('highlight');
    }
    else
    {
        cleanPromos();
        promo = {};
        promo['id'] = element.attr('promoid');
        promo['ratio'] = element.attr('promoratio');
        element.addClass('highlight');
    }
}

/**
 * Event method
 */
function togglePromoRemove(event)
{
    var element = jQuery(this);

    if (element.hasClass('highlight'))
    {
        removePromo = false;
        element.removeClass('highlight');
    }
    else
    {
        cleanPromos();
        removePromo = true;
        element.addClass('highlight');
    }
}

/**
 * Resets an inactive quantity modficators pad
 */
function cleanQuantity()
{
    qty = 1;
    jQuery('#quantity').find('input').removeClass('highlight');
}

/**
 * Resets an inactive quantity modficators pad
 */
function cleanPromos()
{
    promo = null;
    jQuery('#promoPad').find('input').removeClass('highlight');
    removePromo = false;
    jQuery('#promoRemove').removeClass('highlight');
}

/**
 * Event method
 *
 * Open/Close promotions touchpad
 */
function togglePromoPad()
{
    if (null == promo)
    {
        jQuery('#promoPad').toggle();
    }
}

/**
 * Event method
 *
 * Select a category to load its sub-subcategories and articles
 */
function selectCategory(event)
{
    var category = jQuery(this).attr('ref');
    if (null != categoryTreeCash[category])
    {
        _updateSubCategoryPad(category, categoryTreeCash[category]);
    }
    else
    {
        jQuery.ajax({
            url: '/cash-register/index/get-categories/category/' + category,
            type: "GET",
            dataType: "html",
            success: function(response) {
                updateSubCategoryPad(response, category);
            },
            error: function(xhr, status) {
                alert('ERROR ' + status);
            }
        });
    }
}

/**
 * Event method
 */
function selectSubCategory(event)
{
    var category = jQuery(this).attr('ref');
    if (null != categoryTreeCash[category])
    {
        jQuery('#subcategorypad').html(categoryTreeCash[category]);
    }
    else
    {
        jQuery.ajax({
            url: '/cash-register/index/get-articles/category/' + category,
            type: "GET",
            dataType: "html",
            success: function(response) {
                updateArticlePad(response);
            },
            error: function(xhr, status) {
                alert('ERROR ' + status);
            }
        });
    }
}

/**
 * Ajax callback
 */
function updateSubCategoryPad(response, category)
{
    if (null != response)
    {
        categoryTreeCash[category] = response;
        _updateSubCategoryPad(category, response);
    }
    else
    {
        alert('No HTML Response');
    }
}

function _updateSubCategoryPad(category, subCategories)
{
    jQuery('#subcategorypad').html(subCategories);
    jQuery('#subcategorypad').find('.category').on('click', selectSubCategory);

    if (null != articlesCash[category])
    {
        _updateArticlePad(articlesCash[category])
    }
    else
    {
        jQuery.ajax({
            url: '/cash-register/index/get-articles/category/' + category,
            type: "GET",
            dataType: "html",
            success: function(response) {
                updateArticlePad(response, category);
            },
            error: function(xhr, status) {
                alert('ERROR ' + status);
            }
        });
    }

}

/**
 * Event method
 */
function updateArticlePad(response, category)
{
    if (null != response)
    {
        articlesCash[category] = response;
        _updateArticlePad(response);
    }
    else
    {
        alert('No HTML Response');
    }
}

function _updateArticlePad(articles)
{
    jQuery('#articlepad').html(articles);
    jQuery('#articlepad').find('.article').on('click', selectForBill);
}

/**
 * Event method
 */
function selectForBill(event)
{
    hideTotalPrice();
    stopTotalUpdate();

    var element = jQuery(this);
    var target = jQuery('#billList div[ref="' + element.attr('ref') + '"]');

    if (0 == target.length)
    {
        target = element.clone();
        target.on('click', unselectForBill);
        jQuery('#billList').append(target);
    }
    else
    {
        ;
    }

    var inputData = target.find('.inputdata');
    var faceValue = target.find('.facevalue');

    var quantityInput = inputData.find('.qty');
    var promoidInput = inputData.find('.promoid');

    var qtyBuffer = parseInt(quantityInput.val()) + parseInt(qty);
    quantityInput.val(qtyBuffer);
    var inputText = quantityInput.val() + 'x ' + target.attr('name');

    if (removePromo)
    {
        promoidInput.val('');
        target.attr('promoratio', '0');
        inputText += '<br />';
    }
    else if (null != promo)
    {
        promoidInput.val(promo['id']);
        target.attr('promoratio', promo['ratio']);
        inputText += '<br />' + promo['ratio'] + '%';
    }
    else if ('' != promoidInput.val())
    {
        inputText += '<br />' + target.attr('promoratio') + '%';
    }
    else
    {
        inputText += '<br />';
    }

    inputText += '<br />' + currency(_priceValue(promotedPrice(target.attr('saleprice'), target.attr('promoratio')) * qtyBuffer)) + ' €'; // todo On a un débordement par arrondi

    faceValue.html(inputText);

    cleanQuantity();
    cleanPromos();

    triggerTotalUpdate();
}

/**
 * Event method
 */
function unselectForBill(event)
{
    hideTotalPrice();
    stopTotalUpdate();
    jQuery(this).remove();
    triggerTotalUpdate();
}

function updateTotalPrice()
{
    var totalPrice = 0;

    jQuery('#billList .article').each(function(index) {
        var item = jQuery(this);
        var promoratio = parseFloat(item.attr('promoratio'));
        if (isNaN(promoratio))
        {
            promoratio = 0;
        }

        var quantity = item.find('.inputdata').find('.qty').val();
        var saleprice = parseFloat(item.attr('saleprice'));

        totalPrice += _priceValue(promotedPrice(saleprice, promoratio) * quantity);
    });

    jQuery('#total').val(currency(totalPrice) + ' €');
}

function hideTotalPrice()
{
    jQuery('#total').val('...');
}

function triggerTotalUpdate()
{
    stopTotalUpdate();
    totalUpdateTimer = setTimeout(updateTotalPrice, 200);
}

function stopTotalUpdate()
{
    if (null != totalUpdateTimer)
    {
        clearTimeout(totalUpdateTimer);
    }
}

function watchSearch(event)
{
    var input = jQuery(this);
    var search = input.val();

    if ((search.length) >= searchMinLength)
    {
        startSearchTimer(search);
        if (!jQuery('#search ul').is(':visible'))
        {
            input.dropdown('toggle');
        }
    }
    else
    {
        jQuery('#search ul').empty();
        if (jQuery('#search ul').is(':visible'))
        {
            input.dropdown('toggle');
        }
    }
}

function startSearchTimer(search)
{
    if (searchTimer !== null)
    {
        clearTimeout(searchTimer);
    }

    if ((search.length) >= searchMinLength)
    {
        jQuery('#search ul').empty();
        searchTimer = setTimeout(function() {
            requestSearch(search);
        },
                700);
    }
}

function requestSearch(search)
{
    jQuery('#search ul').html('<li role="presentation"><i class="fa fa-spinner fa-spin fa-2x"></i></li>');
    if (search.length >= searchMinLength)
    {
        jQuery.ajax({
            url: '/cash-register/index/get-search/search/' + search,
            type: "GET",
            dataType: "html",
            success: function(response) {
                updateSearchDrop(response);
            },
            error: function(xhr, status) {
                alert('ERROR ' + status);
            }
        });
    }
}

function updateSearchDrop(response)
{
    if (null != response)
    {
        jQuery('#search ul').html(response);
        jQuery('#search li a').on('click', requestArticle);
    }
    else
    {
        alert('No HTML response');
    }
}

function requestArticle(event)
{
    var ref = jQuery(this).attr('ref');

    jQuery.ajax({
        url: '/cash-register/index/get-article/ref/' + ref,
        type: "GET",
        dataType: "html",
        success: function(response) {
            updateSearchArticle(response);
        },
        error: function(xhr, status) {
            alert('ERROR ' + status);
        }
    });
}

function updateSearchArticle(response)
{
    if (null != response)
    {
        jQuery('#search-article').html(response);
        jQuery('#search-article').find('.article').on('click', selectForBill);
    }
    else
    {
        alert('No HTML response');
    }
}

function checkSearchShow(event)
{
    if (jQuery(this).find('input').val().length < searchMinLength)
    {
        event.preventDefault();
    }
}