function incomeInit()
{
    jQuery('a.confirm').on('click', confirmDeleteLink);
}

function confirmDeleteLink(event)
{
    var line = jQuery(this).closest('tr.incomeline');
    line.addClass('deleteselected');
    var confirmation = confirm('Supprimer definitivement cette entrée ?');
    if (!confirmation)
    {
        event.preventDefault();
        line.removeClass('deleteselected');
    }
}