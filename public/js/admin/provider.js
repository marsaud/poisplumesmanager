function providerInit()
{
    var providerBox = $('modid');
    providerBox.observe('change', requestForProvider)
    var updateForm = $('updateproviderform');
    updateForm.observe('submit', checkUpdateProvider);
}

function requestForProvider()
{
    var providerBox = $('modid');
    var id = $F(providerBox);

    if (id == 0)
    {
        defaultUpdateProviderForm();
    }
    else
    {
        new Ajax.Request('/admin/provider/get/format/json/id/' + id,
        {
            onSuccess: function (response){
                updateUpdateProviderForm(response);
            },
            onFailure: function() {
                alert('ERROR');
            }
        }
        );
    }
}

function defaultUpdateProviderForm()
{
    $('modinfo').setValue('(r.a.s.)');
    $('modcomment').setValue('(r.a.s.)');
}

function updateUpdateProviderForm(response)
{
    var provider = eval(response.responseJSON);

    if (provider != null)
    {
        $('modinfo').setValue(provider.info);
        $('modcomment').setValue(provider.comment);
    }
    else
    {
        alert('No JSON response');
    }
}

function checkUpdateProvider(event)
{
    var providerBox = $('modid');
    var id = $F(providerBox);

    if (id == 0)
    {
        alert('Choisissez le fournisseur à modifier');
        event.stop();
    }
}