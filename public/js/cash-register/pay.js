/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * A global timer for total price refereshing
 */
var updateReturnedTimer = null;
var countCheckBoxes = 0;

function payInit()
{
    var checkboxes = $A(['cb', 'chq', 'chr', 'mon']);
    checkboxes.each(function (item){
        var checkbox = $(item);
        _selectPaymentMode(checkbox);
        checkbox.observe('change', selectPaymentMode);
        var given = $(item + 'given');
        given.observe('keypress', disableValidation())
    });
    $('ok').observe('click', updatePaymentForm);
    disableValidation();
}

function selectPaymentMode(event)
{   
    var source = event.findElement();
    disableValidation();
    _selectPaymentMode(source);
}

function _selectPaymentMode(checkbox)
{
    var paymentMode = checkbox.getAttribute('id');
    var activated = ($F(checkbox) == 'on');
    
    activated ? _activateFields(paymentMode) : _desactivateFields(paymentMode);
    
    _helpTotal();
}

function _activateFields(mode)
{
    var fields = $A($('paymentForm').getElementsByClassName(mode + 'fields'));
    fields.each(function (item){
        if ($(item).hasClassName('invisible'))
        {
            $(item).removeClassName('invisible');
            countCheckBoxes++;
        }
    });
}

function _desactivateFields(mode)
{
    var fields = $A($('paymentForm').getElementsByClassName(mode + 'fields'));
    fields.each(function (item){
        if (!$(item).hasClassName('invisible'))
        {
            $(item).addClassName('invisible');
            countCheckBoxes--;
        }
        var inputs = $A(item.getElementsByTagName('input'));
        inputs.each(function (input){
            input.setValue(0);
        })
    });
}

function updatePaymentForm()
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
    
    enableValidation();
}

function disableValidation()
{
    $('validate').setAttribute('disabled', 'disabled');
}

function enableValidation()
{
    $('validate').removeAttribute('disabled');
}

function _helpTotal()
{
    if (countCheckBoxes == 1)
    {
        var modes = $A(['cb', 'chq', 'chr']);
        modes.each(function (item){
            if ($F($(item)) == 'on')
            {
                $(item + 'given').setValue(currency($F($('total'))));
            }
        }
        );
    }
}