function providerInit()
{
    jQuery('#modid').on('change', requestForProvider);
    jQuery('#updateproviderform').on('submit', checkUpdateProvider);
}

function requestForProvider()
{
    var id = jQuery('#modid').val();

    if ('' == id)
    {
        defaultUpdateProviderForm();
    }
    else
    {
        jQuery.ajax({
            url: '/admin/provider/get/format/json/id/' + id,
            type: "GET",
            dataType: "json",
            success: updateUpdateProviderForm,
            error: function(xhr, status) {
                alert('ERROR ' + status);
            }
        });
    }
}

function defaultUpdateProviderForm()
{
    jQuery('#modinfo').val('');
    jQuery('#modcomment').val('');
}

function updateUpdateProviderForm(response)
{
    if (null != response)
    {
        jQuery('#modinfo').val(response.info);
        jQuery('#modcomment').val(response.comment);
    }
    else
    {
        alert('No JSON response');
    }
}

function checkUpdateProvider(event)
{
    if ('' == jQuery('#modid').val())
    {
        alert('Choisissez le fournisseur à modifier');
        event.preventDefault();
    }
}