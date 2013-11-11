/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function payInit()
{
    disableValidation();

    jQuery('#valuefields button').on('click', selectPaymentMode);
    jQuery('form input').on('keypress', disableValidation);
    jQuery('#ok').on('click', updatePaymentForm);
}

function selectPaymentMode(event)
{
    var valueBlock = jQuery(this).parent().next();
    valueBlock.toggle();
    if (!valueBlock.is(':visible'))
    {
        valueBlock.find('input').val(0);
    }
    _helpTotal();
    disableValidation();
}

function updatePaymentForm()
{
    var totalGiven = 0;
    var inputs = jQuery('#valuefields input');

    inputs.each(function(index) {
        var item = jQuery(this);
        if (isNaN(item.val()))
        {
            item.val(0);
        }

        if (!item.is('#monreturned'))
        {
            totalGiven += parseFloat(item.val());
        }

        item.val(currency(item.val()));
    });

    var total = currency(jQuery('#total').val());
    if (isNaN(total))
    {
        alert('Total sale price is corrupted. Please retip article list.');
    }
    else
    {
        if (totalGiven < total)
        {
            alert('Insufficent payment');
        }
        else
        {

            var returned = Math.round(100 * (totalGiven - parseFloat(total))) / 100;
            if (returned < 0)
            {
                returned = 0;
            }

            jQuery('#monreturned').val(currency(returned));

            enableValidation();
        }
    }
}

function disableValidation()
{
    jQuery('#validate').attr('disabled', 'disabled').removeClass('btn-success');
}

function enableValidation()
{
    jQuery('#validate').removeAttr('disabled').addClass('btn-success');
}

function _helpTotal()
{
    var valueFields = jQuery('#valuefields').find('input:visible');
    if (1 == valueFields.length)
    {
        valueFields.val(jQuery('#total').val());
    }
}