// @todo Il va falloir recréer cela à partir de la base de données.
var comboReference = {
    a: 3,
    b: 2,
    c: 2
};

function frontEndInit()
{
    var touchpad = $('touchpad');
    var buttons = $A(touchpad.getElementsByTagName('input'));
    buttons.map(Element.extend);
    buttons.each(function (item){
        $(item).observe('click', selectForBill);
    });
}

function selectForBill(event)
{
    var bill = $('billList');
    var element = $(event.element());
    var duplicate = element.clone();
    duplicate.observe('click', unselectForBill);
    bill.appendChild(duplicate);
}

function unselectForBill(event)
{
    var element = $(event.element());
    element.stopObserving();
    element.remove();
}

function searchCombo()
{
    var billList = $('billList');
    var billItems = $A(billList.getElementsByTagName('input'));
    billItems.map(Element.extend);

    var combos = {};
    billItems.each(function (item){
        item = $(item);
        if (undefined != item.getAttribute('combo'))
        {
            var comboTag = item.getAttribute('combo');
            if (undefined != combos[comboTag])
            {
                combos[comboTag] = new Array();
            }

            var comboIndex = item.getAttribute('index');
            if (!Array.isArray(combos[comboTag][comboIndex]))
            {
                combos[comboTag][comboIndex] = new Array();
            }

            combos[comboTag][comboIndex].push(item);
        }
    });


}
