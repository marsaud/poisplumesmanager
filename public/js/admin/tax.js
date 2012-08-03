function taxInit()
{
    var taxBox = $('modid');
    taxBox.observe('change', requestForTax)
    var updateForm = $('updatetaxform');
    updateForm.observe('submit', checkUpdateTax);
}

function requestForTax()
{
    var taxBox = $('modid');
    var id = $F(taxBox);

    new Ajax.Request('/admin/tax/get/format/json/id/' + id,
    {
        onSuccess: function (response){
            updateUpdateTaxForm(response);
        },
        onFailure: function() {
            alert('ERROR');
        }
    }
    );
}

function updateUpdateTaxForm(response)
{
    var tax = eval(response.responseJSON);

    if (tax != null)
    {
        $('modratio').setValue(tax.ratio);
        $('moddesc').setValue(tax.description);
    }
    else
    {
        alert('No JSON response');
    }
}

function checkUpdateTax(event)
{
    var taxBox = $('modid');
    var id = $F(taxBox);

    if (id == 0)
    {
        alert('Choisissez la Taxe à modifier');
        event.stop();
    }
}