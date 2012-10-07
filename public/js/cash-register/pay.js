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
    var checkboxes = $A(['cb', 'chq', 'chr', 'mon']);
    checkboxes.each(function (item){
        var checkbox = $(item);
        _selectPaymentMode(checkbox);
        checkbox.observe('change', selectPaymentMode);
        var given = $(item + 'given');
        given.observe('keypress', triggerUpdateReturned)
    });
}

function selectPaymentMode(event)
{   
    var source = event.findElement();
    _selectPaymentMode(source);
}

function _selectPaymentMode(checkbox)
{
    var mode = checkbox.getAttribute('id');
    var activated = ($F(checkbox) == 'on');
    
    activated ? _activateFields(mode) : _desactivateFields(mode);
}

function _activateFields(type)
{
    var fields = $A($('paymentForm').getElementsByClassName(type + 'fields'));
    fields.each(function (item){
        if ($(item).hasClassName('invisible'))
        {
            $(item).removeClassName('invisible');
        }
    });
}

function _desactivateFields(type)
{
    var fields = $A($('paymentForm').getElementsByClassName(type + 'fields'));
    fields.each(function (item){
        if (!$(item).hasClassName('invisible'))
        {
            $(item).addClassName('invisible');
        }
        var inputs = $A(item.getElementsByTagName('input'));
        inputs.each(function (input){
            input.setValue(0);
        })
    });
}

function updateReturned()
{
    var totalGiven = 0;
    
    var paymentModes = $A(['mon', 'cb', 'chq', 'chr']);
    paymentModes.each(function (item){
        var amount = $F($(item + 'given'));
        if (isNaN(amount) || amount == '')
        {
            amount = 0;
        }
            
        amount = currency(amount);
        $(item + 'given').setValue(amount);
            
        totalGiven += parseFloat(amount);
    }
    );
    
    var total = currency($F($('total')));
    if (isNaN(total))
    {
        alert('Total sale price is corrupted. Please retip article list.');
    }
    
    var returned = parseFloat(totalGiven) - parseFloat(total);
    if (returned < 0)
    {
        returned = 0;
    }
     
    $('monreturned').setValue(currency(returned));
}

function triggerUpdateReturned()
{
    if (updateReturnedTimer != null)
    {
        clearTimeout(updateReturnedTimer);
    }

    updateReturnedTimer = setTimeout(updateReturned, 1000);
}