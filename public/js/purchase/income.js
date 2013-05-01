function incomeInit()
{
    var confirmLinks = $$('a.confirm');
    confirmLinks.each(
    function (item) {
        item.observe('click', confirmDeleteLink);
    }
);
}

function confirmDeleteLink(event)
{
    var incomeLine = event.findElement('tr.incomeline');
    incomeLine.addClassName('deleteselected');
    var confirmation = confirm('Supprimer definitivement cette entrée ?');
    if (!confirmation)
    {
        event.stop();
        incomeLine.removeClassName('deleteselected');
    }
}