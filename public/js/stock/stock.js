function stockInit()
{
    var buttons = $$('input[type=button]');
    buttons.each(function (item){
       item.observe('click', updateStock); 
    });
}

function updateStock(event)
{
    var cell = $(event.findElement('td[class="eventcell"]'));
    var reference = cell.getAttribute('id');
    
    var quantity = $F($('q_' + reference));
    var comment = $F($('c_' + reference));
    
    new Ajax.Request('/stock/index/update/format/json/ref/' + reference + '/qty/' + quantity + '/cmnt/' + comment,
        {
            onSuccess: function (response){
                updateStockDisplay(response);
            },
            onFailure: function() {
                alert('ERROR');
            }
        }
        );
}

function updateStockDisplay(response)
{
    var infos = eval(response.responseJSON);
    
    $('d_' + infos.reference).update(infos.quantity + ' ' + infos.unit);
    $('q_' + infos.reference).setValue('');
    $('c_' + infos.reference).setValue('(r.a.s.)');
}
