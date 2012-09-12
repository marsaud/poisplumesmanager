/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function currency(price)
{
    var sPrice = price.toString().split('.');
    return sPrice[0] + '.' + _processDecimals(sPrice[1]);
}

function _processDecimals(decimals)
{
    if (typeof decimals == 'undefined')
    {
        decimals = '00';
    }

    if (decimals.length > 2)
    {
        decimals = decimals.substring(0, 2);
    }

    while (decimals.length < 2)
    {
        decimals += '0';
    }

    return decimals;
}

//function testCurrency()
//{
//    alert(currency('0'));
//    alert(currency('1'));
//    alert(currency('1.00003'));
//    alert(currency('1234'));
//    alert(currency('1234.1'));
//    alert(currency('1234.12'));
//    alert(currency('1234.123'));
//}

function promotedPrice(price, ratio)
{
    price = parseFloat(price);
    ratio = parseFloat(ratio);
    if (isNaN(ratio))
    {
        ratio = 0;
    }

    var result = (price * (1 + ratio / 100));

    return _priceValue(result);
}

//function testPromotedPrice()
//{
//    alert(promotedPrice(0, -50));
//    alert(promotedPrice(0, 50));
//    alert(promotedPrice(0, 0));
//    alert(promotedPrice(10, -50));
//    alert(promotedPrice(10, 50));
//    alert(promotedPrice(10, 0));
//    alert(promotedPrice(100, -30));
//    alert(promotedPrice(100, 30));
//    alert(promotedPrice(100, 0));
//}

function _priceValue(price)
{
    price = parseFloat(price) * 100;
    price = price.round() / 100;

    return price;
}