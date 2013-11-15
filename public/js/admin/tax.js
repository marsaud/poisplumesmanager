function taxInit()
{
    jQuery('#modid').on('change', requestForTax);
    jQuery('#updatetaxform').on('submit',checkUpdateTax);
}

function requestForTax()
{
    var id = jQuery(this).val();

    if (0 == id)
    {
        defaultUpdateTaxForm();
    }
    else
    {
        jQuery.ajax({
            url: '/admin/tax/get/format/json/id/' + id,
            type: "GET",
            dataType: "json",
            success: updateUpdateTaxForm,
            error: function(xhr, status) {
                alert('ERROR ' + status);
            }
        });
    }
}

function defaultUpdateTaxForm()
{
    jQuery('#modratio').val('');
    jQuery('#moddesc').val('');
}

function updateUpdateTaxForm(response)
{
    if (null != response)
    {
        jQuery('#modratio').val(response.ratio);
        jQuery('#moddesc').val(response.description);
    }
    else
    {
        alert('No JSON response');
    }
}

function checkUpdateTax(event)
{
    if (0 == jQuery('#modid').val())
    {
        alert('Choisissez la Taxe à modifier');
        event.preventDefault();
    }
}