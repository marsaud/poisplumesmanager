function promoInit()
{
    jQuery('#modid').on('change', requestForPromo);
    jQuery('#updatepromoform').on('submit', checkUpdatePromo);
}

function requestForPromo()
{
    var id = jQuery('#modid').val();

    if ('' == id)
    {
        defaultUpdatePromoForm();
    }
    else
    {
        jQuery.ajax({
            url: '/admin/promotion/get/format/json/id/' + id,
            type: "GET",
            dataType: "json",
            success: updateUpdatePromoForm,
            error: function(xhr, status) {
                alert('ERROR ' + status);
            }
        });
    }
}

function defaultUpdatePromoForm()
{
    jQuery('#modratio').val('');
    jQuery('#moddesc').val('');
}

function updateUpdatePromoForm(response)
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

function checkUpdatePromo(event)
{
    if ('' == jQuery('#modid').val())
    {
        alert('Choisissez la promotion à modifier');
        event.preventDefault();
    }
}