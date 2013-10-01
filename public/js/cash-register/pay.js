/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * A global timer for total price refereshing
 */
var updateReturnedTimer = null;
var countButtons = 0;

function payInit()
{
    disableValidation();
    var buttons = $A(['cb', 'chq', 'chr', 'mon']);
    buttons.each(function(item) {
        var button = $(item);
        _selectPaymentMode(button);
        button.observe('click', selectPaymentMode);
        var given = $(item + 'given');
        given.observe('keypress', disableValidation);
    });
    $('ok').observe('click', updatePaymentForm);
}

function selectPaymentMode(event)
{
    var eventSource = $(event.findElement());
    if (eventSource.getAttribute('active') == 'active')
    {
        eventSource.removeAttribute('active');
    }
    else
    {
        eventSource.setAttribute('active', 'active');
    }
    disableValidation();
    _selectPaymentMode(eventSource);
}

function _selectPaymentMode(button)
{
    var paymentMode = button.getAttribute('id');
    var activated = (button.getAttribute('active') == 'active');

    activated ? _activateFields(paymentMode) : _desactivateFields(paymentMode);

    _helpTotal();
}

function _activateFields(mode)
{
    var fields = $A($('paymentForm').getElementsByClassName(mode + 'fields'));
    fields.each(function(item) {
        if ($(item).hasClassName('invisible'))
        {
            $(item).removeClassName('invisible');
            countButtons++;
        }
    });
}

function _desactivateFields(mode)
{
    var fields = $A($('paymentForm').getElementsByClassName(mode + 'fields'));
    fields.each(function(item) {
        if (!$(item).hasClassName('invisible'))
        {
            $(item).addClassName('invisible');
            countButtons--;
        }
        var inputs = $A(item.getElementsByTagName('input'));
        inputs.each(function(input) {
            input.setValue(0);
        });
    });
}

function updatePaymentForm()
{
    var totalGiven = 0;

    var paymentModes = $A(['mon', 'cb', 'chq', 'chr']);
    paymentModes.each(function(item) {
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

    var returned = totalGiven - parseFloat(total);
    returned = Math.round(100 * returned) / 100;
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
    if (countButtons == 1)
    {
        var modes = $A(['cb', 'chq', 'chr']);
        modes.each(function(item) {
            if ($(item).getAttribute('active') == 'active')
            {
                $(item + 'given').setValue(currency($F($('total'))));
            }
        }
        );
    }
}