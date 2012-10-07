/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * A global timer for total price refereshing
 */
var updateReturnedTimer = null;

function payInit()
{
    var paymentList = $('payment');
    paymentList.observe('change', selectPaymentMode);
    
    selectPaymentMode();
    
    var given = $('given');
    given.observe('keypress', triggerUpdateReturned);
}

function selectPaymentMode()
{
    _resetFields();
    
    var source = $('payment');
    var mode = $F(source);
    
    switch (mode)
    {
        case 'MON':
            _activateMoneyFields();
            break;
        case 'CB':
        case 'CHQ':
            break;
        case 'CHR':
            _activateChrFields();
            break;
        default :
            alert('Unknown payment mode');
            break;
    }
}

function _resetFields()
{
    _desactivateChrFields();
    _desactivateMoneyFields();
    
    $('given').setValue(0);
    $('returned').setValue(0);
    $('chrgiven').setValue(0);
}

function _activateMoneyFields()
{
    var fields = $A($('paymentForm').getElementsByClassName('moneyfields'));
    fields.each(function (item){
        if ($(item).hasClassName('invisible'))
        {
            $(item).removeClassName('invisible');
        }
    });
}

function _desactivateMoneyFields()
{
    var fields = $A($('paymentForm').getElementsByClassName('moneyfields'));
    fields.each(function (item){
        if (!$(item).hasClassName('invisible'))
        {
            $(item).addClassName('invisible');
        }
    });
}

function _activateChrFields()
{
    var fields = $A($('paymentForm').getElementsByClassName('chrfields'));
    fields.each(function (item){
        if ($(item).hasClassName('invisible'))
        {
            $(item).removeClassName('invisible');
        }
    });
}

function _desactivateChrFields()
{
    var fields = $A($('paymentForm').getElementsByClassName('chrfields'));
    fields.each(function (item){
        if (!$(item).hasClassName('invisible'))
        {
            $(item).addClassName('invisible');
        }
    });
}

function updateReturned()
{
    var given = $F($('given'));
    if (isNaN(given) || given == '')
    {
        given = 0;
    }
    
    given = currency(given);
    $('given').setValue(given);
    
    var total = currency($F($('total')));
    if (isNaN(total))
    {
        alert('Total sale price is corrupted. Please retip article list.');
    }
    
    var returned = parseFloat(given) - parseFloat(total);
    if (returned < 0)
    {
        returned = 0;
    }
     
    $('returned').setValue(currency(returned));
}

function triggerUpdateReturned()
{
    if (updateReturnedTimer != null)
    {
        clearTimeout(updateReturnedTimer);
    }

    updateReturnedTimer = setTimeout(updateReturned, 1000);
}