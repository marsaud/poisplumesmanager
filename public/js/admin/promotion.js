function promoInit()
{
    var promoBox = $('modid');
    promoBox.observe('change', requestForPromo)
    var updateForm = $('updatepromoform');
    updateForm.observe('submit', checkUpdatePromo);
}

function requestForPromo()
{
    var promoBox = $('modid');
    var id = $F(promoBox);

    if (id == 0)
    {
        defaultUpdatePromoForm();
    }else
    {

        new Ajax.Request('/admin/promotion/get/format/json/id/' + id,
        {
            onSuccess: function (response){
                updateUpdatePromoForm(response);
            },
            onFailure: function() {
                alert('ERROR');
            }
        }
        );
    }
}

function defaultUpdatePromoForm()
{
    $('modratio').setValue('');
    $('moddesc').setValue('(r.a.s.)');
}

function updateUpdatePromoForm(response)
{
    var promo = eval(response.responseJSON);

    if (promo != null)
    {
        $('modratio').setValue(promo.ratio);
        $('moddesc').setValue(promo.description);
    }
    else
    {
        alert('No JSON response');
    }
}

function checkUpdatePromo(event)
{
    var promoBox = $('modid');
    var id = $F(promoBox);

    if (id == 0)
    {
        alert('Choisissez la promotion à modifier');
        event.stop();
    }
}