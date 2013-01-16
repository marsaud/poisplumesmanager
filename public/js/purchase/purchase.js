function purchaseInit()
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
    var purchaseLine = event.findElement('tr.purchaseline');
    purchaseLine.addClassName('deleteselected');
    var confirmation = confirm('Supprimer definitivement cet achat ?');
    if (!confirmation)
    {
        event.stop();
        purchaseLine.removeClassName('deleteselected');
    }
}