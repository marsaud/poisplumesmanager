function purchaseInit()
{
    jQuery('a.confirm').on('click', confirmDeleteLink);
}

function confirmDeleteLink(event)
{
    var line = jQuery(this).closest('tr.purchaseline');
    line.addClass('deleteselected');
    
    var confirmation = confirm('Supprimer definitivement cet achat ?');
    if (!confirmation)
    {
        event.preventDefault();
        line.removeClass('deleteselected');
    }
}